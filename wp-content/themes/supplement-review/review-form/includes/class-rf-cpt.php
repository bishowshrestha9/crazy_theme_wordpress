<?php
/**
 * Custom Post Type Registration & Status Management
 *
 * @package Review_Form_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Custom_Post_Type {

	/**
	 * Post Type identifier.
	 */
	const POST_TYPE = 'user_review';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'init', array( __CLASS__, 'register_post_status' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'set_custom_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_custom_columns' ), 10, 2 );
		add_filter( 'views_edit-' . self::POST_TYPE, array( __CLASS__, 'add_status_views' ) );
	}

	/**
	 * Register 'user_review' CPT.
	 */
	public static function register_post_type() {
		$labels = array(
			'name'               => _x( 'Reviews', 'post type general name', 'review-form-plugin' ),
			'singular_name'      => _x( 'Review', 'post type singular name', 'review-form-plugin' ),
			'menu_name'          => _x( 'User Reviews', 'admin menu', 'review-form-plugin' ),
			'name_admin_bar'     => _x( 'Review', 'add new on admin bar', 'review-form-plugin' ),
			'add_new'            => _x( 'Add New', 'review', 'review-form-plugin' ),
			'add_new_item'       => __( 'Add New Review', 'review-form-plugin' ),
			'new_item'           => __( 'New Review', 'review-form-plugin' ),
			'edit_item'          => __( 'Edit Review', 'review-form-plugin' ),
			'view_item'          => __( 'View Review', 'review-form-plugin' ),
			'all_items'          => __( 'All Reviews', 'review-form-plugin' ),
			'search_items'       => __( 'Search Reviews', 'review-form-plugin' ),
			'not_found'          => __( 'No reviews found.', 'review-form-plugin' ),
			'not_found_in_trash' => __( 'No reviews found in Trash.', 'review-form-plugin' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 26,
			'menu_icon'          => 'dashicons-feedback',
			'supports'           => array( 'title', 'editor' ),
			'show_in_rest'       => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Register custom 'rejected' post status.
	 */
	public static function register_post_status() {
		register_post_status( 'rejected', array(
			'label'                     => _x( 'Rejected', 'post status', 'review-form-plugin' ),
			'public'                    => false,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>', 'review-form-plugin' ),
		) );
	}

	/**
	 * Custom columns for Review Submissions table.
	 */
	public static function set_custom_columns( $columns ) {
		$new_columns = array(
			'cb'            => $columns['cb'],
			'title'         => __( 'Review Headline', 'review-form-plugin' ),
			'author_name'   => __( 'Submitter Name', 'review-form-plugin' ),
			'author_email'  => __( 'Submitter Email', 'review-form-plugin' ),
			'review_text'   => __( 'Review Snippet', 'review-form-plugin' ),
			'attachment'    => __( 'Attachment', 'review-form-plugin' ),
			'review_status' => __( 'Status', 'review-form-plugin' ),
			'date'          => __( 'Date Submitted', 'review-form-plugin' ),
		);
		return $new_columns;
	}

	/**
	 * Render content for custom columns.
	 */
	public static function render_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'author_name':
				$name = get_post_meta( $post_id, '_rf_submitter_name', true );
				echo esc_html( ! empty( $name ) ? $name : '—' );
				break;

			case 'author_email':
				if ( current_user_can( 'manage_options' ) ) {
					$email = get_post_meta( $post_id, '_rf_submitter_email', true );
					if ( ! empty( $email ) ) {
						printf( '<a href="%s">%s</a>', esc_url( 'mailto:' . antispambot( $email ) ), esc_html( antispambot( $email ) ) );
					} else {
						echo '—';
					}
				} else {
					echo '<em>' . esc_html__( 'Protected', 'review-form-plugin' ) . '</em>';
				}
				break;

			case 'review_text':
				$content = get_post_field( 'post_content', $post_id );
				echo esc_html( wp_trim_words( $content, 18, '...' ) );
				break;

			case 'attachment':
				$attach_id = (int) get_post_meta( $post_id, '_rf_attachment_id', true );
				$attach_url = get_post_meta( $post_id, '_rf_attachment_url', true );
				if ( $attach_id ) {
					$thumb = wp_get_attachment_image( $attach_id, array( 45, 45 ), true, array( 'style' => 'border-radius:4px;object-fit:cover;' ) );
					$full = wp_get_attachment_url( $attach_id );
					printf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $full ), $thumb );
				} elseif ( ! empty( $attach_url ) ) {
					printf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $attach_url ), esc_html__( 'View File', 'review-form-plugin' ) );
				} else {
					echo '—';
				}
				break;

			case 'review_status':
				$post_status = get_post_status( $post_id );
				if ( 'publish' === $post_status ) {
					echo '<span class="rf-badge-approved" style="background:#e6f4ea;color:#137333;padding:3px 8px;border-radius:4px;font-weight:600;font-size:12px;">' . esc_html__( 'Approved', 'review-form-plugin' ) . '</span>';
				} elseif ( 'rejected' === $post_status ) {
					echo '<span class="rf-badge-rejected" style="background:#fce8e6;color:#c5221f;padding:3px 8px;border-radius:4px;font-weight:600;font-size:12px;">' . esc_html__( 'Rejected', 'review-form-plugin' ) . '</span>';
				} else {
					echo '<span class="rf-badge-pending" style="background:#fef7e0;color:#b06000;padding:3px 8px;border-radius:4px;font-weight:600;font-size:12px;">' . esc_html__( 'Pending', 'review-form-plugin' ) . '</span>';
				}
				break;
		}
	}

	/**
	 * Append custom status filter views to post list.
	 */
	public static function add_status_views( $views ) {
		$count = wp_count_posts( self::POST_TYPE );
		$current = isset( $_GET['post_status'] ) ? sanitize_text_field( wp_unslash( $_GET['post_status'] ) ) : '';

		if ( isset( $count->rejected ) && $count->rejected > 0 ) {
			$class = ( 'rejected' === $current ) ? ' class="current"' : '';
			$views['rejected'] = sprintf(
				'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
				esc_url( admin_url( 'edit.php?post_status=rejected&post_type=' . self::POST_TYPE ) ),
				$class,
				esc_html__( 'Rejected', 'review-form-plugin' ),
				$count->rejected
			);
		}

		return $views;
	}
}
