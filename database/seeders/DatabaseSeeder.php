<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'firstname' => 'Milos',
            'lastname' => 'Veselinovic',
            'email' => 'mveselinovic858@gmail.com',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $user = User::create([
            'firstname' => 'Admin',
            'lastname' => '',
            'email' => 'minionhub.dev@gmail.com',
            'role' => 'user',
            'password' => 'password',
        ]);
    }
}
