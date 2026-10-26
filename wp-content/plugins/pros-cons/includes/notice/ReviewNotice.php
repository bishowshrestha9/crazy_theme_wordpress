<?php
namespace ProsCons\Notice;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons_Review_Notice Class
 *
 * This class is extended from NoticeBase to display review notice to admin
 *
 * @package ProsCons
 */
class ReviewNotice extends NoticeBase {

	public function get_name(): string {
		return 'review';
	}

	public function get_title(): string {
		return __( 'Help Us Grow', 'prosandcons' );
	}

	public function get_description(): string {
		return sprintf(
			'<p>%s</p>',
			__(
				'We noticed you’ve been using ProsCons for a while,
                and we hope it’s making your life easier! Could you do us a
                <strong>huge favor</strong> and leave us a 5-star rating on WordPress?
                It would mean the world to us and help us reach even more users!',
				'prosandcons'
			)
		);
	}

	public function get_option_buttons(): array {
		return array(
			array(
				'title'      => __( 'Ok, you deserve it', 'prosandcons' ),
				'attributes' => array(
					'href'   => esc_url( 'https://wordpress.org/support/plugin/prosandcons/reviews/#new-post' ),
					'class'  => 'affx-notice__button',
					'target' => '_blank',
				),
			),
			array(
				'title'      => __( 'I already did', 'prosandcons' ),
				'attributes' => array(
					'class' => 'affx-notice__link affx-notice--dismiss',
				),
			),
			array(
				'title'      => __( 'No, not good enough', 'prosandcons' ),
				'attributes' => array(
					'class' => 'affx-notice__link affx-notice--dismiss',
				),
			),
		);
	}

	/**
	 * Check if notice is applicable
	 *
	 * Apply logic: it'll be displayed after 30 days
	 *
	 * @return boolean
	 */
	public function is_applicable(): bool {
		$installed_at = get_option( 'prosandcons_notice_initiated', 0 );

		return $installed_at < strtotime( '-30 days', time() );
	}
}
