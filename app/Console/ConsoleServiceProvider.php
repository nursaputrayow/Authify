<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            \App\Console\Commands\Clean::class,
        ]);
    }

    public function schedule(Schedule $schedule)
    {
        //
    }
}