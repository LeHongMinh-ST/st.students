<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_name' => 'sytem_admin',
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'stdse@vnua.edu.vn',
            'password' => Hash::make('123456aA@'),
            'role' => 'super_admin',
            'status' => 'active',
            'code' => 'ADMIN001',
            'email_verified_at' => now(),
        ]);
    }
}
