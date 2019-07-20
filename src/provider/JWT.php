<?php


namespace thans\jwt\provider;

use thans\jwt\facade\JWTAuth;
use thans\jwt\parser\AuthHeader;
use thans\jwt\parser\Cookie;
use thans\jwt\parser\Param;
use think\Container;
use think\facade\Config;
use think\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class JWT
{
    private $request;

    private $config;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $config        = require __DIR__.'/../../config/config.php';
        $this->config  = array_merge($config, Config::get('jwt.') ?? []);
    }

    protected function registerBlacklist()
    {
        Container::get('thans\jwt\Blacklist', [
            new $this->config['blacklist_storage'],
        ]);
    }


    protected function registerProvider()
    {
        Container::get('thans\jwt\provider\JWT\Lcobucci', [
            new Builder(),
            new Parser(),
            $this->config['algo'],
            $this->config['secret'],
        ]);
    }

    protected function registerFactory()
    {
        Container::get('thans\jwt\claim\Factory', [
            new Request(),
            $this->config['ttl'],
            $this->config['refresh_ttl'],
        ]);
    }

    protected function registerPayload()
    {
        Container::get('thans\jwt\Payload', [
            Container::get('thans\jwt\claim\Factory'),
        ]);
    }

    protected function registerManager()
    {
        Container::get('thans\jwt\Manager', [
            Container::get('thans\jwt\Blacklist'),
            Container::get('thans\jwt\Payload'),
            Container::get('thans\jwt\provider\JWT\Lcobucci'),
        ]);
    }

    protected function registerJWTAuth()
    {
        JWTAuth::parser()->setRequest($this->request)->setChain([
            new AuthHeader(),
            new Cookie(),
            new Param(),
        ]);
    }

    public function init()
    {
        $this->registerBlacklist();
        $this->registerProvider();
        $this->registerFactory();
        $this->registerPayload();
        $this->registerManager();
        $this->registerJWTAuth();
    }
}
