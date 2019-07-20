<?php

namespace thans\jwt;

use thans\jwt\parser\Parser;

class JWTAuth extends JWT
{
    public function auth()
    {
        return (array)$this->getPayload();
    }

    public function builder(array $user = [])
    {
        return $this->createToken($user);
    }
}
