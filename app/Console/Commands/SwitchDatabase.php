<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SwitchDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:switch {driver : The database driver to switch to (sqlite/mysql)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch between SQLite and MySQL configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $driver = $this->argument('driver');
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        if ($driver === 'sqlite') {
            $envContent = preg_replace('/DB_CONNECTION=\w+/', 'DB_CONNECTION=sqlite', $envContent);
            $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . database_path('database.sqlite'), $envContent);
        } elseif ($driver === 'mysql') {
            $envContent = preg_replace('/DB_CONNECTION=\w+/', 'DB_CONNECTION=mysql', $envContent);
            $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=authify_db', $envContent);
            $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=127.0.0.1', $envContent);
            $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=3306', $envContent);
            $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=root', $envContent);
            $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=', $envContent);
        } else {
            $this->error('Invalid driver specified. Use "sqlite" or "mysql".');
            return Command::FAILURE;
        }

        File::put($envPath, $envContent);

        $this->info('Database configuration switched to ' . strtoupper($driver) . '.');
        $this->info('Run `php artisan config:cache` to apply the changes.');
        return Command::SUCCESS;
    }
}