<?php


namespace thans\jwt\provider\JWT;

use thans\jwt\exception\JWTException;

class Provider
{
    protected $signers;

    protected $algo;

    protected $keys;

    public function getPublicKey()
    {
        if (is_file($this->keys['public'])) {
            return $this->keys['public'];
        }

        return '-----BEGIN PUBLIC KEY-----'.PHP_EOL.implode(PHP_EOL, str_split($this->keys['public'], 64)).PHP_EOL
            .'-----END PUBLIC KEY-----';
    }

    public function getPrivateKey()
    {
        $header = '-----BEGIN PRIVATE KEY-----';
        $footer = '-----END PRIVATE KEY-----';
        if (is_file($this->keys['private'])) {
            return $this->keys['private'];
        }
        if ($this->keys['password'] != '') {
            $header = '-----BEGIN ENCRYPTED PRIVATE KEY-----';
            $footer = '-----END ENCRYPTED PRIVATE KEY-----';
        }

        return $header.PHP_EOL.implode(PHP_EOL, str_split($this->keys['private'], 64)).PHP_EOL
            .$footer;
    }

    public function getSecret()
    {
        return $this->keys;
    }

    public function getPassword()
    {
        return $this->keys['password'];
    }
}
