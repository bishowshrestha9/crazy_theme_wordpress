<?php
/**
 * Safora HTTP Error Descriptions & Diagnostics
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Http_Error_Descriptions {

	/**
	 * Return user-friendly description for HTTP status codes
	 *
	 * @param int $code
	 * @return string
	 */
	public static function get_description($code) {
		switch ((int) $code) {
			case 400: return 'Bad Request: The storage server could not understand the request.';
			case 401: return 'Unauthorized: Check that your access keys, tokens, or credentials are correct.';
			case 403: return 'Forbidden: Your credentials do not have permission to access or write to this bucket/folder.';
			case 404: return 'Not Found: The specified storage bucket or folder could not be found.';
			case 408: return 'Request Timeout: The connection to the storage provider timed out.';
			case 409: return 'Conflict: The file is locked or a conflicting upload is in progress.';
			case 413: return 'Payload Too Large: The upload archive exceeds the single-file size limit of this provider.';
			case 429: return 'Too Many Requests: Rate limit exceeded by storage provider. Retrying later.';
			case 500: return 'Internal Server Error: The storage provider reported an internal error.';
			case 502: return 'Bad Gateway: Storage provider gateway/proxy error.';
			case 503: return 'Service Unavailable: Storage provider is temporarily down or overloaded.';
			case 504: return 'Gateway Timeout: Connection to remote storage server timed out.';
			default:  return 'HTTP Error ' . $code;
		}
	}
}
