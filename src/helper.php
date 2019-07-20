<?php

use thans\jwt\command\SecretCommand;
use think\Console;

Console::addDefaultCommands([
    SecretCommand::class,
]);