<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Forwarding Tester Class
 *
 * Interactive simulator allowing administrators to test Buy Now button HTML URL transformations
 * without executing an actual HTTP redirect or changing post content in database.
 */
class ForwardingTester {

	public function init(): void {
		add_action( 'wp_ajax_affiliate_jt_simulate_forwarding', array( $this, 'ajax_simulate_forwarding' ) );
	}

	/**
	 * AJAX Handler to simulate HTML button forwarding pipeline.
	 */
	public function ajax_simulate_forwarding(): void {
		check_ajax_referer( 'affiliate_jt_debug_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-journey-tracker' ) ) );
		}

		$input_url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		$cookie_override = isset( $_POST['cookie_slug'] ) ? sanitize_title( wp_unslash( $_POST['cookie_slug'] ) ) : '';

		if ( empty( $input_url ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid page URL to simulate.', 'affiliate-journey-tracker' ) ) );
		}

		$simulation = $this->run_simulation( $input_url, $cookie_override );

		wp_send_json_success( $simulation );
	}

	/**
	 * Run simulation pipeline step-by-step.
	 *
	 * @param string $page_url        Target page URL.
	 * @param string $cookie_override Optional cookie override slug.
	 * @return array Step-by-step simulation breakdown.
	 */
	public function run_simulation( string $page_url, string $cookie_override = '' ): array {
		$start_time = microtime( true );

		// Step 1: Resolve Journey Cookie
		$cookie_data  = Cookie_Manager::get_cookie_data();
		$journey_slug = ! empty( $cookie_override ) ? $cookie_override : ( ! empty( $cookie_data['listing_slug'] ) ? $cookie_data['listing_slug'] : 'best-smart-rings' );
		$cookie_json  = ! empty( $cookie_override ) ? wp_json_encode( array( 'listing_slug' => $cookie_override, 'source' => 'manual_override' ) ) : ( ! empty( $cookie_data ) ? wp_json_encode( $cookie_data ) : 'None (Using default)' );

		// Step 2: Determine Pretty Link Target URL
		$parsed_req = wp_parse_url( $page_url );
		$req_path   = isset( $parsed_req['path'] ) ? trim( $parsed_req['path'], '/' ) : 'flexigen';
		$site_a_domains = get_option( 'affiliate_jt_site_a_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
		$first_domain   = trim( explode( "\n", str_replace( "\r", "", $site_a_domains ) )[0] );
		if ( empty( $first_domain ) ) {
			$first_domain = 'thebuyersreviews.com';
		}

		$original_href = 'https://' . rtrim( $first_domain, '/' ) . '/' . ( ! empty( $req_path ) ? $req_path : 'flexigen' );

		// Step 3: Run BuyButtonForwarder checks
		$forwarder = new BuyButtonForwarder();
		$qualifies = $forwarder->is_qualifying_pretty_link( $original_href );
		$modified_href = $forwarder->append_origin_to_href( $original_href, $journey_slug );

		$exec_time = round( ( microtime( true ) - $start_time ) * 1000, 2 ) . ' ms';

		return array(
			'page_url'            => $page_url,
			'original_href'       => $original_href,
			'qualifies'           => $qualifies,
			'cookie_json'         => $cookie_json,
			'journey_slug'        => $journey_slug,
			'modified_href'       => $modified_href,
			'exec_time'           => $exec_time,
			'status'              => $qualifies ? 'Simulation Successful (Buy Button Modified)' : 'Skipped - Does Not Qualify as Site A Pretty Link',
		);
	}

	/**
	 * Render Forwarding Tester Admin Page.
	 */
	public function render_tester_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce      = wp_create_nonce( 'affiliate_jt_debug_nonce' );
		$sample_req = home_url( '/flexigen' );
		$sample_sim = $this->run_simulation( $sample_req, 'best-smart-rings' );

		?>
		<div class="wrap affiliate-jt-debug-wrap">
			<h1><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e( 'Buy Now Button Forwarding Simulator & Tester', 'affiliate-journey-tracker' ); ?></h1>
			<p><?php esc_html_e( 'Simulate rendered HTML page processing (Cookie Lookup -> Anchor Href Detection -> Site A Pretty Link Validation -> Query Parameter Appending) without modifying database records.', 'affiliate-journey-tracker' ); ?></p>

			<div class="affiliate-jt-card full-width">
				<h2><span class="dashicons dashicons-controls-play"></span> <?php esc_html_e( 'Interactive HTML Forwarding Pipeline Tester', 'affiliate-journey-tracker' ); ?></h2>
				<div class="url-tester-box">
					<div class="field-row">
						<label for="jt-sim-url-input"><strong><?php esc_html_e( 'Individual Review Page URL:', 'affiliate-journey-tracker' ); ?></strong></label>
						<input type="url" id="jt-sim-url-input" class="large-text" value="<?php echo esc_url( $sample_req ); ?>" placeholder="https://thebuyersreviews.com/flexigen" />
					</div>
					<div class="field-row" style="margin-top:12px;">
						<label for="jt-sim-cookie-input"><strong><?php esc_html_e( 'Override Journey Cookie Slug (Optional):', 'affiliate-journey-tracker' ); ?></strong></label>
						<input type="text" id="jt-sim-cookie-input" class="regular-text" value="best-smart-rings" placeholder="best-smart-rings" />
						<button type="button" id="jt-run-forwarding-sim" class="button button-primary" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Run Simulation', 'affiliate-journey-tracker' ); ?></button>
					</div>

					<div id="jt-sim-result-panel" class="url-result-panel" style="margin-top:18px;">
						<table class="widefat fixed striped">
							<tbody>
								<tr>
									<th style="width:230px;"><?php esc_html_e( '1. Inspected Page URL', 'affiliate-journey-tracker' ); ?></th>
									<td><code id="sim-res-req-url"><?php echo esc_html( $sample_sim['page_url'] ); ?></code></td>
								</tr>
								<tr>
									<th><?php esc_html_e( '2. Detected Original Buy Href', 'affiliate-journey-tracker' ); ?></th>
									<td><code id="sim-res-orig-dest"><?php echo esc_html( $sample_sim['original_href'] ); ?></code></td>
								</tr>
								<tr>
									<th><?php esc_html_e( '3. Pretty Link & Host Validation', 'affiliate-journey-tracker' ); ?></th>
									<td>
										<span class="badge badge-success" id="sim-res-allowed"><?php esc_html_e( 'Valid Site A Pretty Link', 'affiliate-journey-tracker' ); ?></span>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( '4. Journey Cookie Lookup', 'affiliate-journey-tracker' ); ?></th>
									<td>
										<code><span id="sim-res-cookie-json"><?php echo esc_html( $sample_sim['cookie_json'] ); ?></span></code>
										<br><small>Extracted Origin Slug: <strong class="origin-highlight" id="sim-res-journey-slug"><?php echo esc_html( $sample_sim['journey_slug'] ); ?></strong></small>
									</td>
								</tr>
								<tr>
									<th><?php esc_html_e( '5. Rendered HTML Modified Href', 'affiliate-journey-tracker' ); ?></th>
									<td><code id="sim-res-mod-dest" class="result-url"><?php echo esc_html( $sample_sim['modified_href'] ); ?></code></td>
								</tr>
								<tr>
									<th><?php esc_html_e( '6. Simulation Status & Timing', 'affiliate-journey-tracker' ); ?></th>
									<td>
										<span class="badge badge-success" id="sim-res-status"><?php echo esc_html( $sample_sim['status'] ); ?></span>
										(Execution time: <code id="sim-res-time"><?php echo esc_html( $sample_sim['exec_time'] ); ?></code>)
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
