<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@anagkazo.co.tz'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('superuser@2026'),
            ]
        );

        // Staff Account
        User::updateOrCreate(
            ['email' => 'staff@anagkazo.co.tz'],
            [
                'name' => 'Staff',
                'role' => 'staff',
                'password' => \Illuminate\Support\Facades\Hash::make('staffwaanagkazo@2026'),
            ]
        );

        $this->call(KariakooTyreSeeder::class);
    }
}
