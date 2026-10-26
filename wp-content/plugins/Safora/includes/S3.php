<?php
/**
 * Safora S3 Client (Pure PHP AWS S3 & S3-Compatible Storage Engine)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_S3 {

	private $access_key = '';
	private $secret_key = '';
	private $endpoint = 's3.amazonaws.com';
	private $use_ssl = true;
	private $use_v4 = true;
	private $region = 'us-east-1';

	public function __construct($access_key, $secret_key, $endpoint = 's3.amazonaws.com', $use_ssl = true, $region = 'us-east-1') {
		$this->access_key = trim($access_key);
		$this->secret_key = trim($secret_key);
		$this->endpoint = !empty($endpoint) ? trim($endpoint) : 's3.amazonaws.com';
		$this->use_ssl = (bool) $use_ssl;
		$this->region = !empty($region) ? trim($region) : 'us-east-1';
	}

	/**
	 * Send HTTP request to S3 endpoint
	 *
	 * @param string $method GET, PUT, DELETE, POST
	 * @param string $bucket
	 * @param string $uri
	 * @param array $params Query string params
	 * @param array $headers
	 * @param string|resource $body
	 * @return array
	 */
	public function request($method, $bucket = '', $uri = '', $params = array(), $headers = array(), $body = '') {
		$uri = ltrim($uri, '/');
		$host = !empty($bucket) ? $bucket . '.' . $this->endpoint : $this->endpoint;
		
		// If endpoint is IP or custom localhost, use path style
		if (filter_var($this->endpoint, FILTER_VALIDATE_IP) || 'localhost' === $this->endpoint) {
			$host = $this->endpoint;
			$path = !empty($bucket) ? '/' . $bucket . '/' . $uri : '/' . $uri;
		} else {
			$path = '/' . $uri;
		}

		$query_str = !empty($params) ? http_build_query($params) : '';
		$url = ($this->use_ssl ? 'https://' : 'http://') . $host . $path . ($query_str ? '?' . $query_str : '');

		$date = gmdate('Ymd\THis\Z');
		$datestamp = gmdate('Ymd');

		$content_hash = hash('sha256', is_string($body) ? $body : '');

		$canonical_uri = $path;
		$canonical_query = $query_str;

		$headers['Host'] = $host;
		$headers['x-amz-date'] = $date;
		$headers['x-amz-content-sha256'] = $content_hash;

		// Canonical headers
		ksort($headers);
		$canonical_headers = '';
		$signed_headers_arr = array();
		foreach ($headers as $k => $v) {
			$lower_k = strtolower($k);
			$canonical_headers .= $lower_k . ':' . trim($v) . "\n";
			$signed_headers_arr[] = $lower_k;
		}
		$signed_headers = implode(';', $signed_headers_arr);

		$canonical_request = "$method\n$canonical_uri\n$canonical_query\n$canonical_headers\n$signed_headers\n$content_hash";

		$algorithm = 'AWS4-HMAC-SHA256';
		$credential_scope = "$datestamp/{$this->region}/s3/aws4_request";
		$string_to_sign = "$algorithm\n$date\n$credential_scope\n" . hash('sha256', $canonical_request);

		// Calculate signature
		$k_date = hash_hmac('sha256', $datestamp, 'AWS4' . $this->secret_key, true);
		$k_region = hash_hmac('sha256', $this->region, $k_date, true);
		$k_service = hash_hmac('sha256', 's3', $k_region, true);
		$k_signing = hash_hmac('sha256', 'aws4_request', $k_service, true);
		$signature = hash_hmac('sha256', $string_to_sign, $k_signing);

		$authorization = "$algorithm Credential={$this->access_key}/$credential_scope, SignedHeaders=$signed_headers, Signature=$signature";
		$headers['Authorization'] = $authorization;

		$formatted_headers = array();
		foreach ($headers as $k => $v) {
			$formatted_headers[] = "$k: $v";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $formatted_headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->use_ssl);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);

		if (!empty($body)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$curl_err = curl_error($ch);
		curl_close($ch);

		if (false === $response) {
			return array('code' => 0, 'error' => $curl_err, 'body' => '', 'headers' => array());
		}

		$resp_headers = substr($response, 0, $header_size);
		$resp_body = substr($response, $header_size);

		return array(
			'code' => $http_code,
			'body' => $resp_body,
			'headers' => $resp_headers,
			'error' => ($http_code >= 400) ? $resp_body : '',
		);
	}

	/**
	 * Single-part file upload
	 */
	public function putObjectFile($file_path, $bucket, $uri) {
		$content = file_get_contents($file_path);
		return $this->request('PUT', $bucket, $uri, array(), array(
			'Content-Type' => 'application/octet-stream',
			'Content-Length' => (string) strlen($content),
		), $content);
	}

	/**
	 * Initiate Multipart Upload
	 */
	public function initiateMultipartUpload($bucket, $uri) {
		$res = $this->request('POST', $bucket, $uri, array('uploads' => ''));
		if ($res['code'] >= 200 && $res['code'] < 300) {
			if (preg_match('/<UploadId>(.*?)<\/UploadId>/i', $res['body'], $matches)) {
				return $matches[1];
			}
		}
		return false;
	}

	/**
	 * Upload a single part in a multipart session
	 */
	public function uploadPart($bucket, $uri, $uploadId, $partNumber, $data) {
		$params = array(
			'partNumber' => $partNumber,
			'uploadId'   => $uploadId,
		);
		$res = $this->request('PUT', $bucket, $uri, $params, array(
			'Content-Type' => 'application/octet-stream',
			'Content-Length' => (string) strlen($data),
		), $data);

		if ($res['code'] >= 200 && $res['code'] < 300) {
			if (preg_match('/ETag:\s*"?([^"\r\n]+)"?/i', $res['headers'], $matches)) {
				return trim($matches[1], '"');
			}
		}
		return false;
	}

	/**
	 * Complete Multipart Upload
	 */
	public function completeMultipartUpload($bucket, $uri, $uploadId, $parts) {
		$xml = '<CompleteMultipartUpload>';
		foreach ($parts as $partNumber => $etag) {
			$xml .= '<Part>';
			$xml .= '<PartNumber>' . (int) $partNumber . '</PartNumber>';
			$xml .= '<ETag>' . htmlspecialchars($etag) . '</ETag>';
			$xml .= '</Part>';
		}
		$xml .= '</CompleteMultipartUpload>';

		$params = array('uploadId' => $uploadId);
		return $this->request('POST', $bucket, $uri, $params, array(
			'Content-Type' => 'application/xml',
			'Content-Length' => (string) strlen($xml),
		), $xml);
	}

	/**
	 * Download object to local file
	 */
	public function getObjectFile($bucket, $uri, $saveTo) {
		$res = $this->request('GET', $bucket, $uri);
		if ($res['code'] >= 200 && $res['code'] < 300) {
			return (false !== file_put_contents($saveTo, $res['body']));
		}
		return false;
	}

	/**
	 * Delete object from S3
	 */
	public function deleteObject($bucket, $uri) {
		$res = $this->request('DELETE', $bucket, $uri);
		return ($res['code'] >= 200 && $res['code'] < 300);
	}

	/**
	 * List objects in bucket with prefix
	 */
	public function listObjects($bucket, $prefix = '') {
		$params = !empty($prefix) ? array('prefix' => $prefix) : array();
		$res = $this->request('GET', $bucket, '', $params);
		$files = array();
		if ($res['code'] >= 200 && $res['code'] < 300) {
			preg_match_all('/<Key>(.*?)<\/Key>/i', $res['body'], $matches);
			if (!empty($matches[1])) {
				$files = $matches[1];
			}
		}
		return $files;
	}
}
