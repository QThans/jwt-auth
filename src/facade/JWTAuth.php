<?php

namespace thans\jwt\facade;

use think\Facade;

class JWTAuth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'thans\jwt\JWTAuth';
    }
}
