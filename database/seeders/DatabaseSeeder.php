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
        // Admin Account (only if no admin exists)
        if (User::where('role', 'admin')->doesntExist()) {
            User::firstOrCreate(
                ['email' => 'admin@anagkazo.co.tz'],
                [
                    'name' => 'Admin',
                    'role' => 'admin',
                    'password' => \Illuminate\Support\Facades\Hash::make('superuser@2026'),
                ]
            );
        }

        // Staff Account (only if no staff exists)
        if (User::where('role', 'staff')->doesntExist()) {
            User::firstOrCreate(
                ['email' => 'staff@anagkazo.co.tz'],
                [
                    'name' => 'Staff',
                    'role' => 'staff',
                    'password' => \Illuminate\Support\Facades\Hash::make('staffwaanagkazo@2026'),
                ]
            );
        }

        // Default payment methods if none configured
        if (\App\Models\PaymentMethod::count() === 0) {
            \App\Models\PaymentMethod::firstOrCreate(
                ['name' => 'CRDB Bank A/C'],
                [
                    'type' => 'bank_transfer',
                    'account_number_or_till' => '0150123456789',
                    'account_name' => 'Anagkazo Autoparts Limited',
                    'is_active' => true,
                ]
            );
            \App\Models\PaymentMethod::firstOrCreate(
                ['name' => 'Vodacom Lipa Namba (M-Pesa)'],
                [
                    'type' => 'mobile_money',
                    'account_number_or_till' => '5522331',
                    'account_name' => 'Anagkazo Autoparts',
                    'is_active' => true,
                ]
            );
        }
    }
}

