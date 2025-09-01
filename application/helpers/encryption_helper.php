<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function encryptAES512($plainText, $key) {
    $iv = hex2bin('000102030405060708090a0b0c0d0e0f');
    $key = hash('sha512', $key, true); // 512-bit key
    $cipherText = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return strtoupper(bin2hex($cipherText));
}

function decryptAES512($encryptedText, $key) {
    $iv = hex2bin('000102030405060708090a0b0c0d0e0f');
    $key = hash('sha512', $key, true);
    $cipherText = hex2bin($encryptedText);
    $plainText = openssl_decrypt($cipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $plainText;
}