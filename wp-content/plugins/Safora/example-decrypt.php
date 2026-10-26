<?php
/**
 * Safora Standalone Archive Decryption Tool
 *
 * Use this standalone PHP CLI script to decrypt any Safora encrypted archive
 * (.crypt) if your WordPress site is offline.
 *
 * Usage:
 *   php example-decrypt.php <input_file.crypt> <output_file> <passphrase>
 *
 * Example:
 *   php example-decrypt.php backup_2026-08-20-db.gz.crypt restored-db.gz "MySecretPassword"
 */

if (php_sapi_name() !== 'cli') {
	die("This tool must be run from the command line.\n");
}

if ($argc < 4) {
	echo "Safora Standalone Archive Decryption Tool\n";
	echo "Usage: php " . basename($argv[0]) . " <input_file.crypt> <output_file> <passphrase>\n";
	exit(1);
}

$source_file = $argv[1];
$target_file = $argv[2];
$passphrase  = $argv[3];

if (!file_exists($source_file)) {
	echo "Error: Source file does not exist: $source_file\n";
	exit(1);
}

if (!function_exists('openssl_decrypt')) {
	echo "Error: OpenSSL PHP extension is required.\n";
	exit(1);
}

echo "Decrypting '$source_file' -> '$target_file'...\n";

$src = fopen($source_file, 'rb');
$dest = fopen($target_file, 'wb');

if (!$src || !$dest) {
	echo "Error opening files.\n";
	exit(1);
}

$magic = fread($src, 16);
if ('SAFORA_ENC_V01__' !== $magic) {
	echo "Error: File is not a valid Safora encrypted archive.\n";
	exit(1);
}

$salt = fread($src, 16);
$iv   = fread($src, 16);

$key = hash_pbkdf2('sha256', $passphrase, $salt, 10000, 32, true);

$chunks_processed = 0;
while (!feof($src)) {
	$length_raw = fread($src, 4);
	if (false === $length_raw || strlen($length_raw) < 4) break;

	$unpacked = unpack('Nlength', $length_raw);
	$chunk_length = $unpacked['length'];

	$ciphertext = fread($src, $chunk_length);
	$plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

	if (false === $plaintext) {
		echo "Error: Decryption failed. Incorrect passphrase or corrupted file.\n";
		fclose($src);
		fclose($dest);
		@unlink($target_file);
		exit(1);
	}

	fwrite($dest, $plaintext);
	$iv = substr($ciphertext, -16);
	$chunks_processed++;
}

fclose($src);
fclose($dest);

echo "Success! Successfully decrypted $chunks_processed chunks into '$target_file'.\n";
exit(0);
