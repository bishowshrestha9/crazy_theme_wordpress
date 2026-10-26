<?php
/**
 * Plugin Name: ACF CSV Exporter
 * Description: Export published posts and ACF fields (including product_name and product_image) to CSV using admin button or secret URL.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: acf-csv-exporter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACF_CSV_Exporter {

	const OPTION_SECRET = 'acf_csv_exporter_secret_key';

	public static function init() {
		$instance = new self();
		$instance->hooks();
	}

	public static function activate() {
		$secret = get_option( self::OPTION_SECRET, '' );
		if ( empty( $secret ) ) {
			update_option( self::OPTION_SECRET, wp_generate_password( 40, false, false ) );
		}
	}

	private function hooks() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_acf_csv_exporter_download', array( $this, 'handle_admin_export' ) );
		add_action( 'init', array( $this, 'handle_secret_url_export' ) );
	}

	public function register_admin_page() {
		add_management_page(
			__( 'ACF CSV Exporter', 'acf-csv-exporter' ),
			__( 'ACF CSV Exporter', 'acf-csv-exporter' ),
			'manage_options',
			'acf-csv-exporter',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$secret = $this->get_secret_key();
		$secret_url = add_query_arg(
			array(
				'acf_csv_export' => 1,
				'key'            => $secret,
			),
			home_url( '/' )
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'ACF CSV Exporter', 'acf-csv-exporter' ); ?></h1>
			<p><?php esc_html_e( 'Export published posts and all detected ACF fields as CSV.', 'acf-csv-exporter' ); ?></p>

			<div style="background:#fff;padding:20px;border-radius:8px;max-width:900px;">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="acf_csv_exporter_download">
					<?php wp_nonce_field( 'acf_csv_exporter_download_action', 'acf_csv_exporter_nonce' ); ?>
					<p class="description">
						<?php esc_html_e( 'This export is fixed to published Posts and only exports: Post ID, Post Title, Category, product_name, rating, price, product_image, product_category, effectiveness, buy_link, certification, return_policy, manufacturer, dosage, verdict, results.', 'acf-csv-exporter' ); ?>
					</p>

					<p>
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Export CSV', 'acf-csv-exporter' ); ?></button>
					</p>
				</form>
			</div>

			<div style="background:#fff;padding:20px;border-radius:8px;max-width:900px;margin-top:20px;">
				<h2><?php esc_html_e( 'Secret URL Trigger', 'acf-csv-exporter' ); ?></h2>
				<p><?php esc_html_e( 'Use this URL to export directly:', 'acf-csv-exporter' ); ?></p>
				<code style="display:block;background:#f0f0f1;padding:12px;border-radius:4px;word-break:break-all;">
					<?php echo esc_html( $secret_url ); ?>
				</code>
			</div>
		</div>
		<?php
	}

	public function handle_admin_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized request.', 'acf-csv-exporter' ), 403 );
		}

		check_admin_referer( 'acf_csv_exporter_download_action', 'acf_csv_exporter_nonce' );
		$this->stream_export_csv();
	}

	public function handle_secret_url_export() {
		if ( ! isset( $_GET['acf_csv_export'] ) ) {
			return;
		}

		$provided_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
		$stored_key = $this->get_secret_key();

		if ( ! hash_equals( $stored_key, $provided_key ) ) {
			wp_die( esc_html__( 'Invalid export key.', 'acf-csv-exporter' ), 403 );
		}
		$this->stream_export_csv();
	}

	private function get_secret_key() {
		$secret = get_option( self::OPTION_SECRET, '' );
		if ( empty( $secret ) ) {
			$secret = wp_generate_password( 40, false, false );
			update_option( self::OPTION_SECRET, $secret );
		}
		return (string) $secret;
	}

	private function stream_export_csv() {
		$post_type = 'post';
		@set_time_limit( 0 );
		ignore_user_abort( true );
		$batch_size = 200;

		$field_map = array(
			'product_name'     => 'product_name',
			'rating'           => 'rating',
			'price'            => 'price',
			'product_image'    => 'product_image',
			'product_category' => 'product_category',
			'effectiveness'    => 'effectiveness',
			'buy_link'         => 'buy_link',
			'certification'    => 'certification',
			'return_policy'    => 'return_policy',
			'manufacturer'     => 'manufacturer',
			'dosage'           => 'dosage',
			'verdict'          => 'verdict',
			'results'          => 'results',
		);

		$filename = 'acf-export-' . $post_type . '-' . gmdate( 'Ymd-His' ) . '.csv';
		while ( ob_get_level() > 0 ) {
			ob_end_clean();
		}
		nocache_headers();
		header( 'X-Robots-Tag: noindex, nofollow', true );
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: public' );
		header( 'Expires: 0' );

		$out = fopen( 'php://output', 'w' );
		if ( false === $out ) {
			wp_die( esc_html__( 'Unable to open output stream.', 'acf-csv-exporter' ) );
		}

		fwrite( $out, "\xEF\xBB\xBF" );
		fputcsv( $out, array_merge( array( 'Post ID', 'Post Title', 'Category' ), array_values( $field_map ) ) );

		$paged = 1;
		do {
			$data = new WP_Query(
				array(
					'post_type'              => $post_type,
					'post_status'            => 'publish',
					'fields'                 => 'ids',
					'posts_per_page'         => $batch_size,
					'paged'                  => $paged,
					'orderby'                => 'ID',
					'order'                  => 'ASC',
					'no_found_rows'          => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'cache_results'          => false,
				)
			);

			if ( empty( $data->posts ) ) {
				break;
			}

			foreach ( $data->posts as $post_id ) {
				$post_id = (int) $post_id;
				$row = array(
					$post_id,
					get_the_title( $post_id ),
					$this->get_post_categories_csv_value( $post_id ),
				);

				foreach ( array_keys( $field_map ) as $field_name ) {
					$value = null;
					if ( function_exists( 'get_field' ) ) {
						$value = get_field( $field_name, $post_id );
					}
					if ( null === $value || false === $value || '' === $value ) {
						$value = get_post_meta( $post_id, $field_name, true );
					}

					if ( 'product_image' === $field_name ) {
						$row[] = $this->normalize_image_to_url( $value );
					} elseif ( 'product_category' === $field_name ) {
						$row[] = $this->normalize_taxonomy_value( $value, 'product_category' );
					} else {
						$row[] = $this->flatten_value( $value );
					}
				}

				fputcsv( $out, $row );
			}

			$paged++;
			wp_cache_flush();
		} while ( $paged <= (int) $data->max_num_pages );

		wp_reset_postdata();
		fclose( $out );
		exit;
	}

	private function get_post_categories_csv_value( $post_id ) {
		$terms = get_the_terms( $post_id, 'category' );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}

		$names = array();
		foreach ( $terms as $term ) {
			if ( ! empty( $term->name ) ) {
				$names[] = $term->name;
			}
		}

		return implode( ', ', $names );
	}

	private function normalize_image_to_url( $value ) {
		if ( empty( $value ) ) {
			return '';
		}

		if ( is_array( $value ) ) {
			if ( isset( $value['url'] ) && ! empty( $value['url'] ) ) {
				return esc_url_raw( (string) $value['url'] );
			}
			if ( isset( $value['ID'] ) && ! empty( $value['ID'] ) ) {
				$url = wp_get_attachment_url( (int) $value['ID'] );
				return $url ? esc_url_raw( $url ) : '';
			}
			if ( isset( $value['id'] ) && ! empty( $value['id'] ) ) {
				$url = wp_get_attachment_url( (int) $value['id'] );
				return $url ? esc_url_raw( $url ) : '';
			}

			$urls = array();
			foreach ( $value as $item ) {
				$u = $this->normalize_image_to_url( $item );
				if ( ! empty( $u ) ) {
					$urls[] = $u;
				}
			}
			return implode( ' | ', array_unique( $urls ) );
		}

		if ( is_numeric( $value ) ) {
			$url = wp_get_attachment_url( (int) $value );
			return $url ? esc_url_raw( $url ) : '';
		}

		if ( is_string( $value ) ) {
			if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
				return esc_url_raw( $value );
			}
			if ( ctype_digit( $value ) ) {
				$url = wp_get_attachment_url( (int) $value );
				return $url ? esc_url_raw( $url ) : '';
			}
		}

		return '';
	}

	private function normalize_taxonomy_value( $value, $taxonomy ) {
		if ( empty( $value ) ) {
			return '';
		}

		$names = array();

		if ( is_object( $value ) ) {
			if ( isset( $value->name ) ) {
				$names[] = (string) $value->name;
			}
		} elseif ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( is_object( $item ) && isset( $item->name ) ) {
					$names[] = (string) $item->name;
				} elseif ( is_array( $item ) && isset( $item['name'] ) ) {
					$names[] = (string) $item['name'];
				} elseif ( is_numeric( $item ) ) {
					$term = get_term( (int) $item, $taxonomy );
					if ( $term && ! is_wp_error( $term ) && ! empty( $term->name ) ) {
						$names[] = (string) $term->name;
					}
				} elseif ( is_string( $item ) && '' !== trim( $item ) ) {
					$names[] = trim( $item );
				}
			}
		} elseif ( is_numeric( $value ) ) {
			$term = get_term( (int) $value, $taxonomy );
			if ( $term && ! is_wp_error( $term ) && ! empty( $term->name ) ) {
				$names[] = (string) $term->name;
			}
		} elseif ( is_string( $value ) ) {
			$names[] = trim( $value );
		}

		$names = array_values( array_unique( array_filter( $names ) ) );
		return implode( ', ', $names );
	}

	private function flatten_value( $value ) {
		if ( is_array( $value ) || is_object( $value ) ) {
			$json = wp_json_encode( $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
			return false !== $json ? $json : '';
		}
		if ( is_bool( $value ) ) {
			return $value ? '1' : '0';
		}
		if ( null === $value ) {
			return '';
		}
		return (string) $value;
	}
}

register_activation_hook( __FILE__, array( 'ACF_CSV_Exporter', 'activate' ) );
add_action( 'plugins_loaded', array( 'ACF_CSV_Exporter', 'init' ) );
