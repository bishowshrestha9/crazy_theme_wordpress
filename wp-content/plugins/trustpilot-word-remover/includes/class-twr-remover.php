<?php
if (!defined('ABSPATH')) {
    exit;
}

class TWR_Remover {

    /**
     * Smart replacement/removal of target word from text.
     *
     * @param string $text
     * @param string $target_word
     * @param string $replacement
     * @return string
     */
    public function clean_text($text, $target_word = 'Trustpilot', $replacement = '') {
        if (empty($text) || empty($target_word)) {
            return $text;
        }

        $word_pattern = preg_quote($target_word, '/');

        if (trim($replacement) === '') {
            // Smart Removal Mode: Clean up surrounding punctuation & spacing

            // Pattern 1: ", Trustpilot," or ", Trustpilot ," -> ","
            $text = preg_replace('/,\s*' . $word_pattern . '\s*,/i', ',', $text);

            // Pattern 2: ", Trustpilot" (at end of phrase or list before punctuation/space)
            $text = preg_replace('/,\s*' . $word_pattern . '\b/i', '', $text);

            // Pattern 3: "Trustpilot, " (at start of phrase)
            $text = preg_replace('/\b' . $word_pattern . '\s*,\s*/i', '', $text);

            // Pattern 4: Standalone word with surrounding spaces
            $text = preg_replace('/\s+\b' . $word_pattern . '\b\s+/i', ' ', $text);

            // Pattern 5: Fallback standalone word match
            $text = preg_replace('/\b' . $word_pattern . '\b/i', '', $text);

            // Cleanup double commas (e.g. ", ,") or trailing commas before period
            $text = preg_replace('/,\s*,+/', ',', $text);
            $text = preg_replace('/,\s*\./', '.', $text);
            $text = preg_replace('/ {2,}/', ' ', $text);
        } else {
            // Replacement Mode: Replace word directly preserving word boundaries
            $text = preg_replace('/\b' . $word_pattern . '\b/i', $replacement, $text);
        }

        return $text;
    }

    /**
     * Perform post cleaning and save revision.
     *
     * @param int $post_id
     * @param string $target_word
     * @param string $replacement
     * @return array
     */
    public function clean_post($post_id, $target_word = 'Trustpilot', $replacement = '') {
        $post = get_post($post_id);
        if (!$post) {
            return array(
                'success' => false,
                'message' => __('Post not found.', 'trustpilot-word-remover'),
            );
        }

        $original_content = $post->post_content;
        $original_title   = $post->post_title;

        $new_content = $this->clean_text($original_content, $target_word, $replacement);
        $new_title   = $this->clean_text($original_title, $target_word, $replacement);

        if ($original_content === $new_content && $original_title === $new_title) {
            return array(
                'success' => true,
                'message' => __('No occurrences of target word found to remove.', 'trustpilot-word-remover'),
                'updated' => false,
            );
        }

        // Count removed instances
        $removed_count = substr_count(strtolower($original_content), strtolower($target_word)) +
                          substr_count(strtolower($original_title), strtolower($target_word));

        // Create WP Revision
        if (function_exists('wp_save_post_revision')) {
            wp_save_post_revision($post_id);
        }

        // Update post in database
        $post_data = array(
            'ID'           => $post_id,
            'post_content' => $new_content,
            'post_title'   => $new_title,
        );

        $result = wp_update_post($post_data, true);

        if (is_wp_error($result)) {
            return array(
                'success' => false,
                'message' => $result->get_error_message(),
            );
        }

        return array(
            'success'       => true,
            'message'       => sprintf(__('Successfully removed %d occurrence(s) from post #%d.', 'trustpilot-word-remover'), $removed_count, $post_id),
            'updated'       => true,
            'removed_count' => $removed_count,
        );
    }

    /**
     * Generate diff preview for single post.
     *
     * @param int $post_id
     * @param string $target_word
     * @param string $replacement
     * @return array
     */
    public function generate_diff_preview($post_id, $target_word = 'Trustpilot', $replacement = '') {
        $post = get_post($post_id);
        if (!$post) {
            return array('success' => false, 'message' => __('Post not found.', 'trustpilot-word-remover'));
        }

        $scanner = new TWR_Scanner();
        $original_snippets = $scanner->extract_snippets($post->post_content, $target_word);

        $cleaned_content = $this->clean_text($post->post_content, $target_word, $replacement);
        
        // Extract updated context around clean area
        $plain_text = strip_tags($cleaned_content);
        $preview_length = min(300, strlen($plain_text));
        $cleaned_preview = substr($plain_text, 0, $preview_length) . (strlen($plain_text) > 300 ? '...' : '');

        return array(
            'success'          => true,
            'post_id'          => $post_id,
            'post_title'       => $post->post_title,
            'original_snippets'=> $original_snippets,
            'cleaned_content'  => esc_html($cleaned_preview),
        );
    }

    /**
     * Start background slow queue for 4-hour (or custom duration) post replacement.
     */
    public function start_slow_queue($post_ids, $replacement = '', $duration_hours = 4) {
        if (empty($post_ids)) {
            return false;
        }

        $post_ids = array_values(array_unique(array_map('intval', $post_ids)));
        $total_posts = count($post_ids);
        $duration_seconds = max(60, floatval($duration_hours) * 3600);

        // Determine optimal batch size (between 1 and 20) and interval
        if ($total_posts <= 10) {
            $batch_size = 1;
        } elseif ($total_posts <= 50) {
            $batch_size = 2;
        } elseif ($total_posts <= 200) {
            $batch_size = 5;
        } else {
            $batch_size = 10;
        }

        $total_batches = max(1, ceil($total_posts / $batch_size));
        $interval_seconds = max(30, intval(floor($duration_seconds / $total_batches)));

        $queue_data = array(
            'status'                    => 'running',
            'post_ids'                   => $post_ids,
            'processed_ids'              => array(),
            'replacement'                => $replacement,
            'total_posts'                => $total_posts,
            'processed_count'            => 0,
            'total_occurrences_removed'   => 0,
            'duration_hours'             => $duration_hours,
            'batch_size'                 => $batch_size,
            'interval_seconds'           => $interval_seconds,
            'started_at'                 => time(),
            'last_run'                   => time(),
        );

        update_option('twr_slow_queue', $queue_data);
        wp_clear_scheduled_hook('twr_cron_process_queue');

        // Execute batch 1 immediately
        $this->process_next_queue_batch();

        return true;
    }

    /**
     * Process next queue batch called by WP-Cron or AJAX.
     */
    public function process_next_queue_batch() {
        $queue = get_option('twr_slow_queue');
        if (empty($queue) || empty($queue['status']) || $queue['status'] !== 'running') {
            return false;
        }

        $all_ids       = isset($queue['post_ids']) ? $queue['post_ids'] : array();
        $processed_ids = isset($queue['processed_ids']) ? $queue['processed_ids'] : array();
        $replacement   = isset($queue['replacement']) ? $queue['replacement'] : '';
        $batch_size    = isset($queue['batch_size']) ? intval($queue['batch_size']) : 5;

        $remaining_ids = array_values(array_diff($all_ids, $processed_ids));

        if (empty($remaining_ids)) {
            $queue['status'] = 'completed';
            $queue['completed_at'] = time();
            update_option('twr_slow_queue', $queue);
            wp_clear_scheduled_hook('twr_cron_process_queue');
            return true;
        }

        $batch_to_process = array_slice($remaining_ids, 0, $batch_size);
        $cleaned_in_batch = 0;
        $occurrences_in_batch = 0;

        foreach ($batch_to_process as $pid) {
            $res = $this->clean_post($pid, 'Trustpilot', $replacement);
            if ($res['success']) {
                if (!empty($res['updated'])) {
                    $cleaned_in_batch++;
                    $occurrences_in_batch += isset($res['removed_count']) ? $res['removed_count'] : 0;
                }
            }
            $processed_ids[] = $pid;
        }

        $queue['processed_ids'] = array_values(array_unique($processed_ids));
        $queue['processed_count'] = count($queue['processed_ids']);
        $queue['total_occurrences_removed'] += $occurrences_in_batch;
        $queue['last_run'] = time();

        $new_remaining = count($all_ids) - $queue['processed_count'];

        if ($new_remaining <= 0) {
            $queue['status'] = 'completed';
            $queue['completed_at'] = time();
            wp_clear_scheduled_hook('twr_cron_process_queue');
        } else {
            // Schedule next single event batch
            wp_clear_scheduled_hook('twr_cron_process_queue');
            $interval = isset($queue['interval_seconds']) ? intval($queue['interval_seconds']) : 300;
            wp_schedule_single_event(time() + $interval, 'twr_cron_process_queue');
        }

        update_option('twr_slow_queue', $queue);
        return true;
    }

    /**
     * Get queue status and remaining ETA.
     */
    public function get_queue_status() {
        $queue = get_option('twr_slow_queue');
        if (empty($queue)) {
            return array('active' => false, 'status' => 'idle');
        }

        $total = isset($queue['total_posts']) ? intval($queue['total_posts']) : 0;
        $processed = isset($queue['processed_count']) ? intval($queue['processed_count']) : 0;
        $status = isset($queue['status']) ? $queue['status'] : 'idle';
        $percent = $total > 0 ? min(100, round(($processed / $total) * 100)) : 0;

        $remaining_posts = max(0, $total - $processed);
        $batch_size = isset($queue['batch_size']) ? max(1, intval($queue['batch_size'])) : 5;
        $interval_seconds = isset($queue['interval_seconds']) ? max(1, intval($queue['interval_seconds'])) : 300;

        $remaining_batches = ceil($remaining_posts / $batch_size);
        $eta_seconds = $remaining_batches * $interval_seconds;

        return array(
            'active'                    => in_array($status, array('running', 'paused')),
            'status'                    => $status,
            'total_posts'               => $total,
            'processed_count'           => $processed,
            'remaining_posts'           => $remaining_posts,
            'percent'                   => $percent,
            'total_occurrences_removed' => isset($queue['total_occurrences_removed']) ? intval($queue['total_occurrences_removed']) : 0,
            'replacement'               => isset($queue['replacement']) ? $queue['replacement'] : '',
            'duration_hours'            => isset($queue['duration_hours']) ? floatval($queue['duration_hours']) : 4,
            'started_at'                => isset($queue['started_at']) ? intval($queue['started_at']) : 0,
            'last_run'                  => isset($queue['last_run']) ? intval($queue['last_run']) : 0,
            'eta_seconds'               => $eta_seconds,
            'eta_formatted'             => $this->format_seconds($eta_seconds),
        );
    }

    /**
     * Pause, Resume, or Cancel background queue.
     */
    public function control_queue($action) {
        $queue = get_option('twr_slow_queue');
        if (empty($queue)) {
            return false;
        }

        if ($action === 'pause') {
            $queue['status'] = 'paused';
            wp_clear_scheduled_hook('twr_cron_process_queue');
            update_option('twr_slow_queue', $queue);
            return true;
        } elseif ($action === 'resume') {
            $queue['status'] = 'running';
            update_option('twr_slow_queue', $queue);
            $this->process_next_queue_batch();
            return true;
        } elseif ($action === 'cancel') {
            delete_option('twr_slow_queue');
            wp_clear_scheduled_hook('twr_cron_process_queue');
            return true;
        }

        return false;
    }

    /**
     * Emergency Stop: Force kill all background queues and scheduled cron events.
     */
    public function emergency_stop() {
        delete_option('twr_slow_queue');
        wp_clear_scheduled_hook('twr_cron_process_queue');
        return true;
    }

    private function format_seconds($seconds) {
        if ($seconds <= 0) return '0m';
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds % 3600) / 60);
        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $mins);
        }
        return sprintf('%dm', $mins);
    }
}

