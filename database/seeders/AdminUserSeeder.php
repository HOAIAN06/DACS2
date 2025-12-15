<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@hanzo.com'],
            [
                'name' => 'Admin Hanzo',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
