<?php

namespace thans\jwt\facade;

use think\Facade;

/**
 * Class JWTAuth
 * @package thans\jwt\facade
 * @method builder(array $user = []) static
 * @method auth() static
 * @method refresh() static
 */
class JWTAuth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'thans\jwt\JWTAuth';
    }
}
