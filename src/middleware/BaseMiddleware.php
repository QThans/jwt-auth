<?php


namespace thans\jwt\middleware;


use thans\jwt\JWTAuth as Auth;

class BaseMiddleware
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    protected function setAuthenticationHeader($response, $token = null)
    {
        $token = $token ?: $this->auth->refresh();

        return $response->header('Authorization', 'Bearer '.$token);
    }

}