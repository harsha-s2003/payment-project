<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * AES-512 Encryption (NDPS Requirement)
 */
if (!function_exists('encryptAES512')) {
    function encryptAES512($plainText, $key) {
        return bin2hex(openssl_encrypt($plainText, "AES-256-ECB", hex2bin($key), OPENSSL_RAW_DATA));
    }
}

if (!function_exists('decryptAES512')) {
    function decryptAES512($encryptedText, $key) {
        return openssl_decrypt(hex2bin($encryptedText), "AES-256-ECB", hex2bin($key), OPENSSL_RAW_DATA);
    }
}