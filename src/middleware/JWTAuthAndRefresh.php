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
            $response = $next($request);

            return $this->setAuthenticationHeader($response);
        }

        return $next($request);
    }
}