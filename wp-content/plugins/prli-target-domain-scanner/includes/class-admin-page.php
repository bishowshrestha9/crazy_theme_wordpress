<?php
/**
 * Admin Page Renderer for Pretty Links Target Domain Scanner.
 */

if (!defined('ABSPATH')) {
	exit;
}

class PRLI_TDS_Admin_Page {

	public static function init(): void {
		add_action('admin_menu', array(__CLASS__, 'register_admin_menu'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
	}

	public static function register_admin_menu(): void {
		// Under Tools menu
		add_management_page(
			'Pretty Links Domain Checker',
			'Pretty Link Domain Checker',
			'manage_options',
			'prli-target-domain-scanner',
			array(__CLASS__, 'render_page')
		);

		// Also register under Pretty Links top level menu if active
		add_action('admin_menu', function() {
			if (function_exists('menu_page_url') && menu_page_url('pretty-link', false)) {
				add_submenu_page(
					'pretty-link',
					'Pretty Links Target Domain Checker',
					'Domain Checker',
					'manage_options',
					'prli-target-domain-scanner-pl',
					array(__CLASS__, 'render_page')
				);
			}
		}, 99);
	}

	public static function enqueue_assets(string $hook): void {
		if (strpos($hook, 'prli-target-domain-scanner') === false) {
			return;
		}

		$plugin_dir_url = plugin_dir_url(dirname(__FILE__));

		wp_enqueue_style(
			'prli-tds-admin-css',
			$plugin_dir_url . 'assets/css/admin.css',
			array(),
			'1.1.0'
		);

		wp_enqueue_script(
			'prli-tds-admin-js',
			$plugin_dir_url . 'assets/js/admin.js',
			array('jquery'),
			'1.1.0',
			true
		);

		wp_localize_script('prli-tds-admin-js', 'prliTdsConfig', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce'   => wp_create_nonce('prli_tds_nonce'),
		));
	}

	public static function render_page(): void {
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'prli-target-domain-scanner'));
		}

		$domain_input     = '';
		$allow_subdomains = true;
		$filter_type      = 'mismatches';
		$results          = null;
		$has_post         = ($_SERVER['REQUEST_METHOD'] === 'POST');

		if ($has_post) {
			check_admin_referer('prli_tds_scan_action', 'prli_tds_nonce');

			$domain_input     = isset($_POST['prli_tds_domain']) ? sanitize_text_field(wp_unslash($_POST['prli_tds_domain'])) : '';
			$allow_subdomains = isset($_POST['prli_tds_allow_subdomains']);
			$filter_type      = isset($_POST['prli_tds_filter_type']) ? sanitize_key($_POST['prli_tds_filter_type']) : 'mismatches';

			if (!empty(trim($domain_input))) {
				$results = PRLI_TDS_Engine::scan_links($domain_input, $allow_subdomains);
			} else {
				$results = array(
					'success' => false,
					'error'   => 'Please enter a target domain in the input box.',
					'items'   => array(),
					'stats'   => array('total' => 0, 'matched' => 0, 'mismatched' => 0, 'invalid' => 0),
				);
			}
		}

		$is_db_ready = PRLI_TDS_Engine::is_pretty_links_table_present();
		$total_count = $is_db_ready ? PRLI_TDS_Engine::get_total_links_count() : 0;
		?>

		<div class="wrap prli-tds-wrap">
			<div class="prli-tds-header">
				<div class="prli-tds-header-title">
					<span class="dashicons dashicons-admin-links prli-tds-icon"></span>
					<h1>Pretty Links Target Domain Checker</h1>
				</div>
				<p class="prli-tds-subtitle">
					Scan all your Pretty Links target URLs, match them against your expected domain, and identify any target domain mismatches instantly.
				</p>
			</div>

			<?php if (!$is_db_ready) : ?>
				<div class="notice notice-error prli-tds-notice">
					<p>
						<strong>Pretty Links Database Table Not Found:</strong> 
						The table <code><?php echo esc_html(GLOBALS['wpdb']->prefix . 'prli_links'); ?></code> does not exist in your database. 
						Please ensure that the <strong>Pretty Links</strong> plugin is installed and activated.
					</p>
				</div>
			<?php endif; ?>

			<div class="prli-tds-card prli-tds-form-card">
				<h2>Scan Criteria</h2>
				<form id="prli-tds-form" method="post" action="">
					<?php wp_nonce_field('prli_tds_scan_action', 'prli_tds_nonce'); ?>
					
					<div class="prli-tds-form-grid">
						<div class="prli-tds-form-group prli-tds-full">
							<label for="prli_tds_domain" class="prli-tds-label">
								Expected Target Domain <span class="required">*</span>
							</label>
							<div class="prli-tds-input-wrapper">
								<span class="dashicons dashicons-globe prli-input-icon"></span>
								<input 
									type="text" 
									name="prli_tds_domain" 
									id="prli_tds_domain" 
									class="regular-text prli-tds-input" 
									value="<?php echo esc_attr($domain_input); ?>" 
									placeholder="e.g. example.com or https://mybrand.com" 
									required 
								/>
							</div>
							<p class="description">
								Enter the domain you expect your Pretty Links to target. Any link pointing to a different domain will be flagged as a mismatch.
							</p>
						</div>

						<div class="prli-tds-form-group">
							<label class="prli-tds-label">Match Mode & Subdomains</label>
							<label for="prli_tds_allow_subdomains" class="prli-tds-checkbox-label">
								<input 
									type="checkbox" 
									name="prli_tds_allow_subdomains" 
									id="prli_tds_allow_subdomains" 
									value="1" 
									<?php checked($allow_subdomains); ?> 
								/>
								Include subdomains as matching (e.g. <code>sub.example.com</code> matches <code>example.com</code>)
							</label>
						</div>

						<div class="prli-tds-form-group">
							<label for="prli_tds_filter_type" class="prli-tds-label">Show Results</label>
							<select name="prli_tds_filter_type" id="prli_tds_filter_type" class="prli-tds-select">
								<option value="mismatches" <?php selected($filter_type, 'mismatches'); ?>>Mismatched & Invalid Domains Only (Recommended)</option>
								<option value="all" <?php selected($filter_type, 'all'); ?>>Show All Scanned Links</option>
								<option value="matches" <?php selected($filter_type, 'matches'); ?>>Matching Domains Only</option>
								<option value="invalid" <?php selected($filter_type, 'invalid'); ?>>Invalid / Empty URLs Only</option>
							</select>
						</div>
					</div>

					<div class="prli-tds-form-actions">
						<button type="submit" id="prli-tds-submit-btn" class="button button-primary button-hero prli-tds-btn">
							<span class="dashicons dashicons-search"></span> Scan Target Domains
						</button>
						<button type="button" id="prli-tds-reset-btn" class="button button-secondary button-hero">
							Reset
						</button>
						<span id="prli-tds-spinner" class="spinner"></span>
					</div>
				</form>
			</div>

			<!-- Live Stats Cards Container -->
			<div id="prli-tds-stats-container" class="prli-tds-stats-grid" style="<?php echo ($results && $results['success']) ? '' : 'display:none;'; ?>">
				<div class="prli-tds-stat-card prli-card-total">
					<div class="prli-stat-icon"><span class="dashicons dashicons-admin-links"></span></div>
					<div class="prli-stat-content">
						<span class="prli-stat-number" id="prli-stat-total">
							<?php echo $results ? (int) $results['stats']['total'] : $total_count; ?>
						</span>
						<span class="prli-stat-label">Total Links Scanned</span>
					</div>
				</div>

				<div class="prli-tds-stat-card prli-card-matched">
					<div class="prli-stat-icon"><span class="dashicons dashicons-yes-alt"></span></div>
					<div class="prli-stat-content">
						<span class="prli-stat-number" id="prli-stat-matched">
							<?php echo $results ? (int) $results['stats']['matched'] : 0; ?>
						</span>
						<span class="prli-stat-label">Matching Target Domain</span>
					</div>
				</div>

				<div class="prli-tds-stat-card prli-card-mismatched">
					<div class="prli-stat-icon"><span class="dashicons dashicons-warning"></span></div>
					<div class="prli-stat-content">
						<span class="prli-stat-number" id="prli-stat-mismatched">
							<?php echo $results ? (int) $results['stats']['mismatched'] : 0; ?>
						</span>
						<span class="prli-stat-label">Mismatched Domains</span>
					</div>
				</div>

				<div class="prli-tds-stat-card prli-card-invalid">
					<div class="prli-stat-icon"><span class="dashicons dashicons-dismiss"></span></div>
					<div class="prli-stat-content">
						<span class="prli-stat-number" id="prli-stat-invalid">
							<?php echo $results ? (int) $results['stats']['invalid'] : 0; ?>
						</span>
						<span class="prli-stat-label">Invalid / Empty URLs</span>
					</div>
				</div>
			</div>

			<!-- Notice / Alert Container -->
			<div id="prli-tds-notice-box">
				<?php if ($results && !$results['success']) : ?>
					<div class="notice notice-error prli-tds-notice">
						<p><?php echo esc_html($results['error']); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<!-- Results Section -->
			<div id="prli-tds-results-container" class="prli-tds-card prli-tds-results-card" style="<?php echo ($results && $results['success']) ? '' : 'display:none;'; ?>">
				<div class="prli-tds-results-header">
					<div class="prli-results-title-group">
						<h2>Domain Check Results</h2>
						<span class="prli-badge prli-badge-info" id="prli-results-counter">
							Showing <strong id="prli-showing-count">0</strong> items
						</span>
					</div>
					<div class="prli-results-tools">
						<input type="text" id="prli-tds-table-search" placeholder="Search results..." class="regular-text prli-tds-search-input" />
						<button type="button" id="prli-tds-export-csv" class="button button-secondary">
							<span class="dashicons dashicons-download"></span> Export CSV
						</button>
					</div>
				</div>

				<div class="prli-table-responsive">
					<table class="widefat fixed striped prli-tds-table" id="prli-tds-table">
						<thead>
							<tr>
								<th style="width: 70px;">ID</th>
								<th style="width: 200px;">Pretty Link Name & Slug</th>
								<th>Target URL</th>
								<th style="width: 180px;">Detected Host</th>
								<th style="width: 130px;">Status</th>
								<th style="width: 120px; text-align: right;">Actions</th>
							</tr>
						</thead>
						<tbody id="prli-tds-table-body">
							<?php
							if ($results && $results['success']) {
								self::render_table_rows($results['items'], $filter_type);
							}
							?>
						</tbody>
					</table>
				</div>

				<!-- Pagination Container -->
				<div class="prli-tds-pagination-bar" id="prli-tds-pagination">
					<div class="prli-pagination-info">
						Page <span id="prli-current-page">1</span> of <span id="prli-total-pages">1</span>
					</div>
					<div class="prli-pagination-controls">
						<button type="button" class="button" id="prli-pg-prev" disabled>&laquo; Previous</button>
						<button type="button" class="button" id="prli-pg-next" disabled>Next &raquo;</button>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	public static function render_table_rows(array $items, string $filter_type = 'mismatches'): void {
		$displayed = 0;
		foreach ($items as $item) {
			$status = $item['status'];
			if ($filter_type === 'mismatches' && ($status !== 'mismatched' && $status !== 'invalid')) {
				continue;
			}
			if ($filter_type === 'matches' && $status !== 'matched') {
				continue;
			}
			if ($filter_type === 'invalid' && $status !== 'invalid') {
				continue;
			}

			$displayed++;
			?>
			<tr data-id="<?php echo (int) $item['id']; ?>" data-status="<?php echo esc_attr($status); ?>">
				<td><strong>#<?php echo (int) $item['id']; ?></strong></td>
				<td>
					<strong class="prli-item-name"><?php echo esc_html($item['name']); ?></strong><br />
					<code class="prli-slug">/<?php echo esc_html($item['slug']); ?></code>
					<a href="<?php echo esc_url($item['pretty_url']); ?>" target="_blank" class="dashicons dashicons-external" title="Open Pretty Link"></a>
				</td>
				<td class="prli-target-url-cell">
					<?php if (!empty($item['target_url'])) : ?>
						<a href="<?php echo esc_url($item['target_url']); ?>" target="_blank" rel="noopener" class="prli-target-link">
							<?php echo esc_html($item['target_url']); ?>
						</a>
					<?php else : ?>
						<em class="prli-text-muted">(Empty Target URL)</em>
					<?php endif; ?>
				</td>
				<td>
					<?php if (!empty($item['detected_host'])) : ?>
						<code class="prli-host-badge"><?php echo esc_html($item['detected_host']); ?></code>
					<?php else : ?>
						<span class="prli-text-muted">&mdash;</span>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($status === 'matched') : ?>
						<span class="prli-status-badge status-matched">
							<span class="dashicons dashicons-yes"></span> Matched
						</span>
					<?php elseif ($status === 'mismatched') : ?>
						<span class="prli-status-badge status-mismatched">
							<span class="dashicons dashicons-warning"></span> Mismatch
						</span>
					<?php else : ?>
						<span class="prli-status-badge status-invalid">
							<span class="dashicons dashicons-dismiss"></span> Invalid URL
						</span>
					<?php endif; ?>
				</td>
				<td style="text-align: right;">
					<a href="<?php echo esc_url($item['edit_url']); ?>" target="_blank" class="button button-small button-secondary">
						<span class="dashicons dashicons-edit"></span> Edit
					</a>
				</td>
			</tr>
			<?php
		}

		if ($displayed === 0) {
			?>
			<tr>
				<td colspan="6" class="prli-empty-state">
					<span class="dashicons dashicons-yes-alt prli-empty-icon"></span>
					<p>No matching links found for the selected filter.</p>
				</td>
			</tr>
			<?php
		}
	}
}
