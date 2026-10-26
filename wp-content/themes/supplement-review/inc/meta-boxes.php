<?php
/**
 * Custom Meta Boxes for Supplement Reviews (No ACF dependency)
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post meta keys for reviews with schema & sanitization callbacks.
 */
function sr_register_post_meta() {
	$meta_keys = array(
		'_sr_product_name' => array(
			'type'              => 'string',
			'description'       => 'Product Name',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_product_tags' => array(
			'type'              => 'string',
			'description'       => 'Product Tagline / Categories (e.g. Blood Sugar • Liquid Drops)',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_product_image_id' => array(
			'type'              => 'integer',
			'description'       => 'Product Image Attachment ID',
			'sanitize_callback' => 'absint',
		),
		'_sr_product_image_url' => array(
			'type'              => 'string',
			'description'       => 'Product Image URL fallback',
			'sanitize_callback' => 'esc_url_raw',
		),
		'_sr_product_rating' => array(
			'type'              => 'number',
			'description'       => 'Editorial Star Rating (0 - 5.0)',
			'sanitize_callback' => function( $value ) {
				$val = floatval( $value );
				return max( 0, min( 5, $val ) );
			},
		),
		'_sr_product_price' => array(
			'type'              => 'string',
			'description'       => 'Current Discounted Price (e.g. 12.99)',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_product_old_price' => array(
			'type'              => 'string',
			'description'       => 'Original Regular Price (e.g. 17.99)',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_affiliate_url' => array(
			'type'              => 'string',
			'description'       => 'Official Merchant Affiliate URL',
			'sanitize_callback' => 'esc_url_raw',
		),
		'_sr_affiliate_btn_text' => array(
			'type'              => 'string',
			'description'       => 'Affiliate CTA Button Text',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_verdict_text' => array(
			'type'              => 'string',
			'description'       => 'Editorial Verdict Summary Text',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'_sr_reviewed_by' => array(
			'type'              => 'string',
			'description'       => 'Reviewed By Expert Name',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_author_role' => array(
			'type'              => 'string',
			'description'       => 'Author Medical Credential / Role',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_toc_groups' => array(
			'type'              => 'string',
			'description'       => 'TOC Group Labels (comma-separated)',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_sr_is_featured' => array(
			'type'              => 'boolean',
			'description'       => 'Feature on Homepage Hero',
			'sanitize_callback' => 'rest_sanitize_boolean',
		),
	);

	foreach ( $meta_keys as $key => $args ) {
		register_post_meta(
			'post',
			$key,
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => $args['type'],
				'description'       => $args['description'],
				'sanitize_callback' => $args['sanitize_callback'],
				'auth_callback'     => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'sr_register_post_meta' );

/**
 * Add Review Meta Box to Post edit screen.
 */
function sr_add_review_meta_boxes() {
	add_meta_box(
		'sr_review_product_meta',
		__( 'Supplement Review & Product Card Settings', 'supplement-review' ),
		'sr_render_review_meta_box',
		'post',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sr_add_review_meta_boxes' );

/**
 * Render the Review Meta Box HTML.
 *
 * @param WP_Post $post Current post object.
 */
function sr_render_review_meta_box( $post ) {
	wp_nonce_field( 'sr_save_meta_box_data', 'sr_meta_box_nonce' );

	$product_name    = get_post_meta( $post->ID, '_sr_product_name', true );
	$product_tags    = get_post_meta( $post->ID, '_sr_product_tags', true );
	$image_id        = get_post_meta( $post->ID, '_sr_product_image_id', true );
	$image_url       = get_post_meta( $post->ID, '_sr_product_image_url', true );
	$rating          = get_post_meta( $post->ID, '_sr_product_rating', true );
	$price           = get_post_meta( $post->ID, '_sr_product_price', true );
	$old_price       = get_post_meta( $post->ID, '_sr_product_old_price', true );
	$affiliate_url   = get_post_meta( $post->ID, '_sr_affiliate_url', true );
	$btn_text        = get_post_meta( $post->ID, '_sr_affiliate_btn_text', true );
	$verdict_text    = get_post_meta( $post->ID, '_sr_verdict_text', true );
	$reviewed_by     = get_post_meta( $post->ID, '_sr_reviewed_by', true );
	$author_role     = get_post_meta( $post->ID, '_sr_author_role', true );
	$toc_groups      = get_post_meta( $post->ID, '_sr_toc_groups', true );
	$is_featured     = get_post_meta( $post->ID, '_sr_is_featured', true );
	$scores_json     = get_post_meta( $post->ID, '_sr_editorial_scores', true );

	// Default fallback scores if not yet set
	$editorial_scores = array();
	if ( ! empty( $scores_json ) ) {
		$decoded = json_decode( $scores_json, true );
		if ( is_array( $decoded ) ) {
			$editorial_scores = $decoded;
		}
	}
	if ( empty( $editorial_scores ) ) {
		$editorial_scores = array(
			array( 'label' => 'Effectiveness', 'score' => '4.9' ),
			array( 'label' => 'Ingredients', 'score' => '4.9' ),
			array( 'label' => 'Customer Reviews', 'score' => '4.8' ),
		);
	}

	$preview_img = '';
	if ( $image_id ) {
		$preview_img = wp_get_attachment_image_url( $image_id, 'medium' );
	} elseif ( $image_url ) {
		$preview_img = $image_url;
	} else {
		$preview_img = get_template_directory_uri() . '/assets/images/product-sample.png';
	}
	?>
	<style>
		.sr-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
		.sr-meta-col-full { grid-column: 1 / -1; }
		.sr-meta-field { margin-bottom: 15px; }
		.sr-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
		.sr-meta-field input[type="text"],
		.sr-meta-field input[type="number"],
		.sr-meta-field input[type="url"],
		.sr-meta-field textarea { width: 100%; max-width: 100%; }
		.sr-meta-image-preview { width: 120px; height: 120px; border: 1px dashed #ccd0d4; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f6f7f7; margin-bottom: 10px; }
		.sr-meta-image-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
		.sr-score-row-item { display: flex; gap: 10px; align-items: center; margin-bottom: 8px; }
	</style>

	<div class="sr-meta-wrapper">
		<div class="sr-meta-grid">
			<!-- Product Name -->
			<div class="sr-meta-field">
				<label for="sr_product_name"><?php esc_html_e( 'Product Name', 'supplement-review' ); ?></label>
				<input type="text" id="sr_product_name" name="_sr_product_name" value="<?php echo esc_attr( $product_name ); ?>" placeholder="<?php esc_attr_e( 'e.g. Gluco Reset', 'supplement-review' ); ?>" />
			</div>

			<!-- Product Tags / Category -->
			<div class="sr-meta-field">
				<label for="sr_product_tags"><?php esc_html_e( 'Product Tagline / Category Tags', 'supplement-review' ); ?></label>
				<input type="text" id="sr_product_tags" name="_sr_product_tags" value="<?php echo esc_attr( $product_tags ); ?>" placeholder="<?php esc_attr_e( 'e.g. Blood Sugar • Liquid Drops', 'supplement-review' ); ?>" />
			</div>

			<!-- Product Image Upload -->
			<div class="sr-meta-field">
				<label><?php esc_html_e( 'Product Bottle / Box Image', 'supplement-review' ); ?></label>
				<div class="sr-meta-image-preview" id="sr_product_image_preview">
					<?php if ( ! empty( $preview_img ) ) : ?>
						<img src="<?php echo esc_url( $preview_img ); ?>" alt="Product preview" />
					<?php else : ?>
						<span style="color:#8c8f94;"><?php esc_html_e( 'No image', 'supplement-review' ); ?></span>
					<?php endif; ?>
				</div>
				<input type="hidden" id="sr_product_image_id" name="_sr_product_image_id" value="<?php echo esc_attr( $image_id ); ?>" />
				<button type="button" class="button" id="sr_upload_image_btn"><?php esc_html_e( 'Select Product Image', 'supplement-review' ); ?></button>
				<button type="button" class="button" id="sr_remove_image_btn" <?php echo empty( $image_id ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove', 'supplement-review' ); ?></button>
			</div>

			<!-- Rating -->
			<div class="sr-meta-field">
				<label for="sr_product_rating"><?php esc_html_e( 'Editorial Overall Rating (0 to 5.0)', 'supplement-review' ); ?></label>
				<input type="number" step="0.1" min="0" max="5" id="sr_product_rating" name="_sr_product_rating" value="<?php echo esc_attr( $rating ? $rating : '4.9' ); ?>" />
			</div>

			<!-- Current Price -->
			<div class="sr-meta-field">
				<label for="sr_product_price"><?php esc_html_e( 'Current Price ($)', 'supplement-review' ); ?></label>
				<input type="text" id="sr_product_price" name="_sr_product_price" value="<?php echo esc_attr( $price ? $price : '12.99' ); ?>" placeholder="12.99" />
			</div>

			<!-- Old Price -->
			<div class="sr-meta-field">
				<label for="sr_product_old_price"><?php esc_html_e( 'Old / Regular Price ($)', 'supplement-review' ); ?></label>
				<input type="text" id="sr_product_old_price" name="_sr_product_old_price" value="<?php echo esc_attr( $old_price ? $old_price : '17.99' ); ?>" placeholder="17.99" />
				<small class="description"><?php esc_html_e( 'Discount percentage is automatically calculated from these two prices.', 'supplement-review' ); ?></small>
			</div>

			<!-- Affiliate URL -->
			<div class="sr-meta-field sr-meta-col-full">
				<label for="sr_affiliate_url"><?php esc_html_e( 'Merchant Affiliate / Official Buy URL', 'supplement-review' ); ?></label>
				<input type="url" id="sr_affiliate_url" name="_sr_affiliate_url" value="<?php echo esc_attr( $affiliate_url ); ?>" placeholder="https://..." />
			</div>

			<!-- Button Text -->
			<div class="sr-meta-field">
				<label for="sr_affiliate_btn_text"><?php esc_html_e( 'Buy Button Text', 'supplement-review' ); ?></label>
				<input type="text" id="sr_affiliate_btn_text" name="_sr_affiliate_btn_text" value="<?php echo esc_attr( ! empty( $btn_text ) ? $btn_text : 'Buy Now (Official Website)' ); ?>" />
			</div>

			<!-- Reviewed By -->
			<div class="sr-meta-field">
				<label for="sr_reviewed_by"><?php esc_html_e( 'Reviewed By (Expert Name)', 'supplement-review' ); ?></label>
				<input type="text" id="sr_reviewed_by" name="_sr_reviewed_by" value="<?php echo esc_attr( ! empty( $reviewed_by ) ? $reviewed_by : 'Dr. Emma Myers' ); ?>" />
			</div>

			<!-- Author Role -->
			<div class="sr-meta-field">
				<label for="sr_author_role"><?php esc_html_e( 'Author Credential Line', 'supplement-review' ); ?></label>
				<input type="text" id="sr_author_role" name="_sr_author_role" value="<?php echo esc_attr( ! empty( $author_role ) ? $author_role : 'Medical Researcher & Contributor' ); ?>" />
			</div>

			<!-- TOC Group Labels -->
			<div class="sr-meta-field">
				<label for="sr_toc_groups"><?php esc_html_e( 'Table of Contents 3 Column Groups', 'supplement-review' ); ?></label>
				<input type="text" id="sr_toc_groups" name="_sr_toc_groups" value="<?php echo esc_attr( $toc_groups ); ?>" placeholder="<?php esc_attr_e( 'e.g. Column 1, Column 2, Column 3', 'supplement-review' ); ?>" />
				<small class="description"><?php esc_html_e( 'Optional 3 comma-separated column names. Leave empty for no column headers.', 'supplement-review' ); ?></small>
			</div>

			<!-- Verdict Text -->
			<div class="sr-meta-field sr-meta-col-full">
				<label for="sr_verdict_text"><?php esc_html_e( 'Quick Editorial Verdict', 'supplement-review' ); ?></label>
				<textarea id="sr_verdict_text" name="_sr_verdict_text" rows="3"><?php echo esc_textarea( ! empty( $verdict_text ) ? $verdict_text : 'Legit, convenient option for adults seeking daily glucose support' ); ?></textarea>
			</div>

			<!-- Editorial Score Rows (Repeatable) -->
			<div class="sr-meta-field sr-meta-col-full">
				<label><?php esc_html_e( 'Editorial Score Bars (e.g. Effectiveness, Ingredients)', 'supplement-review' ); ?></label>
				<div id="sr_scores_container">
					<?php foreach ( $editorial_scores as $index => $item ) : ?>
						<div class="sr-score-row-item">
							<input type="text" name="sr_scores_label[]" value="<?php echo esc_attr( isset( $item['label'] ) ? $item['label'] : '' ); ?>" placeholder="<?php esc_attr_e( 'Category Name', 'supplement-review' ); ?>" style="flex:2;" />
							<input type="number" step="0.1" min="0" max="5" name="sr_scores_val[]" value="<?php echo esc_attr( isset( $item['score'] ) ? $item['score'] : '4.9' ); ?>" placeholder="4.9" style="flex:1;" />
							<button type="button" class="button sr-remove-score-btn">&times;</button>
						</div>
					<?php endforeach; ?>
				</div>
				<button type="button" class="button" id="sr_add_score_btn"><?php esc_html_e( '+ Add Score Row', 'supplement-review' ); ?></button>
			</div>

			<!-- Featured Switch -->
			<div class="sr-meta-field sr-meta-col-full">
				<label>
					<input type="checkbox" name="_sr_is_featured" value="1" <?php checked( $is_featured, 1 ); ?> />
					<?php esc_html_e( 'Feature this review in the Homepage Hero section', 'supplement-review' ); ?>
				</label>
			</div>
		</div>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Media Uploader
		var uploadBtn = document.getElementById('sr_upload_image_btn');
		var removeBtn = document.getElementById('sr_remove_image_btn');
		var imageIdInput = document.getElementById('sr_product_image_id');
		var previewContainer = document.getElementById('sr_product_image_preview');

		if (uploadBtn && typeof wp !== 'undefined' && wp.media) {
			var frame;
			uploadBtn.addEventListener('click', function(e) {
				e.preventDefault();
				if (frame) { frame.open(); return; }
				frame = wp.media({
					title: '<?php echo esc_js( __( 'Select Product Image', 'supplement-review' ) ); ?>',
					button: { text: '<?php echo esc_js( __( 'Use this image', 'supplement-review' ) ); ?>' },
					multiple: false
				});
				frame.on('select', function() {
					var attachment = frame.state().get('selection').first().toJSON();
					imageIdInput.value = attachment.id;
					previewContainer.innerHTML = '<img src="' + (attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url) + '" alt="" />';
					removeBtn.style.display = 'inline-block';
				});
				frame.open();
			});

			removeBtn.addEventListener('click', function(e) {
				e.preventDefault();
				imageIdInput.value = '';
				previewContainer.innerHTML = '<span style="color:#8c8f94;"><?php echo esc_js( __( 'No image', 'supplement-review' ) ); ?></span>';
				removeBtn.style.display = 'none';
			});
		}

		// Repeatable score rows
		var container = document.getElementById('sr_scores_container');
		var addBtn = document.getElementById('sr_add_score_btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function(e) {
				e.preventDefault();
				var div = document.createElement('div');
				div.className = 'sr-score-row-item';
				div.innerHTML = '<input type="text" name="sr_scores_label[]" placeholder="<?php echo esc_js( __( 'Category Name', 'supplement-review' ) ); ?>" style="flex:2;" />' +
					'<input type="number" step="0.1" min="0" max="5" name="sr_scores_val[]" value="4.9" placeholder="4.9" style="flex:1;" />' +
					'<button type="button" class="button sr-remove-score-btn">&times;</button>';
				container.appendChild(div);
				attachRemoveHandler(div.querySelector('.sr-remove-score-btn'));
			});

			function attachRemoveHandler(btn) {
				btn.addEventListener('click', function() {
					var row = this.closest('.sr-score-row-item');
					if (row) { row.remove(); }
				});
			}

			container.querySelectorAll('.sr-remove-score-btn').forEach(function(btn) {
				attachRemoveHandler(btn);
			});
		}
	});
	</script>
	<?php
}

/**
 * Save meta box values securely.
 *
 * @param int $post_id Post ID.
 */
function sr_save_review_meta_box_data( $post_id ) {
	// Security Checks
	if ( ! isset( $_POST['sr_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sr_meta_box_nonce'] ) ), 'sr_save_meta_box_data' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Text fields
	$text_fields = array(
		'_sr_product_name',
		'_sr_product_tags',
		'_sr_product_price',
		'_sr_product_old_price',
		'_sr_affiliate_btn_text',
		'_sr_reviewed_by',
		'_sr_author_role',
		'_sr_toc_groups',
	);

	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	// URL fields
	if ( isset( $_POST['_sr_affiliate_url'] ) ) {
		update_post_meta( $post_id, '_sr_affiliate_url', esc_url_raw( wp_unslash( $_POST['_sr_affiliate_url'] ) ) );
	}

	// Image ID
	if ( isset( $_POST['_sr_product_image_id'] ) ) {
		update_post_meta( $post_id, '_sr_product_image_id', absint( $_POST['_sr_product_image_id'] ) );
	}

	// Rating (0 to 5)
	if ( isset( $_POST['_sr_product_rating'] ) ) {
		$rating = floatval( $_POST['_sr_product_rating'] );
		$rating = max( 0, min( 5, $rating ) );
		update_post_meta( $post_id, '_sr_product_rating', $rating );
	}

	// Verdict text
	if ( isset( $_POST['_sr_verdict_text'] ) ) {
		update_post_meta( $post_id, '_sr_verdict_text', sanitize_textarea_field( wp_unslash( $_POST['_sr_verdict_text'] ) ) );
	}

	// Featured switch
	$featured = isset( $_POST['_sr_is_featured'] ) ? 1 : 0;
	update_post_meta( $post_id, '_sr_is_featured', $featured );

	// Repeatable editorial scores
	if ( isset( $_POST['sr_scores_label'] ) && is_array( $_POST['sr_scores_label'] ) ) {
		$labels = array_map( 'sanitize_text_field', wp_unslash( $_POST['sr_scores_label'] ) );
		$values = isset( $_POST['sr_scores_val'] ) && is_array( $_POST['sr_scores_val'] ) ? array_map( 'floatval', $_POST['sr_scores_val'] ) : array();

		$scores = array();
		foreach ( $labels as $idx => $label ) {
			if ( ! empty( $label ) ) {
				$score_val = isset( $values[ $idx ] ) ? max( 0, min( 5, $values[ $idx ] ) ) : 4.9;
				$scores[]  = array(
					'label' => $label,
					'score' => number_format( $score_val, 1 ),
				);
			}
		}

		update_post_meta( $post_id, '_sr_editorial_scores', wp_json_encode( $scores ) );
	}
}
add_action( 'save_post', 'sr_save_review_meta_box_data' );
