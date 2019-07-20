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
        return $this->keys['public'];
    }

    public function getPrivateKey()
    {
        return $this->keys['private'];
    }

    public function getSecret()
    {
        return $this->keys;
    }
}