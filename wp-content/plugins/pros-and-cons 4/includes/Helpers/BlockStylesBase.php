<?php
namespace ProsAndCons\Helpers;

defined( 'ABSPATH' ) || exit;

abstract class BlockStylesBase {

	abstract protected static function css_id_prefix(): string;

	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {}

	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {}

	public static function block_css( $attr, $id ) {
		$buckets = array(
			'desktop' => static::get_selectors( $attr ),
			'tablet'  => static::get_tabletselectors( $attr ),
			'mobile'  => static::get_mobileselectors( $attr ),
		);

		static::apply_promoted_selectors( $buckets, $attr );
		static::apply_hover_selectors( $buckets, $attr );

		$prefix   = static::css_id_prefix();
		$clean_id = Helpers::sanitize_block_id( (string) $id );

		return array(
			'desktop' => Helpers::generate_css( $buckets['desktop'], $prefix . $clean_id ),
			'tablet'  => Helpers::generate_css( $buckets['tablet'], $prefix . $clean_id ),
			'mobile'  => Helpers::generate_css( $buckets['mobile'], $prefix . $clean_id ),
		);
	}
}
