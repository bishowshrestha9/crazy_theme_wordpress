<?php

namespace ProsCons;

use ProsCons\Blocks\ButtonBlock;
use ProsCons\Blocks\CtaBlock;
use ProsCons\Blocks\NoticeBlock;
use ProsCons\Blocks\ProductComparisonBlock;
use ProsCons\Blocks\ProductTableBlock;
use ProsCons\Blocks\ProsAndConsBlock;
use ProsCons\Blocks\SingleProductBlock;
use ProsCons\Blocks\SpecificationsBlock;
use ProsCons\Blocks\VerdictBlock;
use ProsCons\Blocks\VersusLineBlock;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package ProsCons
 */
class ProsConsBlocks {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	public function init() {
		$blocks = array(
			'ButtonBlock'            => ButtonBlock::class,
			'CtaBlock'               => CtaBlock::class,
			'NoticeBlock'            => NoticeBlock::class,
			'ProductComparisonBlock' => ProductComparisonBlock::class,
			'ProductTableBlock'      => ProductTableBlock::class,
			'ProsAndConsBlock'       => ProsAndConsBlock::class,
			'SingleProductBlock'     => SingleProductBlock::class,
			'SpecificationsBlock'    => SpecificationsBlock::class,
			'VerdictBlock'           => VerdictBlock::class,
			'VersusLineBlock'        => VersusLineBlock::class,
		);

		foreach ( $blocks as $class ) {
			new $class();
		}
	}
}
