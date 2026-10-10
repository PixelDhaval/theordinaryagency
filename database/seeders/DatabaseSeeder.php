<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Model events stay enabled so posts get their reading time and categories their slug.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('Skipping sample data in production. Create your admin with: php artisan make:filament-user');

            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@theordinaryagency.test'],
            ['name' => 'Studio Admin', 'password' => 'password', 'email_verified_at' => now()],
        );

        $this->call(SampleContentSeeder::class);
    }
}
