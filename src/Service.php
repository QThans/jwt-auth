<?php


namespace thans\jwt;


use thans\jwt\command\SecretCommand;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands(SecretCommand::class);
    }
}