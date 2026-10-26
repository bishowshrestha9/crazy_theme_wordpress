<?php
/**
 * Admin Screen for Review Moderation (Approve, Reject, Delete)
 *
 * @package Review_Form_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Admin_Moderation {

	/**
	 * Initialize admin hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_admin_actions' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register submenu page under 'User Reviews'.
	 */
	public static function add_admin_menu() {
		// Calculate pending reviews count badge
		$pending_count = self::get_pending_count();
		$badge = $pending_count > 0 ? sprintf( ' <span class="update-plugins count-%d"><span class="plugin-count">%d</span></span>', $pending_count, $pending_count ) : '';

		add_submenu_page(
			'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE,
			__( 'Moderation Queue', 'review-form-plugin' ),
			__( 'Moderation Queue', 'review-form-plugin' ) . $badge,
			'manage_options',
			'rf-moderation',
			array( __CLASS__, 'render_moderation_page' )
		);
	}

	/**
	 * Enqueue admin styles.
	 */
	public static function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'rf-moderation' ) !== false || 'edit.php' === $hook ) {
			wp_enqueue_style(
				'rf-admin-style',
				RF_PLUGIN_URL . 'assets/css/review-form-admin.css',
				array(),
				RF_PLUGIN_VERSION
			);
		}
	}

	/**
	 * Handle admin actions: Approve, Reject, Delete with capability & nonce checks.
	 */
	public static function handle_admin_actions() {
		if ( ! isset( $_GET['page'] ) || 'rf-moderation' !== $_GET['page'] ) {
			return;
		}

		if ( ! isset( $_GET['rf_action'] ) || ! isset( $_GET['review_id'] ) ) {
			return;
		}

		// Security: Capability Check
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access.', 'review-form-plugin' ) );
		}

		// Security: Nonce Verification
		check_admin_referer( 'rf_admin_moderation_action' );

		$review_id = absint( $_GET['review_id'] );
		$action    = sanitize_key( $_GET['rf_action'] );

		if ( ! $review_id || get_post_type( $review_id ) !== RF_Custom_Post_Type::POST_TYPE ) {
			return;
		}

		$redirect_url = admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation' );
		if ( isset( $_GET['status_filter'] ) ) {
			$redirect_url = add_query_arg( 'status_filter', sanitize_key( $_GET['status_filter'] ), $redirect_url );
		}

		switch ( $action ) {
			case 'approve':
				wp_update_post( array(
					'ID'          => $review_id,
					'post_status' => 'publish',
				) );
				$redirect_url = add_query_arg( 'rf_notice', 'approved', $redirect_url );
				break;

			case 'reject':
				wp_update_post( array(
					'ID'          => $review_id,
					'post_status' => 'rejected',
				) );
				$redirect_url = add_query_arg( 'rf_notice', 'rejected', $redirect_url );
				break;

			case 'delete':
				wp_delete_post( $review_id, true ); // Permanent delete
				$redirect_url = add_query_arg( 'rf_notice', 'deleted', $redirect_url );
				break;
		}

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Render the Moderation Queue table page.
	 */
	public static function render_moderation_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'review-form-plugin' ) );
		}

		$current_status = isset( $_GET['status_filter'] ) ? sanitize_key( $_GET['status_filter'] ) : 'all';
		$notice = isset( $_GET['rf_notice'] ) ? sanitize_key( $_GET['rf_notice'] ) : '';

		// Query reviews based on filter
		$query_args = array(
			'post_type'      => RF_Custom_Post_Type::POST_TYPE,
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( 'pending' === $current_status ) {
			$query_args['post_status'] = 'pending';
		} elseif ( 'publish' === $current_status ) {
			$query_args['post_status'] = 'publish';
		} elseif ( 'rejected' === $current_status ) {
			$query_args['post_status'] = 'rejected';
		} else {
			$query_args['post_status'] = array( 'pending', 'publish', 'rejected' );
		}

		$reviews = get_posts( $query_args );

		// Status counts
		$count_pending  = count( get_posts( array( 'post_type' => RF_Custom_Post_Type::POST_TYPE, 'post_status' => 'pending', 'posts_per_page' => -1, 'fields' => 'ids' ) ) );
		$count_approved = count( get_posts( array( 'post_type' => RF_Custom_Post_Type::POST_TYPE, 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids' ) ) );
		$count_rejected = count( get_posts( array( 'post_type' => RF_Custom_Post_Type::POST_TYPE, 'post_status' => 'rejected', 'posts_per_page' => -1, 'fields' => 'ids' ) ) );
		$count_all      = $count_pending + $count_approved + $count_rejected;
		?>
		<div class="wrap rf-admin-wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Review Moderation Queue', 'review-form-plugin' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Approve, reject, or delete submitted user reviews before they appear publicly.', 'review-form-plugin' ); ?></p>

			<?php if ( 'approved' === $notice ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Review approved successfully.', 'review-form-plugin' ); ?></p></div>
			<?php elseif ( 'rejected' === $notice ) : ?>
				<div class="notice notice-warning is-dismissible"><p><?php esc_html_e( 'Review marked as rejected.', 'review-form-plugin' ); ?></p></div>
			<?php elseif ( 'deleted' === $notice ) : ?>
				<div class="notice notice-error is-dismissible"><p><?php esc_html_e( 'Review deleted permanently.', 'review-form-plugin' ); ?></p></div>
			<?php endif; ?>

			<!-- Status Filter Tabs -->
			<ul class="subsubsub" style="margin-bottom:16px;">
				<li>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&status_filter=all' ) ); ?>" class="<?php echo ( 'all' === $current_status ) ? 'current' : ''; ?>">
						<?php printf( esc_html__( 'All (%d)', 'review-form-plugin' ), $count_all ); ?>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&status_filter=pending' ) ); ?>" class="<?php echo ( 'pending' === $current_status ) ? 'current' : ''; ?>">
						<?php printf( esc_html__( 'Pending (%d)', 'review-form-plugin' ), $count_pending ); ?>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&status_filter=publish' ) ); ?>" class="<?php echo ( 'publish' === $current_status ) ? 'current' : ''; ?>">
						<?php printf( esc_html__( 'Approved (%d)', 'review-form-plugin' ), $count_approved ); ?>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&status_filter=rejected' ) ); ?>" class="<?php echo ( 'rejected' === $current_status ) ? 'current' : ''; ?>">
						<?php printf( esc_html__( 'Rejected (%d)', 'review-form-plugin' ), $count_rejected ); ?>
					</a>
				</li>
			</ul>

			<!-- Reviews Moderation Table -->
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th style="width:180px;"><?php esc_html_e( 'Headline', 'review-form-plugin' ); ?></th>
						<th style="width:140px;"><?php esc_html_e( 'Submitter', 'review-form-plugin' ); ?></th>
						<th><?php esc_html_e( 'Review Text', 'review-form-plugin' ); ?></th>
						<th style="width:90px;"><?php esc_html_e( 'Attachment', 'review-form-plugin' ); ?></th>
						<th style="width:95px;"><?php esc_html_e( 'Status', 'review-form-plugin' ); ?></th>
						<th style="width:110px;"><?php esc_html_e( 'Date', 'review-form-plugin' ); ?></th>
						<th style="width:190px;"><?php esc_html_e( 'Actions', 'review-form-plugin' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $reviews ) ) : ?>
						<tr>
							<td colspan="7" style="text-align:center;padding:24px;color:#718096;">
								<?php esc_html_e( 'No review submissions found in this status filter.', 'review-form-plugin' ); ?>
							</td>
						</tr>
					<?php else : ?>
						<?php foreach ( $reviews as $rev ) :
							$sub_name   = get_post_meta( $rev->ID, '_rf_submitter_name', true );
							$sub_email  = get_post_meta( $rev->ID, '_rf_submitter_email', true );
							$attach_id  = (int) get_post_meta( $rev->ID, '_rf_attachment_id', true );
							$attach_url = get_post_meta( $rev->ID, '_rf_attachment_url', true );
							$status     = $rev->post_status;

							$approve_url = wp_nonce_url(
								admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&rf_action=approve&review_id=' . $rev->ID . '&status_filter=' . $current_status ),
								'rf_admin_moderation_action'
							);
							$reject_url = wp_nonce_url(
								admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&rf_action=reject&review_id=' . $rev->ID . '&status_filter=' . $current_status ),
								'rf_admin_moderation_action'
							);
							$delete_url = wp_nonce_url(
								admin_url( 'edit.php?post_type=' . RF_Custom_Post_Type::POST_TYPE . '&page=rf-moderation&rf_action=delete&review_id=' . $rev->ID . '&status_filter=' . $current_status ),
								'rf_admin_moderation_action'
							);
							?>
							<tr>
								<td>
									<strong><?php echo esc_html( $rev->post_title ); ?></strong>
								</td>
								<td>
									<div><?php echo esc_html( $sub_name ? $sub_name : '—' ); ?></div>
									<small style="color:#64748b;"><?php echo esc_html( antispambot( $sub_email ) ); ?></small>
								</td>
								<td>
									<p style="margin:0;font-size:13px;line-height:1.45;color:#334155;">
										<?php echo esc_html( $rev->post_content ); ?>
									</p>
								</td>
								<td>
									<?php if ( $attach_id ) : ?>
										<a href="<?php echo esc_url( wp_get_attachment_url( $attach_id ) ); ?>" target="_blank" rel="noopener">
											<?php echo wp_get_attachment_image( $attach_id, array( 50, 50 ), true, array( 'style' => 'border-radius:4px;object-fit:cover;' ) ); ?>
										</a>
									<?php elseif ( ! empty( $attach_url ) ) : ?>
										<a href="<?php echo esc_url( $attach_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View', 'review-form-plugin' ); ?></a>
									<?php else : ?>
										<span style="color:#a0aec0;">—</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ( 'publish' === $status ) : ?>
										<span class="rf-badge-approved" style="background:#e6f4ea;color:#137333;padding:4px 8px;border-radius:4px;font-weight:600;font-size:11px;display:inline-block;">Approved</span>
									<?php elseif ( 'rejected' === $status ) : ?>
										<span class="rf-badge-rejected" style="background:#fce8e6;color:#c5221f;padding:4px 8px;border-radius:4px;font-weight:600;font-size:11px;display:inline-block;">Rejected</span>
									<?php else : ?>
										<span class="rf-badge-pending" style="background:#fef7e0;color:#b06000;padding:4px 8px;border-radius:4px;font-weight:600;font-size:11px;display:inline-block;">Pending</span>
									<?php endif; ?>
								</td>
								<td>
									<small style="color:#64748b;"><?php echo esc_html( get_the_date( 'M j, Y', $rev->ID ) ); ?></small>
								</td>
								<td>
									<div style="display:flex;gap:6px;flex-wrap:wrap;">
										<?php if ( 'publish' !== $status ) : ?>
											<a href="<?php echo esc_url( $approve_url ); ?>" class="button button-small button-primary" style="background:#6fa68f;border-color:#5d9380;">
												<?php esc_html_e( 'Approve', 'review-form-plugin' ); ?>
											</a>
										<?php endif; ?>

										<?php if ( 'rejected' !== $status ) : ?>
											<a href="<?php echo esc_url( $reject_url ); ?>" class="button button-small">
												<?php esc_html_e( 'Reject', 'review-form-plugin' ); ?>
											</a>
										<?php endif; ?>

										<a href="<?php echo esc_url( $delete_url ); ?>" class="button button-small button-link-delete" onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to permanently delete this review?', 'review-form-plugin' ) ); ?>');">
											<?php esc_html_e( 'Delete', 'review-form-plugin' ); ?>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Get count of pending reviews.
	 */
	public static function get_pending_count() {
		$count = wp_count_posts( RF_Custom_Post_Type::POST_TYPE );
		return isset( $count->pending ) ? (int) $count->pending : 0;
	}
}
