<?php
/**
 * Google Drive Uploader for Google Drive Backup
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_Drive_Uploader
{
    private static $instance = null;
    private $oauth_handler;
    private $api_endpoint = 'https://www.googleapis.com/upload/drive/v3/files';

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->oauth_handler = WP_GD_OAuth_Handler::get_instance();
    }

    public function upload_backup($file_path, $backup_metadata = array())
    {
        if (!file_exists($file_path)) {
            return new WP_Error('file_not_found', 'Backup file not found.');
        }

        $access_token = $this->oauth_handler->get_access_token();
        if (is_wp_error($access_token)) {
            return $access_token;
        }

        try {
            // Step 1: Create folder structure if needed
            $backup_folder_id = $this->get_or_create_backup_folder($access_token);
            if (is_wp_error($backup_folder_id)) {
                throw new Exception($backup_folder_id->get_error_message());
            }

            // Step 2: Upload backup file
            $file_metadata = array(
                'name' => basename($file_path),
                'parents' => array($backup_folder_id),
                'description' => isset($backup_metadata['description']) ? $backup_metadata['description'] : '',
            );

            $response = $this->upload_file($file_path, $file_metadata, $access_token);

            if (is_wp_error($response)) {
                throw new Exception($response->get_error_message());
            }

            return array(
                'success' => true,
                'file_id' => $response['id'],
                'file_name' => $response['name'],
                'web_view_link' => $response['webViewLink'],
            );
        } catch (Exception $e) {
            return new WP_Error('upload_failed', $e->getMessage());
        }
    }

    private function get_or_create_backup_folder($access_token)
    {
        // Check if folder exists
        $folder_id = get_option('wp_gd_backup_folder_id');

        if ($folder_id) {
            $exists = $this->check_folder_exists($folder_id, $access_token);
            if (!$exists) {
                delete_option('wp_gd_backup_folder_id');
            } else {
                return $folder_id;
            }
        }

        // Create new folder
        $folder_response = $this->create_folder('WordPress Backups', $access_token);

        if (is_wp_error($folder_response)) {
            return $folder_response;
        }

        update_option('wp_gd_backup_folder_id', $folder_response['id']);

        return $folder_response['id'];
    }

    private function check_folder_exists($folder_id, $access_token)
    {
        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($folder_id);

        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $status = wp_remote_retrieve_response_code($response);
        return 200 === $status;
    }

    private function create_folder($folder_name, $access_token)
    {
        $url = 'https://www.googleapis.com/drive/v3/files?supportsAllDrives=true';

        $body = array(
            'name' => $folder_name,
            'mimeType' => 'application/vnd.google-apps.folder',
        );

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('create_folder_failed', $body['error']['message']);
        }

        return $body;
    }

    private function upload_file($file_path, $file_metadata, $access_token)
    {
        $file_size = filesize($file_path);
        $chunk_size = 256 * 1024 * 1024; // 256MB chunks

        if ($file_size > $chunk_size) {
            return $this->resumable_upload($file_path, $file_metadata, $access_token);
        } else {
            return $this->simple_upload($file_path, $file_metadata, $access_token);
        }
    }

    private function simple_upload($file_path, $file_metadata, $access_token)
    {
        $url = $this->api_endpoint . '?uploadType=multipart&supportsAllDrives=true';

        $file_content = file_get_contents($file_path);

        if (false === $file_content) {
            return new WP_Error('file_read_failed', 'Failed to read backup file.');
        }

        $boundary = 'boundary_' . uniqid();
        $multipart_body = "--" . $boundary . "\r\n";
        $multipart_body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $multipart_body .= json_encode($file_metadata) . "\r\n";
        $multipart_body .= "--" . $boundary . "\r\n";
        $multipart_body .= "Content-Type: application/octet-stream\r\n\r\n";
        $multipart_body .= $file_content . "\r\n";
        $multipart_body .= "--" . $boundary . "--";

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'multipart/related; boundary=' . $boundary,
            ),
            'body' => $multipart_body,
            'timeout' => 300,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('upload_failed', $body['error']['message']);
        }

        return $body;
    }

    private function resumable_upload($file_path, $file_metadata, $access_token)
    {
        $url = $this->api_endpoint . '?uploadType=resumable&supportsAllDrives=true';

        // Initialize resumable session
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'X-Goog-Upload-Protocol' => 'resumable',
                'X-Goog-Upload-Command' => 'start',
                'X-Goog-Upload-Header-Content-Length' => filesize($file_path),
            ),
            'body' => json_encode($file_metadata),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $session_uri = wp_remote_retrieve_header($response, 'location');

        if (!$session_uri) {
            return new WP_Error('resumable_upload_failed', 'Failed to initialize resumable upload.');
        }

        // Upload file in chunks
        $file_handle = fopen($file_path, 'rb');
        if (!$file_handle) {
            return new WP_Error('file_open_failed', 'Failed to open backup file.');
        }

        $chunk_size = 256 * 1024 * 1024;
        $file_size = filesize($file_path);
        $offset = 0;

        while ($offset < $file_size) {
            $chunk = fread($file_handle, $chunk_size);
            if (false === $chunk) {
                fclose($file_handle);
                return new WP_Error('file_read_failed', 'Failed to read chunk from backup file.');
            }

            $chunk_bytes = strlen($chunk);
            $range_end = $offset + $chunk_bytes - 1;

            $headers = array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => $chunk_bytes,
                'Content-Range' => 'bytes ' . $offset . '-' . $range_end . '/' . $file_size,
            );

            $chunk_response = wp_remote_post($session_uri, array(
                'headers' => $headers,
                'body' => $chunk,
                'timeout' => 300,
            ));

            if (is_wp_error($chunk_response)) {
                fclose($file_handle);
                return $chunk_response;
            }

            $status_code = wp_remote_retrieve_response_code($chunk_response);

            if (200 === $status_code || 201 === $status_code) {
                $body = json_decode(wp_remote_retrieve_body($chunk_response), true);
                fclose($file_handle);
                return $body;
            }

            if (308 !== $status_code) {
                fclose($file_handle);
                return new WP_Error('chunk_upload_failed', 'Failed to upload chunk.');
            }

            $offset += $chunk_bytes;
        }

        fclose($file_handle);
        return new WP_Error('resumable_upload_failed', 'Resumable upload did not complete.');
    }

    public function delete_backup($file_id, $access_token = null)
    {
        if (null === $access_token) {
            $access_token = $this->oauth_handler->get_access_token();
            if (is_wp_error($access_token)) {
                return $access_token;
            }
        }

        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($file_id) . '?supportsAllDrives=true';

        $response = wp_remote_request($url, array(
            'method' => 'DELETE',
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);

        if (204 === $status) {
            return array('success' => true);
        }

        return new WP_Error('delete_failed', 'Failed to delete backup from Google Drive.');
    }
}
