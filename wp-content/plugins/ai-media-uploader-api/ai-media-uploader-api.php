<?php
/**
 * Plugin Name: AI Media Uploader API
 * Description: High-performance REST API endpoint for uploading AI generated images and rich metadata directly into WordPress Media Library.
 * Version: 1.0.0
 * Author: Bishow Shrestha 
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AI_Media_Uploader_API {

    const ROUTE_NAMESPACE = 'ai-media/v1';
    const OPTION_API_KEY = 'ai_media_uploader_api_key';

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
        add_action('rest_api_init', array($this, 'handle_cors_headers'));
        add_action('admin_menu', array($this, 'register_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Enable CORS headers for cross-origin extension requests
     */
    public function handle_cors_headers() {
        add_filter('rest_pre_serve_request', function($served, $result, $request, $server) {
            $origin = get_http_origin();
            if ($origin) {
                header("Access-Control-Allow-Origin: $origin");
            } else {
                header("Access-Control-Allow-Origin: *");
            }
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Authorization, X-WP-Nonce, Content-Type, X-AI-Upload-Key');
            header('Access-Control-Allow-Credentials: true');

            if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
                status_header(200);
                exit();
            }

            return $served;
        }, 10, 4);
    }

    /**
     * Register REST API Routes
     */
    public function register_routes() {
        // Health Check Endpoint
        register_rest_route(self::ROUTE_NAMESPACE, '/health', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array($this, 'health_check'),
            'permission_callback' => '__return_true'
        ));

        // Media Upload Endpoint
        register_rest_route(self::ROUTE_NAMESPACE, '/upload', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'handle_upload'),
            'permission_callback' => array($this, 'check_permissions'),
            'args'     => array(
                'image' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Base64 image data URI, raw base64 string, or public HTTP image URL.'
                ),
                'title' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Image title in WordPress Media Library.'
                ),
                'alt_text' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Image HTML alt attribute text.'
                ),
                'caption' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Image caption text.'
                ),
                'description' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Image description content.'
                ),
                'filename' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Preferred filename (e.g. product-image.png).'
                ),
                'author' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Author name or user ID matching WordPress site user (e.g. "John Doe" or "john_doe" or user ID).'
                ),
                'author_name' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Alternative param for Author Name.'
                ),
                'make' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Camera manufacturer (e.g. Fujifilm, Apple, Canon).'
                ),
                'model' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Camera model (e.g. X-H2S, iPhone 15 Pro).'
                ),
                'lensModel' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Lens model info (e.g. XF50-140mm).'
                ),
                'software' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Software info (e.g. iOS 17.5).'
                ),
                'copyright' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Copyright notice.'
                ),
                'country' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Country name.'
                ),
                'state' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'State or Province.'
                ),
                'city' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'City name.'
                ),
                'sub_location' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Sub-location or Neighborhood.'
                ),
                'latitude' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'GPS Decimal Latitude.'
                ),
                'longitude' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'GPS Decimal Longitude.'
                ),
                'dateTimeOriginal' => array(
                    'required'    => false,
                    'type'        => 'string',
                    'description' => 'Original date time string (e.g. 2026:08:20 13:01:44).'
                )
            )
        ));
    }

    /**
     * Health check endpoint
     */
    public function health_check() {
        return rest_ensure_response(array(
            'status'     => 'ok',
            'plugin'     => 'AI Media Uploader API',
            'version'    => '1.0.0',
            'upload_dir' => wp_upload_dir()['path']
        ));
    }

    /**
     * Permission callback supporting API key, application passwords, or logged-in users with upload_files capability
     */
    public function check_permissions($request) {
        // 1. Check API Key header 'X-AI-Upload-Key' or parameter
        $provided_key = $request->get_header('X-AI-Upload-Key');
        if (empty($provided_key)) {
            $provided_key = $request->get_param('api_key');
        }

        $configured_key = get_option(self::OPTION_API_KEY);
        if (defined('AI_MEDIA_UPLOAD_KEY')) {
            $configured_key = AI_MEDIA_UPLOAD_KEY;
        }

        if (!empty($configured_key) && !empty($provided_key)) {
            if (hash_equals($configured_key, $provided_key)) {
                return true;
            }
        }

        // 2. Fallback to standard WP permission (User session / Application Password)
        if (current_user_can('upload_files')) {
            return true;
        }

        // If no API key is configured in settings, auto-generate one or allow upload capability check failure notification
        if (empty($configured_key) && current_user_can('upload_files')) {
            return true;
        }

        return new WP_Error(
            'rest_forbidden',
            __('Unauthorized. Please provide a valid X-AI-Upload-Key header or authenticate with WordPress credentials.', 'ai-media-uploader'),
            array('status' => 401)
        );
    }

    /**
     * Main Handler for Uploading Image + Metadata into WP Media Library
     */
    public function handle_upload($request) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $params      = $request->get_params();
        $title       = isset($params['title']) ? sanitize_text_field($params['title']) : '';
        $alt_text    = isset($params['alt_text']) ? sanitize_text_field($params['alt_text']) : '';
        $caption     = isset($params['caption']) ? sanitize_textarea_field($params['caption']) : '';
        $description = isset($params['description']) ? sanitize_textarea_field($params['description']) : '';
        $custom_name = isset($params['filename']) ? sanitize_file_name($params['filename']) : '';
        $raw_author  = isset($params['author_name']) ? sanitize_text_field($params['author_name']) : (isset($params['author']) ? sanitize_text_field($params['author']) : '');

        // Extract camera, lens, location, and EXIF parameters with full alias support
        $make           = isset($params['make']) ? sanitize_text_field($params['make']) : (isset($params['camera_make']) ? sanitize_text_field($params['camera_make']) : (isset($params['cameraMake']) ? sanitize_text_field($params['cameraMake']) : ''));
        $model          = isset($params['model']) ? sanitize_text_field($params['model']) : (isset($params['camera_model']) ? sanitize_text_field($params['camera_model']) : (isset($params['cameraModel']) ? sanitize_text_field($params['cameraModel']) : ''));
        $lens_model     = isset($params['lensModel']) ? sanitize_text_field($params['lensModel']) : 
                         (isset($params['lens_model']) ? sanitize_text_field($params['lens_model']) : 
                         (isset($params['lensInfo']) ? sanitize_text_field($params['lensInfo']) : 
                         (isset($params['lens_info']) ? sanitize_text_field($params['lens_info']) : 
                         (isset($params['lens']) ? sanitize_text_field($params['lens']) : ''))));
        $software       = isset($params['software']) ? sanitize_text_field($params['software']) : '';
        $copyright      = isset($params['copyright']) ? sanitize_text_field($params['copyright']) : (isset($params['copyright_tag']) ? sanitize_text_field($params['copyright_tag']) : '');
        $country        = isset($params['country']) ? sanitize_text_field($params['country']) : '';
        $state          = isset($params['state']) ? sanitize_text_field($params['state']) : '';
        $city           = isset($params['city']) ? sanitize_text_field($params['city']) : '';
        $sub_location   = isset($params['sub_location']) ? sanitize_text_field($params['sub_location']) : (isset($params['subLocation']) ? sanitize_text_field($params['subLocation']) : (isset($params['location']) ? sanitize_text_field($params['location']) : ''));
        
        $gps_latitude   = isset($params['latitude']) ? sanitize_text_field($params['latitude']) : (isset($params['gpsLatitude']) ? sanitize_text_field($params['gpsLatitude']) : (isset($params['gps_latitude']) ? sanitize_text_field($params['gps_latitude']) : ''));
        $gps_longitude  = isset($params['longitude']) ? sanitize_text_field($params['longitude']) : (isset($params['gpsLongitude']) ? sanitize_text_field($params['gpsLongitude']) : (isset($params['gps_longitude']) ? sanitize_text_field($params['gps_longitude']) : ''));
        $date_time_orig = isset($params['dateTimeOriginal']) ? sanitize_text_field($params['dateTimeOriginal']) : 
                         (isset($params['date_time_original']) ? sanitize_text_field($params['date_time_original']) : 
                         (isset($params['dateOriginal']) ? sanitize_text_field($params['dateOriginal']) : 
                         (isset($params['date_original']) ? sanitize_text_field($params['date_original']) : '')));

        $camera_meta = array(
            'make'               => $make,
            'model'              => $model,
            'lens_model'         => $lens_model,
            'software'           => $software,
            'copyright'          => $copyright,
            'country'            => $country,
            'state'              => $state,
            'city'               => $city,
            'sub_location'       => $sub_location,
            'gps_latitude'       => $gps_latitude,
            'gps_longitude'      => $gps_longitude,
            'date_time_original' => $date_time_orig
        );

        $author_id   = $this->resolve_author_id($raw_author);

        $image_data  = isset($params['image']) ? $params['image'] : '';
        $file_array  = array();
        $temp_file   = '';

        // Case A: File upload via multipart/form-data
        $files = $request->get_file_params();
        if (!empty($files['file'])) {
            $file_upload = $files['file'];
            $upload = wp_handle_upload($file_upload, array('test_form' => false));
            if (isset($upload['error'])) {
                return new WP_Error('upload_error', $upload['error'], array('status' => 500));
            }
            $file_path = $upload['file'];
            $file_url  = $upload['url'];
            $mime_type = $upload['type'];
        } 
        // Case B: Base64 data URI or raw base64 string
        elseif (!empty($image_data) && (strpos($image_data, 'data:image') === 0 || !filter_var($image_data, FILTER_VALIDATE_URL))) {
            $parsed_base64 = $this->parse_base64($image_data);
            if (is_wp_error($parsed_base64)) {
                return $parsed_base64;
            }

            $extension = $parsed_base64['ext'];
            $raw_data  = $parsed_base64['data'];
            $mime_type = $parsed_base64['mime'];

            if (empty($custom_name)) {
                $custom_name = (!empty($title) ? sanitize_title($title) : 'ai-gen-' . time()) . '.' . $extension;
            } elseif (pathinfo($custom_name, PATHINFO_EXTENSION) === '') {
                $custom_name .= '.' . $extension;
            }

            $upload = wp_upload_bits($custom_name, null, $raw_data);
            if (!empty($upload['error'])) {
                return new WP_Error('upload_bits_error', $upload['error'], array('status' => 500));
            }

            $file_path = $upload['file'];
            $file_url  = $upload['url'];
        }
        // Case C: Remote Image URL
        elseif (!empty($image_data) && filter_var($image_data, FILTER_VALIDATE_URL)) {
            $temp_file = download_url($image_data);
            if (is_wp_error($temp_file)) {
                return new WP_Error('url_download_failed', 'Failed to download image from URL: ' . $temp_file->get_error_message(), array('status' => 400));
            }

            $url_filename = basename(parse_url($image_data, PHP_URL_PATH));
            if (empty($custom_name)) {
                $custom_name = !empty($url_filename) ? $url_filename : 'ai-image-' . time() . '.png';
            }

            $file_array['name']     = $custom_name;
            $file_array['tmp_name'] = $temp_file;

            $sideload = wp_handle_sideload($file_array, array('test_form' => false));
            if (isset($sideload['error'])) {
                @unlink($temp_file);
                return new WP_Error('sideload_error', $sideload['error'], array('status' => 500));
            }

            $file_path = $sideload['file'];
            $file_url  = $sideload['url'];
            $mime_type = $sideload['type'];
        } else {
            return new WP_Error('missing_image', 'No valid image data, base64 payload, or file upload provided.', array('status' => 400));
        }

        // Determine title fallback
        if (empty($title)) {
            $title = pathinfo($file_path, PATHINFO_FILENAME);
            $title = ucwords(str_replace(array('-', '_'), ' ', $title));
        }

        // Check mime type fallback
        if (empty($mime_type)) {
            $wp_filetype = wp_check_filetype($file_path, null);
            $mime_type   = $wp_filetype['type'];
        }

        // Read any existing EXIF headers from the uploaded binary stream (e.g. Fujifilm, X-H2S, Lens, GPS)
        $existing_exif = $this->extract_exif_from_file($file_path);
        foreach ($existing_exif as $ex_key => $ex_val) {
            if (empty($camera_meta[$ex_key]) && !empty($ex_val)) {
                $camera_meta[$ex_key] = $ex_val;
            }
        }
        if (empty($make) && !empty($camera_meta['make'])) $make = $camera_meta['make'];
        if (empty($model) && !empty($camera_meta['model'])) $model = $camera_meta['model'];
        if (empty($lens_model) && !empty($camera_meta['lens_model'])) $lens_model = $camera_meta['lens_model'];
        if (empty($date_time_orig) && !empty($camera_meta['date_time_original'])) $date_time_orig = $camera_meta['date_time_original'];
        if (empty($gps_latitude) && !empty($camera_meta['gps_latitude'])) $gps_latitude = $camera_meta['gps_latitude'];
        if (empty($gps_longitude) && !empty($camera_meta['gps_longitude'])) $gps_longitude = $camera_meta['gps_longitude'];

        // Insert attachment object into WordPress database with resolved post_author
        $attachment = array(
            'post_mime_type' => $mime_type,
            'post_title'     => $title,
            'post_content'   => $description,
            'post_excerpt'   => $caption,
            'post_status'    => 'inherit',
            'post_author'    => $author_id
        );

        $attachment_id = wp_insert_attachment($attachment, $file_path);

        if (is_wp_error($attachment_id)) {
            return new WP_Error('db_insert_failed', $attachment_id->get_error_message(), array('status' => 500));
        }

        // Save raw author name as post meta if provided
        if (!empty($raw_author)) {
            update_post_meta($attachment_id, '_uploaded_by_author_name', $raw_author);
        }

        // Get actual WP User display name for response
        $author_user = get_userdata($author_id);
        $resolved_author_name = $author_user ? $author_user->display_name : ($raw_author ?: 'Administrator');

        // Generate attachment metadata (image dimensions, thumbnails, sub-sizes)
        $attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);

        if (!is_array($attach_data)) {
            $attach_data = array();
        }
        if (!isset($attach_data['image_meta']) || !is_array($attach_data['image_meta'])) {
            $attach_data['image_meta'] = array(
                'aperture'          => '0',
                'credit'            => '',
                'camera'            => '',
                'caption'           => '',
                'created_timestamp' => '0',
                'copyright'         => '',
                'focal_length'      => '0',
                'iso'               => '0',
                'shutter_speed'     => '0',
                'title'             => '',
                'orientation'       => '0',
                'keywords'          => array()
            );
        }

        // Fill WordPress attachment image_meta array for metadata checkers
        $attach_data['image_meta']['title']             = $title;
        $attach_data['image_meta']['caption']           = $caption;
        $attach_data['image_meta']['credit']            = $resolved_author_name;
        $attach_data['image_meta']['copyright']         = !empty($copyright) ? $copyright : $resolved_author_name;
        $attach_data['image_meta']['camera']            = trim("$make $model");
        $attach_data['image_meta']['created_timestamp'] = !empty($date_time_orig) ? strtotime($date_time_orig) : time();

        wp_update_attachment_metadata($attachment_id, $attach_data);

        // Set HTML Alt Text & Extended Meta Keys for WP & external checkers
        if (!empty($alt_text)) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
        }
        update_post_meta($attachment_id, '_image_title', $title);
        update_post_meta($attachment_id, '_image_caption', $caption);
        update_post_meta($attachment_id, '_image_description', $description);
        update_post_meta($attachment_id, '_image_author', $resolved_author_name);
        update_post_meta($attachment_id, '_wp_attachment_image_title', $title);

        // Store camera & EXIF meta
        if (!empty($make)) update_post_meta($attachment_id, '_image_make', $make);
        if (!empty($model)) update_post_meta($attachment_id, '_image_model', $model);
        if (!empty($lens_model)) update_post_meta($attachment_id, '_image_lens_model', $lens_model);
        if (!empty($software)) update_post_meta($attachment_id, '_image_software', $software);
        if (!empty($copyright)) update_post_meta($attachment_id, '_image_copyright', $copyright);
        if (!empty($country)) update_post_meta($attachment_id, '_image_country', $country);
        if (!empty($state)) update_post_meta($attachment_id, '_image_state', $state);
        if (!empty($city)) update_post_meta($attachment_id, '_image_city', $city);
        if (!empty($sub_location)) update_post_meta($attachment_id, '_image_sub_location', $sub_location);
        if (!empty($gps_latitude)) update_post_meta($attachment_id, '_image_gps_latitude', $gps_latitude);
        if (!empty($gps_longitude)) update_post_meta($attachment_id, '_image_gps_longitude', $gps_longitude);
        if (!empty($date_time_orig)) update_post_meta($attachment_id, '_image_date_time_original', $date_time_orig);

        // Embed EXIF / IPTC / XMP headers directly into the physical image file binary on disk
        $this->embed_exif_iptc_metadata($file_path, $title, $alt_text, $caption, $description, $resolved_author_name, $camera_meta);

        // Embed EXIF / IPTC / XMP into ALL generated sub-sizes (medium, large, thumbnail, scaled) so right-clicking in WP Media Library preserves EXIF
        $file_dir = dirname($file_path);

        if (!empty($attach_data['sizes']) && is_array($attach_data['sizes'])) {
            foreach ($attach_data['sizes'] as $size_info) {
                if (!empty($size_info['file'])) {
                    $sub_file_path = $file_dir . '/' . $size_info['file'];
                    if (file_exists($sub_file_path)) {
                        $this->embed_exif_iptc_metadata($sub_file_path, $title, $alt_text, $caption, $description, $resolved_author_name, $camera_meta);
                    }
                }
            }
        }

        if (!empty($attach_data['original_image'])) {
            $orig_file_path = $file_dir . '/' . $attach_data['original_image'];
            if (file_exists($orig_file_path)) {
                $this->embed_exif_iptc_metadata($orig_file_path, $title, $alt_text, $caption, $description, $resolved_author_name, $camera_meta);
            }
        }

        // Build sizes list for response
        $sizes = array(
            'full' => $file_url
        );
        if (!empty($attach_data['sizes'])) {
            $upload_dir = wp_upload_dir();
            $base_url   = trailingslashit(dirname($file_url));
            foreach ($attach_data['sizes'] as $size_name => $size_info) {
                $sizes[$size_name] = $base_url . $size_info['file'];
            }
        }

        return rest_ensure_response(array(
            'success'          => true,
            'attachment_id'    => $attachment_id,
            'url'              => $file_url,
            'title'            => $title,
            'alt_text'         => $alt_text,
            'caption'          => $caption,
            'description'      => $description,
            'author_id'        => $author_id,
            'author_name'      => $resolved_author_name,
            'raw_author_input' => $raw_author,
            'image_meta'       => $attach_data['image_meta'],
            'file_path'        => $file_path,
            'mime_type'        => $mime_type,
            'sizes'            => $sizes
        ));
    }

    /**
     * Resolve a text author name or string into an actual WordPress User ID
     */
    private function resolve_author_id($author_input) {
        if (empty($author_input)) {
            $current_user = get_current_user_id();
            return $current_user ? $current_user : 1;
        }

        // 1. If numeric ID passed
        if (is_numeric($author_input)) {
            $user_id = (int)$author_input;
            if (get_userdata($user_id)) {
                return $user_id;
            }
        }

        $author_str = trim($author_input);

        // 2. Exact match by login name
        $user = get_user_by('login', $author_str);
        if ($user) return $user->ID;

        // 3. Exact match by slug / nicename
        $user = get_user_by('slug', sanitize_title($author_str));
        if ($user) return $user->ID;

        // 4. Match by email
        if (is_email($author_str)) {
            $user = get_user_by('email', $author_str);
            if ($user) return $user->ID;
        }

        // 5. Case-insensitive match by Display Name
        global $wpdb;
        $matched_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->users} WHERE LOWER(display_name) = LOWER(%s) LIMIT 1",
            $author_str
        ));
        if ($matched_id) {
            return (int)$matched_id;
        }

        // 6. Search display_name / login / nicename using WP_User_Query
        $user_query = new WP_User_Query(array(
            'search'         => '*' . $author_str . '*',
            'search_columns' => array('display_name', 'user_login', 'user_nicename'),
            'number'         => 1
        ));
        $results = $user_query->get_results();
        if (!empty($results)) {
            return $results[0]->ID;
        }

        // Fallback to logged-in user or admin ID 1
        $current_user = get_current_user_id();
        return $current_user ? $current_user : 1;
    }

    /**
     * Helper to decode base64 strings and extract extension & mime type
     */
    private function parse_base64($base64_string) {
        $mime = 'image/png';
        $ext  = 'png';

        if (preg_match('/^data:(image\/([\w\+\-]+));base64,/', $base64_string, $type)) {
            $base64_string = substr($base64_string, strpos($base64_string, ',') + 1);
            $mime          = strtolower($type[1]);
            $ext           = strtolower($type[2]);
            if ($ext === 'jpeg') $ext = 'jpg';
            if ($ext === 'svg+xml') $ext = 'svg';
        }

        $data = base64_decode($base64_string, true);
        if ($data === false) {
            return new WP_Error('invalid_base64', 'Failed to decode base64 image data.', array('status' => 400));
        }

        return array(
            'data' => $data,
            'mime' => $mime,
            'ext'  => $ext
        );
    }

    /**
     * Extract existing EXIF data from binary image file on disk
     */
    private function extract_exif_from_file($file_path) {
        $extracted = array();
        if (!file_exists($file_path)) return $extracted;

        if (function_exists('exif_read_data')) {
            @$exif = exif_read_data($file_path, 'ANY_TAG', true);
            if ($exif && is_array($exif)) {
                if (!empty($exif['IFD0']['Make'])) $extracted['make'] = trim($exif['IFD0']['Make']);
                if (!empty($exif['IFD0']['Model'])) $extracted['model'] = trim($exif['IFD0']['Model']);
                if (!empty($exif['IFD0']['Software'])) $extracted['software'] = trim($exif['IFD0']['Software']);
                if (!empty($exif['IFD0']['Artist'])) $extracted['author'] = trim($exif['IFD0']['Artist']);
                if (!empty($exif['IFD0']['Copyright'])) $extracted['copyright'] = trim($exif['IFD0']['Copyright']);
                if (!empty($exif['EXIF']['LensModel'])) $extracted['lens_model'] = trim($exif['EXIF']['LensModel']);
                if (!empty($exif['EXIF']['DateTimeOriginal'])) $extracted['date_time_original'] = trim($exif['EXIF']['DateTimeOriginal']);

                if (isset($exif['GPS']['GPSLatitude']) && isset($exif['GPS']['GPSLatitudeRef'])) {
                    $lat = $this->parse_gps_rational_array($exif['GPS']['GPSLatitude'], $exif['GPS']['GPSLatitudeRef']);
                    if ($lat !== null) $extracted['gps_latitude'] = (string)$lat;
                }
                if (isset($exif['GPS']['GPSLongitude']) && isset($exif['GPS']['GPSLongitudeRef'])) {
                    $lng = $this->parse_gps_rational_array($exif['GPS']['GPSLongitude'], $exif['GPS']['GPSLongitudeRef']);
                    if ($lng !== null) $extracted['gps_longitude'] = (string)$lng;
                }
            }
        }

        return $extracted;
    }

    private function parse_gps_rational_array($parts, $ref) {
        if (!is_array($parts) || count($parts) < 3) return null;
        $d = $this->eval_rational($parts[0]);
        $m = $this->eval_rational($parts[1]);
        $s = $this->eval_rational($parts[2]);
        $deg = $d + ($m / 60) + ($s / 3600);
        if (strtoupper((string)$ref) === 'S' || strtoupper((string)$ref) === 'W') {
            $deg = -$deg;
        }
        return $deg;
    }

    private function eval_rational($val) {
        if (is_numeric($val)) return (float)$val;
        if (is_string($val) && strpos($val, '/') !== false) {
            $parts = explode('/', $val);
            if (count($parts) == 2 && $parts[1] != 0) {
                return (float)$parts[0] / (float)$parts[1];
            }
        }
        return 0;
    }

    private function convert_decimal_to_dms($decimal) {
        $abs = abs((float)$decimal);
        $degrees = floor($abs);
        $minutes_decimal = ($abs - $degrees) * 60;
        $minutes = floor($minutes_decimal);
        $seconds = round(($minutes_decimal - $minutes) * 60, 4);

        return array(
            'deg' => (int)$degrees,
            'min' => (int)$minutes,
            'sec' => $seconds
        );
    }

    /**
     * Embed physical EXIF and XMP metadata into WebP, JPEG, and PNG image files on disk
     */
    private function embed_exif_iptc_metadata($file_path, $title, $alt_text, $caption, $description, $author, $camera_meta = array()) {
        if (!file_exists($file_path)) return;

        $exif_bytes = $this->build_exif_tiff_payload($title, $caption, $description, $author, $camera_meta);
        $xmp_bytes  = $this->build_xmp_payload($title, $caption, $description, $author, $camera_meta);

        $file_type = wp_check_filetype($file_path);
        $mime = $file_type['type'];

        if ($mime === 'image/webp') {
            $this->inject_metadata_into_webp($file_path, $exif_bytes, $xmp_bytes);
        } elseif ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $this->inject_metadata_into_jpeg($file_path, $exif_bytes, $xmp_bytes);
        } elseif ($mime === 'image/png') {
            $this->inject_metadata_into_png($file_path, $exif_bytes, $xmp_bytes);
        }
    }

    /**
     * Build binary TIFF payload containing EXIF tags for ExifTool & EXIF viewers
     */
    private function build_exif_tiff_payload($title, $caption, $description, $author, $camera_meta = array()) {
        $text_desc = !empty($caption) ? $caption : $description;

        $make           = isset($camera_meta['make']) ? $camera_meta['make'] : '';
        $model          = isset($camera_meta['model']) ? $camera_meta['model'] : '';
        $software       = isset($camera_meta['software']) ? $camera_meta['software'] : 'AI Media Generator';
        $copyright      = isset($camera_meta['copyright']) ? $camera_meta['copyright'] : '';
        $date_time_orig = isset($camera_meta['date_time_original']) ? $camera_meta['date_time_original'] : '';
        $lens_model     = isset($camera_meta['lens_model']) ? $camera_meta['lens_model'] : '';

        $tags = array();
        if (!empty($title)) {
            $tags[] = array('id' => 0x010d, 'type' => 2, 'value' => $title); // DocumentName / Title
            $tags[] = array('id' => 0x9c9e, 'type' => 1, 'value' => mb_convert_encoding($title, 'UTF-16LE', 'UTF-8') . "\x00\x00"); // XPTitle
        }
        if (!empty($author)) {
            $tags[] = array('id' => 0x013b, 'type' => 2, 'value' => $author); // Artist / Author
            $tags[] = array('id' => 0x9ca0, 'type' => 1, 'value' => mb_convert_encoding($author, 'UTF-16LE', 'UTF-8') . "\x00\x00"); // XPAuthor
        }
        if (!empty($copyright)) {
            $tags[] = array('id' => 0x8298, 'type' => 2, 'value' => $copyright); // Copyright
        } elseif (!empty($author)) {
            $tags[] = array('id' => 0x8298, 'type' => 2, 'value' => $author);
        }

        if (!empty($text_desc)) {
            $tags[] = array('id' => 0x010e, 'type' => 2, 'value' => $text_desc); // ImageDescription
            $tags[] = array('id' => 0x9c9f, 'type' => 1, 'value' => mb_convert_encoding($text_desc, 'UTF-16LE', 'UTF-8') . "\x00\x00"); // XPComment
        }

        if (!empty($make)) {
            $tags[] = array('id' => 0x010f, 'type' => 2, 'value' => $make); // Make
        }
        if (!empty($model)) {
            $tags[] = array('id' => 0x0110, 'type' => 2, 'value' => $model); // Model
        }
        if (!empty($software)) {
            $tags[] = array('id' => 0x0131, 'type' => 2, 'value' => $software); // Software
        }
        if (!empty($date_time_orig)) {
            $tags[] = array('id' => 0x9003, 'type' => 2, 'value' => $date_time_orig); // DateTimeOriginal
            $tags[] = array('id' => 0x9004, 'type' => 2, 'value' => $date_time_orig); // CreateDate
            $tags[] = array('id' => 0x0132, 'type' => 2, 'value' => $date_time_orig); // ModifyDate
        }
        if (!empty($lens_model)) {
            $tags[] = array('id' => 0xa434, 'type' => 2, 'value' => $lens_model); // LensModel
        }

        usort($tags, function($a, $b) {
            return $a['id'] - $b['id'];
        });

        $num_entries = count($tags);
        if ($num_entries === 0) return '';

        $offset_data_start = 8 + 2 + ($num_entries * 12) + 4;
        $ifd_entries = '';
        $data_block  = '';

        foreach ($tags as $tag) {
            $val = $tag['value'];
            if ($tag['type'] === 2 && substr($val, -1) !== "\x00") {
                $val .= "\x00";
            }
            $count = strlen($val);

            if ($count <= 4) {
                $val_padded = str_pad($val, 4, "\x00");
                $ifd_entries .= pack('v', $tag['id']) . pack('v', $tag['type']) . pack('V', $count) . $val_padded;
            } else {
                $current_offset = $offset_data_start + strlen($data_block);
                $ifd_entries .= pack('v', $tag['id']) . pack('v', $tag['type']) . pack('V', $count) . pack('V', $current_offset);
                $data_block .= $val;
                if (strlen($val) % 2 !== 0) {
                    $data_block .= "\x00";
                }
            }
        }

        $tiff_header = "II\x2A\x00\x08\x00\x00\x00";
        $ifd_header  = pack('v', $num_entries) . $ifd_entries . pack('V', 0);

        return $tiff_header . $ifd_header . $data_block;
    }

    /**
     * Build UTF-8 XMP XML payload for Adobe & ExifTool viewers
     */
    private function build_xmp_payload($title, $caption, $description, $author, $camera_meta = array()) {
        $title_esc     = htmlspecialchars($title, ENT_XML1, 'UTF-8');
        $author_esc    = htmlspecialchars($author, ENT_XML1, 'UTF-8');
        $desc_text     = !empty($caption) ? $caption : $description;
        $desc_esc      = htmlspecialchars($desc_text, ENT_XML1, 'UTF-8');

        $make_esc      = htmlspecialchars(isset($camera_meta['make']) ? $camera_meta['make'] : '', ENT_XML1, 'UTF-8');
        $model_esc     = htmlspecialchars(isset($camera_meta['model']) ? $camera_meta['model'] : '', ENT_XML1, 'UTF-8');
        $lens_esc      = htmlspecialchars(isset($camera_meta['lens_model']) ? $camera_meta['lens_model'] : '', ENT_XML1, 'UTF-8');
        $software_esc  = htmlspecialchars(isset($camera_meta['software']) ? $camera_meta['software'] : '', ENT_XML1, 'UTF-8');
        $copyright_esc = htmlspecialchars(isset($camera_meta['copyright']) ? $camera_meta['copyright'] : $author, ENT_XML1, 'UTF-8');
        $country_esc   = htmlspecialchars(isset($camera_meta['country']) ? $camera_meta['country'] : '', ENT_XML1, 'UTF-8');
        $state_esc     = htmlspecialchars(isset($camera_meta['state']) ? $camera_meta['state'] : '', ENT_XML1, 'UTF-8');
        $city_esc      = htmlspecialchars(isset($camera_meta['city']) ? $camera_meta['city'] : '', ENT_XML1, 'UTF-8');
        $sub_loc_esc   = htmlspecialchars(isset($camera_meta['sub_location']) ? $camera_meta['sub_location'] : '', ENT_XML1, 'UTF-8');
        $lat_esc       = htmlspecialchars(isset($camera_meta['gps_latitude']) ? $camera_meta['gps_latitude'] : '', ENT_XML1, 'UTF-8');
        $lng_esc       = htmlspecialchars(isset($camera_meta['gps_longitude']) ? $camera_meta['gps_longitude'] : '', ENT_XML1, 'UTF-8');
        $dt_orig_esc   = htmlspecialchars(isset($camera_meta['date_time_original']) ? $camera_meta['date_time_original'] : '', ENT_XML1, 'UTF-8');

        $xmp = '<?xpacket begin="" id="W5M0MpCehiHzreSzNTczkc9d"?>' . "\n" .
        '<x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.6-c140 79.160451, 2017/05/06-01:08:21        ">' . "\n" .
        ' <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">' . "\n" .
        '  <rdf:Description rdf:about=""' . "\n" .
        '    xmlns:dc="http://purl.org/dc/elements/1.1/"' . "\n" .
        '    xmlns:photoshop="http://ns.adobe.com/photoshop/1.0/"' . "\n" .
        '    xmlns:xmp="http://ns.adobe.com/xap/1.0/"' . "\n" .
        '    xmlns:xmpRights="http://ns.adobe.com/xap/1.0/rights/"' . "\n" .
        '    xmlns:tiff="http://ns.adobe.com/tiff/1.0/"' . "\n" .
        '    xmlns:exif="http://ns.adobe.com/exif/1.0/"' . "\n" .
        '    xmlns:aux="http://ns.adobe.com/exif/1.0/aux/"' . "\n" .
        '    xmlns:Iptc4xmpCore="http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/">' . "\n";

        if (!empty($title_esc)) {
            $xmp .= '   <dc:title><rdf:Alt><rdf:li xml:lang="x-default">' . $title_esc . '</rdf:li></rdf:Alt></dc:title>' . "\n";
            $xmp .= '   <photoshop:Headline>' . $title_esc . '</photoshop:Headline>' . "\n";
        }
        if (!empty($author_esc)) {
            $xmp .= '   <dc:creator><rdf:Seq><rdf:li>' . $author_esc . '</rdf:li></rdf:Seq></dc:creator>' . "\n";
            $xmp .= '   <photoshop:Credit>' . $author_esc . '</photoshop:Credit>' . "\n";
        }
        if (!empty($copyright_esc)) {
            $xmp .= '   <dc:rights><rdf:Alt><rdf:li xml:lang="x-default">' . $copyright_esc . '</rdf:li></rdf:Alt></dc:rights>' . "\n";
            $xmp .= '   <xmpRights:Marked>True</xmpRights:Marked>' . "\n";
        }
        if (!empty($desc_esc)) {
            $xmp .= '   <dc:description><rdf:Alt><rdf:li xml:lang="x-default">' . $desc_esc . '</rdf:li></rdf:Alt></dc:description>' . "\n";
        }
        if (!empty($make_esc)) {
            $xmp .= '   <tiff:Make>' . $make_esc . '</tiff:Make>' . "\n";
        }
        if (!empty($model_esc)) {
            $xmp .= '   <tiff:Model>' . $model_esc . '</tiff:Model>' . "\n";
        }
        if (!empty($software_esc)) {
            $xmp .= '   <tiff:Software>' . $software_esc . '</tiff:Software>' . "\n";
        }
        if (!empty($lens_esc)) {
            $xmp .= '   <aux:Lens>' . $lens_esc . '</aux:Lens>' . "\n";
            $xmp .= '   <exif:LensModel>' . $lens_esc . '</exif:LensModel>' . "\n";
        }
        if (!empty($dt_orig_esc)) {
            $xmp .= '   <exif:DateTimeOriginal>' . $dt_orig_esc . '</exif:DateTimeOriginal>' . "\n";
        }
        if (!empty($country_esc)) {
            $xmp .= '   <photoshop:Country>' . $country_esc . '</photoshop:Country>' . "\n";
        }
        if (!empty($state_esc)) {
            $xmp .= '   <photoshop:State>' . $state_esc . '</photoshop:State>' . "\n";
        }
        if (!empty($city_esc)) {
            $xmp .= '   <photoshop:City>' . $city_esc . '</photoshop:City>' . "\n";
        }
        if (!empty($sub_loc_esc)) {
            $xmp .= '   <Iptc4xmpCore:Location>' . $sub_loc_esc . '</Iptc4xmpCore:Location>' . "\n";
        }
        if (!empty($lat_esc)) {
            $xmp .= '   <exif:GPSLatitude>' . $lat_esc . '</exif:GPSLatitude>' . "\n";
        }
        if (!empty($lng_esc)) {
            $xmp .= '   <exif:GPSLongitude>' . $lng_esc . '</exif:GPSLongitude>' . "\n";
        }

        $xmp .= '  </rdf:Description>' . "\n" .
        ' </rdf:RDF>' . "\n" .
        '</x:xmpmeta>' . "\n" .
        '<?xpacket end="w"?>';

        return $xmp;
    }

    /**
     * Inject EXIF & XMP RIFF Chunks into WebP container
     */
    private function inject_metadata_into_webp($file_path, $exif_bytes, $xmp_bytes) {
        $data = file_get_contents($file_path);
        if (strlen($data) < 12 || substr($data, 0, 4) !== 'RIFF' || substr($data, 8, 4) !== 'WEBP') {
            return false;
        }

        $pos = 12;
        $len = strlen($data);
        $chunks = array();
        $vp8x_chunk = null;
        $width = 0;
        $height = 0;

        while ($pos < $len - 8) {
            $chunk_id   = substr($data, $pos, 4);
            $chunk_size = unpack('V', substr($data, $pos + 4, 4))[1];
            $chunk_data = substr($data, $pos + 8, $chunk_size);
            $pad        = ($chunk_size % 2 !== 0) ? 1 : 0;

            if ($chunk_id === 'VP8X' && $chunk_size >= 10) {
                $vp8x_chunk = $chunk_data;
                $w_low  = ord($chunk_data[4]) | (ord($chunk_data[5]) << 8) | (ord($chunk_data[6]) << 16);
                $h_low  = ord($chunk_data[7]) | (ord($chunk_data[8]) << 8) | (ord($chunk_data[9]) << 16);
                $width  = $w_low + 1;
                $height = $h_low + 1;
            } elseif ($chunk_id !== 'EXIF' && $chunk_id !== 'XMP ') {
                $chunks[] = array('id' => $chunk_id, 'data' => $chunk_data);

                if ($width === 0 && $chunk_id === 'VP8L' && $chunk_size >= 5) {
                    $b1 = ord($chunk_data[1]);
                    $b2 = ord($chunk_data[2]);
                    $b3 = ord($chunk_data[3]);
                    $b4 = ord($chunk_data[4]);
                    $width  = (($b1 | ($b2 << 8)) & 0x3FFF) + 1;
                    $height = (((($b2 | ($b3 << 8)) >> 6) | ($b4 << 10)) & 0x3FFF) + 1;
                } elseif ($width === 0 && $chunk_id === 'VP8 ' && $chunk_size >= 10) {
                    $width  = (ord($chunk_data[6]) | (ord($chunk_data[7]) << 8)) & 0x3FFF;
                    $height = (ord($chunk_data[8]) | (ord($chunk_data[9]) << 8)) & 0x3FFF;
                }
            }

            $pos += 8 + $chunk_size + $pad;
        }

        $has_exif  = !empty($exif_bytes) ? 0x08 : 0;
        $has_xmp   = !empty($xmp_bytes) ? 0x04 : 0;
        $orig_flags= ($vp8x_chunk !== null) ? ord($vp8x_chunk[0]) : 0;
        $new_flags = $orig_flags | $has_exif | $has_xmp;

        $w_val = max(0, $width - 1);
        $h_val = max(0, $height - 1);

        $new_vp8x_data = chr($new_flags) . "\x00\x00\x00" .
                         chr($w_val & 0xFF) . chr(($w_val >> 8) & 0xFF) . chr(($w_val >> 16) & 0xFF) .
                         chr($h_val & 0xFF) . chr(($h_val >> 8) & 0xFF) . chr(($h_val >> 16) & 0xFF);

        $out = 'WEBP';
        $out .= 'VP8X' . pack('V', 10) . $new_vp8x_data;

        foreach ($chunks as $c) {
            $c_len = strlen($c['data']);
            $out .= $c['id'] . pack('V', $c_len) . $c['data'];
            if ($c_len % 2 !== 0) {
                $out .= "\x00";
            }
        }

        if (!empty($exif_bytes)) {
            $e_len = strlen($exif_bytes);
            $out .= 'EXIF' . pack('V', $e_len) . $exif_bytes;
            if ($e_len % 2 !== 0) {
                $out .= "\x00";
            }
        }

        if (!empty($xmp_bytes)) {
            $x_len = strlen($xmp_bytes);
            $out .= 'XMP ' . pack('V', $x_len) . $xmp_bytes;
            if ($x_len % 2 !== 0) {
                $out .= "\x00";
            }
        }

        $file_payload = 'RIFF' . pack('V', strlen($out)) . $out;
        file_put_contents($file_path, $file_payload);
        return true;
    }

    /**
     * Inject EXIF & XMP APP1 Segments into JPEG container
     */
    private function inject_metadata_into_jpeg($file_path, $exif_bytes, $xmp_bytes) {
        $data = file_get_contents($file_path);
        if (strlen($data) < 4 || substr($data, 0, 2) !== "\xFF\xD8") {
            return false;
        }

        $exif_segment = '';
        if (!empty($exif_bytes)) {
            $payload = "Exif\x00\x00" . $exif_bytes;
            $exif_segment = "\xFF\xE1" . pack('n', strlen($payload) + 2) . $payload;
        }

        $xmp_segment = '';
        if (!empty($xmp_bytes)) {
            $payload = "http://ns.adobe.com/xap/1.0/\x00" . $xmp_bytes;
            $xmp_segment = "\xFF\xE1" . pack('n', strlen($payload) + 2) . $payload;
        }

        $new_data = substr($data, 0, 2) . $exif_segment . $xmp_segment . substr($data, 2);
        file_put_contents($file_path, $new_data);
        return true;
    }

    /**
     * Inject eXIf and iTXt Chunks into PNG container
     */
    private function inject_metadata_into_png($file_path, $exif_bytes, $xmp_bytes) {
        $data = file_get_contents($file_path);
        if (strlen($data) < 8 || substr($data, 0, 8) !== "\x89PNG\x0D\x0A\x1A\x0A") {
            return false;
        }

        $pos = 8;
        $len = strlen($data);
        $chunks = array();

        while ($pos < $len - 12) {
            $chunk_len  = unpack('N', substr($data, $pos, 4))[1];
            $chunk_type = substr($data, $pos + 4, 4);
            $chunk_data = substr($data, $pos + 8, $chunk_len);

            if ($chunk_type !== 'eXIf' && $chunk_type !== 'iTXt') {
                $chunks[] = array('type' => $chunk_type, 'data' => $chunk_data);
            }
            $pos += 12 + $chunk_len;
        }

        $out = "\x89PNG\x0D\x0A\x1A\x0A";
        foreach ($chunks as $c) {
            $c_crc = crc32($c['type'] . $c['data']);
            $out .= pack('N', strlen($c['data'])) . $c['type'] . $c['data'] . pack('N', $c_crc);

            if ($c['type'] === 'IHDR') {
                if (!empty($exif_bytes)) {
                    $crc = crc32('eXIf' . $exif_bytes);
                    $out .= pack('N', strlen($exif_bytes)) . 'eXIf' . $exif_bytes . pack('N', $crc);
                }
                if (!empty($xmp_bytes)) {
                    $itxt = "XML:com.adobe.xmp\x00\x00\x00\x00\x00" . $xmp_bytes;
                    $crc = crc32('iTXt' . $itxt);
                    $out .= pack('N', strlen($itxt)) . 'iTXt' . $itxt . pack('N', $crc);
                }
            }
        }

        file_put_contents($file_path, $out);
        return true;
    }

    /**
     * Admin Settings Page for API Key Management
     */
    public function register_settings_page() {
        add_options_page(
            'AI Media Uploader Settings',
            'AI Media Uploader',
            'manage_options',
            'ai-media-uploader',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('ai_media_uploader_options', self::OPTION_API_KEY);
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) return;

        if (isset($_POST['generate_new_key'])) {
            check_admin_referer('ai_media_generate_key');
            $new_key = 'aimedia_' . bin2hex(random_bytes(24));
            update_option(self::OPTION_API_KEY, $new_key);
            echo '<div class="notice notice-success is-dismissible"><p>New API Key Generated Successfully!</p></div>';
        }

        $current_key = get_option(self::OPTION_API_KEY);
        if (empty($current_key)) {
            $current_key = 'aimedia_' . bin2hex(random_bytes(24));
            update_option(self::OPTION_API_KEY, $current_key);
        }

        $endpoint_url = rest_url(self::ROUTE_NAMESPACE . '/upload');
        ?>
        <div class="wrap">
            <h1>AI Media Uploader API Settings</h1>
            <p>Use these settings and API key to connect your AI browser extension or external applications directly to your WordPress Media Library.</p>

            <table class="form-table">
                <tr>
                    <th scope="row">REST Upload Endpoint</th>
                    <td>
                        <code><?php echo esc_url($endpoint_url); ?></code>
                        <p class="description">Send POST requests with image payload and metadata to this endpoint.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">X-AI-Upload-Key Header</th>
                    <td>
                        <input type="text" readonly class="regular-text" value="<?php echo esc_attr($current_key); ?>" id="ai_key_input" onclick="this.select();" />
                        <button type="button" class="button" onclick="navigator.clipboard.writeText(document.getElementById('ai_key_input').value); alert('API Key copied to clipboard!');">Copy Key</button>
                        <p class="description">Include this key in your request header: <code>X-AI-Upload-Key: <?php echo esc_html($current_key); ?></code></p>
                    </td>
                </tr>
            </table>

            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('ai_media_generate_key'); ?>
                <input type="submit" name="generate_new_key" class="button button-secondary" value="Generate New API Key" onclick="return confirm('Generating a new key will invalidate the previous key. Continue?');">
            </form>
        </div>
        <?php
    }
}

new AI_Media_Uploader_API();
