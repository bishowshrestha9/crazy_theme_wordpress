<?php

namespace ProsCons\Platforms\Api\AliExpress;

defined( 'ABSPATH' ) || exit;

/**
 * AliExpress API HMAC-SHA256 signature generator
 *
 * @package ProsCons
 */
class AliExpressSignature {

	/**
	 * Sign parameters using HMAC-SHA256
	 *
	 * @param array  $params     Parameters to sign.
	 * @param string $app_secret App secret key.
	 * @return string Uppercase hex digest.
	 */
	public static function sign( array $params, string $app_secret ): string {
		ksort( $params );

		$concatenated = '';
		foreach ( $params as $key => $value ) {
			$concatenated .= $key . $value;
		}

		return strtoupper( hash_hmac( 'sha256', $concatenated, $app_secret ) );
	}

	/**
	 * Build full request parameters with system params and signature
	 *
	 * @param string $method      API method name.
	 * @param array  $params      Business parameters.
	 * @param string $app_key     App key.
	 * @param string $app_secret  App secret.
	 * @return array Full signed parameters.
	 */
	public static function build_request_params( string $method, array $params, string $app_key, string $app_secret ): array {
		$system_params = array(
			'app_key'     => $app_key,
			'method'      => $method,
			'sign_method' => 'sha256',
			'timestamp'   => gmdate( 'Y-m-d\TH:i:s\Z' ),
			'v'           => '2.0',
		);

		$all_params = array_merge( $system_params, $params );

		$all_params['sign'] = self::sign( $all_params, $app_secret );

		return $all_params;
	}
}
