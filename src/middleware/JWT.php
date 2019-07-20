<?php


namespace thans\jwt\middleware;

use thans\jwt\provider\JWT as JWTProvider;

class JWT
{
    public function handle($request, \Closure $next)
    {
        //初始化
        (new JWTProvider($request))->init();
        return $next($request);
    }
}