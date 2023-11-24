<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Palette;

class PalettesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Replace the path with the actual path to your CSV file
        $csvFilePath = env("PATH_PALETTES_CSV");

        $csvData = array_map('str_getcsv', file($csvFilePath));

        // Skip the header row
        array_shift($csvData);

        $palettes = [];

        foreach ($csvData as $row) {
            $palettes[] = [
                'background' => ltrim($row[1], '#'),  // Remove the '#' if present
                'text1' => ltrim($row[6], '#'),
                'text2' => ltrim($row[11], '#'),
                'icon' => ltrim($row[16], '#'),
            ];
        }

        // Batch insert into the palettes table
        foreach ($palettes as $palette) {
            Palette::create($palette);
        }
    }
}
