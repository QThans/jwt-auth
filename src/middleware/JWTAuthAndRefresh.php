<?php


namespace thans\jwt\middleware;

use thans\jwt\exception\TokenExpiredException;

class JWTAuthAndRefresh extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        // 跨域检测直接返回
        if($request->isOptions()){
            return response();
        }

        try {
            $this->auth->auth();
        } catch (TokenExpiredException $e) {
            // 如果在黑名单宽限期不刷新
            if(!$this->auth->validateGracePeriod()){

                $this->auth->setRefresh();
                $response = $next($request);

                return $this->setAuthentication($response);
            }
        }

        return $next($request);
    }
}
