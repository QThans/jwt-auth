<?php

use thans\jwt\command\SecretCommand;
use thans\jwt\provider\JWT as JWTProvider;
use think\Console;

Console::addDefaultCommands([
    SecretCommand::class,
]);

(new JWTProvider(new \think\Request()))->init();
