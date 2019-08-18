<?php


namespace thans\jwt;


use thans\jwt\command\SecretCommand;
use thans\jwt\provider\JWT as JWTProvider;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands(SecretCommand::class);
        (new JWTProvider(new \think\Request()))->init();
    }
}