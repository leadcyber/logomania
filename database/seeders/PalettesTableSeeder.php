<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Palette;

class PalettesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Palettes CSV file
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


        // Family_Palette CSV file
        $csvFilePath = env("PATH_FAMILY_PALETTE_CSV");

        $csvData = array_map('str_getcsv', file($csvFilePath));

        // Skip the header row
        array_shift($csvData);

        $family_palettes = [];

        foreach ($csvData as $row) {
            $family_palettes[] = [
                'palette_id' => trim($row[1]),
                'family_id' => trim($row[2]),
            ];
        }

        // Batch insert into the palettes table
        foreach ($family_palettes as $family_palette) {
            DB::table('family_palette')->insert($family_palette);
        }
    }
}
