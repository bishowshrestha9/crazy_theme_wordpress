<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Shared currency formatting utilities
 *
 * @package ProsCons
 */
class CurrencyHelper {

	/**
	 * Currency code to symbol mapping
	 *
	 * @var array
	 */
	private static $symbols = array(
		'USD' => '$',
		'EUR' => "\u{20AC}",
		'GBP' => "\u{00A3}",
		'CAD' => 'C$',
		'AUD' => 'A$',
		'JPY' => "\u{00A5}",
		'CNY' => "\u{00A5}",
		'CHF' => 'CHF ',
		'INR' => "\u{20B9}",
		'MXN' => 'MX$',
		'BRL' => 'R$',
		'PLN' => "z\u{0142}",
		'SEK' => 'kr',
		'NOK' => 'kr',
		'DKK' => 'kr',
		'SGD' => 'S$',
		'NZD' => 'NZ$',
		'HKD' => 'HK$',
		'CZK' => "K\u{010D}",
		'HUF' => 'Ft',
		'ILS' => "\u{20AA}",
		'TRY' => "\u{20BA}",
		'TWD' => 'NT$',
		'THB' => "\u{0E3F}",
		'PHP' => "\u{20B1}",
		'MYR' => 'RM',
		'IDR' => 'Rp',
		'VND' => "\u{20AB}",
		'ZAR' => 'R',
	);

	/**
	 * Get currency symbol for a currency code
	 *
	 * @param string $currency_code ISO 4217 currency code.
	 * @return string Currency symbol or code with trailing space.
	 */
	public static function get_symbol( string $currency_code ): string {
		return self::$symbols[ $currency_code ] ?? $currency_code . ' ';
	}

	/**
	 * Format a price amount with currency symbol
	 *
	 * @param float  $amount        Price amount.
	 * @param string $currency_code ISO 4217 currency code.
	 * @return string Formatted price string.
	 */
	public static function format_price( float $amount, string $currency_code ): string {
		$symbol = self::get_symbol( $currency_code );

		if ( 'JPY' === $currency_code ) {
			return $symbol . number_format( $amount, 0 );
		}

		return $symbol . number_format( $amount, 2 );
	}
}
