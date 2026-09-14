<?php

namespace App\Console\Commands;

use Database\Seeders\AdminSeeder;
use Illuminate\Console\Command;

class ProvisionCommand extends Command
{
    protected $signature = 'pbm:provision';

    protected $description = 'Migrate the database and ensure the trial admin account exists';

    public function handle(): int
    {
        if (blank(config('app.key'))) {
            $this->call('key:generate', ['--force' => true]);
        }

        if (config('database.default') === 'sqlite') {
            $configured = (string) config('database.connections.sqlite.database');
            $path = $configured !== '' && $configured !== ':memory:'
                ? $configured
                : database_path('database.sqlite');

            if (! str_contains($path, DIRECTORY_SEPARATOR) && $path !== ':memory:') {
                $path = base_path($path);
            }

            if (! is_file($path)) {
                touch($path);
            }
        }

        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--class' => AdminSeeder::class, '--force' => true]);

        return self::SUCCESS;
    }
}
