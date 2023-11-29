<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            FamiliesTableSeeder::class,
            TranslationsTableSeeder::class,
            IconsTableSeeder::class,
            FontsTableSeeder::class,
            PalettesTableSeeder::class,
        ]);
    }
}
