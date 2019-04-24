<?php

namespace App\Console;

use App\Console\Commands\Robot\InitCommand;
use App\Console\Commands\Robot\ManipulateCommand;
use App\Console\Commands\Robot\QuitCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        InitCommand::class,
        ManipulateCommand::class,
        QuitCommand::class,
    ];
}
