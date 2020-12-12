<?php


namespace thans\jwt\middleware;

use thans\jwt\exception\TokenExpiredException;

class JWTAuthAndRefresh extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        // OPTIONS请求直接返回
        if ($request->isOptions()) {
            return $next($request);
        }

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
