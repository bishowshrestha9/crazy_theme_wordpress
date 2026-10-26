<?php
/**
 * Safora AES-256-CBC Encryption & Decryption Engine
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Encryption {

	const MAGIC_HEADER = 'SAFORA_ENC_V01__'; // 16-byte magic identifier
	const CHUNK_SIZE = 1048576; // 1 MB streaming chunks
	const CIPHER_METHOD = 'AES-256-CBC';

	/**
	 * Encrypt a file using AES-256-CBC and PBKDF2 key derivation
	 *
	 * @param string $source_file Path to unencrypted file
	 * @param string $target_file Path where encrypted file will be created
	 * @param string $passphrase User passphrase
	 * @return bool
	 */
	public static function encrypt_file($source_file, $target_file, $passphrase) {
		if (!function_exists('openssl_encrypt')) {
			throw new Exception('OpenSSL PHP extension is required for encryption.');
		}

		$src_handle = @fopen($source_file, 'rb');
		if (!$src_handle) {
			throw new Exception("Unable to open source file for encryption: $source_file");
		}

		$dest_handle = @fopen($target_file, 'wb');
		if (!$dest_handle) {
			@fclose($src_handle);
			throw new Exception("Unable to open destination file for encryption: $target_file");
		}

		// Generate random 16-byte salt and 16-byte IV
		$salt = openssl_random_pseudo_bytes(16);
		$iv = openssl_random_pseudo_bytes(16);

		// Derive 32-byte (256-bit) encryption key using PBKDF2
		$key = hash_pbkdf2('sha256', $passphrase, $salt, 10000, 32, true);

		// Write header: [Magic Header 16b][Salt 16b][IV 16b]
		fwrite($dest_handle, self::MAGIC_HEADER);
		fwrite($dest_handle, $salt);
		fwrite($dest_handle, $iv);

		// Stream encrypt in 1MB chunks
		while (!feof($src_handle)) {
			$plaintext = fread($src_handle, self::CHUNK_SIZE);
			if (false === $plaintext || '' === $plaintext) break;

			$ciphertext = openssl_encrypt($plaintext, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);
			$chunk_length = strlen($ciphertext);

			// Write 4-byte chunk length followed by chunk data
			fwrite($dest_handle, pack('N', $chunk_length));
			fwrite($dest_handle, $ciphertext);

			// Use end of ciphertext as next IV (CBC chaining)
			$iv = substr($ciphertext, -16);
		}

		fclose($src_handle);
		fclose($dest_handle);
		return true;
	}

	/**
	 * Decrypt a file encrypted with Safora
	 *
	 * @param string $source_file Path to encrypted file (.crypt)
	 * @param string $target_file Path where decrypted file will be written
	 * @param string $passphrase User passphrase
	 * @return bool
	 */
	public static function decrypt_file($source_file, $target_file, $passphrase) {
		if (!function_exists('openssl_decrypt')) {
			throw new Exception('OpenSSL PHP extension is required for decryption.');
		}

		$src_handle = @fopen($source_file, 'rb');
		if (!$src_handle) {
			throw new Exception("Unable to open source encrypted file: $source_file");
		}

		$dest_handle = @fopen($target_file, 'wb');
		if (!$dest_handle) {
			@fclose($src_handle);
			throw new Exception("Unable to open target file for decryption: $target_file");
		}

		// Read header: [Magic Header 16b][Salt 16b][IV 16b]
		$magic = fread($src_handle, 16);
		if (self::MAGIC_HEADER !== $magic) {
			fclose($src_handle);
			fclose($dest_handle);
			throw new Exception('Invalid file header: file is not a valid Safora encrypted archive.');
		}

		$salt = fread($src_handle, 16);
		$iv = fread($src_handle, 16);

		// Derive key
		$key = hash_pbkdf2('sha256', $passphrase, $salt, 10000, 32, true);

		while (!feof($src_handle)) {
			$length_raw = fread($src_handle, 4);
			if (false === $length_raw || strlen($length_raw) < 4) break;

			$unpacked = unpack('Nlength', $length_raw);
			$chunk_length = $unpacked['length'];

			$ciphertext = fread($src_handle, $chunk_length);
			$plaintext = openssl_decrypt($ciphertext, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);

			if (false === $plaintext) {
				fclose($src_handle);
				fclose($dest_handle);
				throw new Exception('Decryption failed. Incorrect passphrase or corrupted archive.');
			}

			fwrite($dest_handle, $plaintext);
			$iv = substr($ciphertext, -16);
		}

		fclose($src_handle);
		fclose($dest_handle);
		return true;
	}
}
