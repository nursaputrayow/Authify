<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class Clean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run various commands to clean the application state';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate:fresh');
        
        $this->info('Clearing config...');
        Artisan::call('config:clear');
        
        $this->info('Clearing cache...');
        Artisan::call('cache:clear');
        
        $this->info('Clearing routes...');
        Artisan::call('route:clear');
        
        $this->info('Clearing logs...');
        \File::delete(storage_path('logs/laravel.log'));

        $this->info('Application has been cleaned successfully!');
    }
}
