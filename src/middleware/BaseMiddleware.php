<?php


namespace thans\jwt\middleware;

use thans\jwt\JWTAuth as Auth;
use think\App;
use think\facade\Cookie;

class BaseMiddleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    protected function setAuthentication($response, $token = null)
    {
        $token = $token ?: $this->auth->refresh();
        Cookie::set('token', $token);
        $this->auth->setToken($token);

        return $response->header(['Authorization' => 'Bearer '.$token]);
    }
}
