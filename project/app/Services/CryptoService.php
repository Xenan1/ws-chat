<?php

namespace App\Services;

class CryptoService
{
    protected const string CIPHER_METHOD = 'aes-128-cbc';

    public function encrypt(string $data, string $key): string
    {
        $iv = $this->getIV($key);
        return openssl_encrypt($data, self::CIPHER_METHOD, $key, 0, $iv);
    }

    public function decrypt(string $data, string $key): string
    {
        $iv = $this->getIV($key);
        return openssl_decrypt($data, self::CIPHER_METHOD, $key, 0, $iv);
    }

    protected function getIV(string $key): string
    {
        return md5($key, true);
    }
}
