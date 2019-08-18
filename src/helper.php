<?php

use thans\jwt\command\SecretCommand;
use thans\jwt\provider\JWT as JWTProvider;
use think\Console;
use think\facade\App;

if (strpos(App::version(), '6.0') !== false) {
    Console::addCommands([
        SecretCommand::class,
    ]);
} else {
    Console::addDefaultCommands([
        SecretCommand::class,
    ]);
}

(new JWTProvider(new \think\Request()))->init();
