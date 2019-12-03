<?php


namespace thans\jwt\middleware;

use thans\jwt\exception\TokenExpiredException;

class JWTAuthAndRefresh extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        try {
            $this->auth->auth();
        } catch (TokenExpiredException $e) {
            $this->auth->setRefresh();
            $response = $next($request);

            return $this->setAuthentication($response);
        }

        return $next($request);
    }
}
