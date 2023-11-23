<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Milos',
            'lastname' => 'Veselinovic',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        User::create([
            'firstname' => 'Admin',
            'lastname' => '',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);
    }
}
