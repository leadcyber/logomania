<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;

class TranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Specify the path to your CSV file
        $csvFilePath = env("PATH_TRANSLATIONS_CSV");

        // Get the CSV data with tab (\t) as the delimiter
        $csvData = array_map(function ($row) {
            return explode("   ", $row);
        }, file($csvFilePath));

        // Remove the header row
        $header = array_shift($csvData);

        // Insert data into the translations table
        foreach ($csvData as $row) {
            if (count($row) < 4) continue;
            
            Translation::create([
                'group' => $row[0],
                'key' => $row[1],
                'locale' => 'en',
                'value' => trim($row[2]),
            ]);
            Translation::create([
                'group' => $row[0],
                'key' => $row[1],
                'locale' => 'fr',
                'value' => trim($row[3]),
            ]);
        }
    }
}
