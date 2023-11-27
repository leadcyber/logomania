<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Translation;
use App\Models\Family;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            'food',
            'animals',
            'airplane',
            'boat',
            'truck',
            'cinema',
            'trade',
            'culture',
            'law',
            'education',
            'childhood',
            'aesthetics',
            'event_planning',
            'finance',
            'hightech',
            'hospitality',
            'literature',
            'leisure_games',
            'home',
            'audio_media',
            'print_media',
            'motorcycle',
            'music',
            'nature',
            'dating',
            'health',
            'security',
            'personal_services',
            'sports',
            'theatre_dance',
            'construction',
            'bicycle',
            'automobile',
            'travel',
            'web'
        ];

        foreach ($families as $family) {
            Family::create([
                'text_code' => 'families.' . $family,
            ]);
        }
    }
}
