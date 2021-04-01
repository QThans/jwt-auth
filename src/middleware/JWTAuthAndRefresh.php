<?php


namespace thans\jwt\middleware;

use thans\jwt\exception\TokenExpiredException;
use thans\jwt\exception\TokenBlacklistGracePeriodException;

class JWTAuthAndRefresh extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        // 跨域检测直接返回
        if($request->isOptions()){
            return response();
        }

        // 验证token
        try {

            $this->auth->auth();

        // 捕获token过期
        } catch (TokenExpiredException $e) {
            // 尝试刷新token
            try {

                $this->auth->setRefresh();
                $token = $this->auth->refresh();

                // $payload = $this->auth->auth(false);
                // $request->uid = $payload['uid']->getValue();

                $response = $next($request);
                return $this->setAuthentication($response, $token);

            // 捕获黑名单宽限期
            } catch (TokenBlacklistGracePeriodException $e) {

                // $payload = $this->auth->auth(false);
                // $request->uid = $payload['uid']->getValue();
                return $next($request);
            }

        // 捕获黑名单宽限期
        } catch (TokenBlacklistGracePeriodException $e) {
            // $payload = $this->auth->auth(false);
            // $request->uid = $payload['uid']->getValue();
        }

        return $next($request);
    }
}
