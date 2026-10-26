<?php
/**
 * Admin Pro Controller.
 *
 * Renders the Pro upsell dashboard.
 *
 * @package ShortLinks\App\Controllers\Admin
 */

declare(strict_types=1);

namespace ShortLinks\App\Controllers\Admin;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class ProController
 */
final class ProController {

    /**
     * Render the Pro upsell view.
     *
     * @return void
     */
    public function renderProPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'short-links' ) );
        }

        $file = SHORTLINKS_VIEWS_DIR . 'admin/pro.php';
        if ( file_exists( $file ) ) {
            include $file;
        }
    }
}
