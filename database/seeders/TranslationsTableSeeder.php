<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Translation;
use App\Models\Family;

class TranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            'fr' => [
                'food' => 'Alimentation',
                'animals' => 'Animaux',
                'airplane' => 'Avion',
                'boat' => 'Bateau',
                'truck' => 'Camion',
                'cinema' => 'Cinéma',
                'trade' => 'Commerce',
                'culture' => 'Culture',
                'law' => 'Droit',
                'education' => 'Education',
                'childhood' => 'Enfance',
                'aesthetics' => 'Esthétique',
                'event_planning' => 'Evènementiel',
                'finance' => 'Finance',
                'hightech' => 'High Tech',
                'hospitality' => 'Hôtellerie',
                'literature' => 'Littérature',
                'leisure_games' => 'Loisirs & Jeux',
                'home' => 'Maison',
                'audio_media' => 'Média Audio',
                'print_media' => 'Média Imprimé',
                'motorcycle' => 'Moto',
                'music' => 'Musique',
                'nature' => 'Nature',
                'dating' => 'Rencontres',
                'health' => 'Santé',
                'security' => 'Sécurité',
                'personal_services' => 'Services à la personne',
                'sports' => 'Sports',
                'theatre_dance' => 'Théâtre & Danse',
                'construction' => 'Travaux',
                'bicycle' => 'Vélo',
                'automobile' => 'Automobile',
                'travel' => 'Voyage',
                'web' => 'Web',
            ],
            'en' => [
                'food' => 'Food',
                'animals' => 'Animals',
                'airplane' => 'Airplane',
                'boat' => 'Boat',
                'truck' => 'Truck',
                'cinema' => 'Cinema',
                'trade' => 'Trade',
                'culture' => 'Culture',
                'law' => 'Law',
                'education' => 'Education',
                'childhood' => 'Childhood',
                'aesthetics' => 'Aesthetics',
                'event_planning' => 'Event Planning',
                'finance' => 'Finance',
                'hightech' => 'High Tech',
                'hospitality' => 'Hospitality',
                'literature' => 'Literature',
                'leisure_games' => 'Leisure & Games',
                'home' => 'Home',
                'audio_media' => 'Audio Media',
                'print_media' => 'Print Media',
                'motorcycle' => 'Motorcycle',
                'music' => 'Music',
                'nature' => 'Nature',
                'dating' => 'Dating',
                'health' => 'Health',
                'security' => 'Security',
                'personal_services' => 'Personal Services',
                'sports' => 'Sports',
                'theatre_dance' => 'Theatre & Dance',
                'construction' => 'Construction',
                'bicycle' => 'Bicycle',
                'automobile' => 'Automobile',
                'travel' => 'Travel',
                'web' => 'Web',
            ],
        ];

        foreach ($translations as $locale => $groupTranslations) {
            foreach ($groupTranslations as $key => $value) {
                // Insert into families table
                $family = Family::where('text_code', 'families.'.$key)->first();
                if (!$family)
                    Family::create([
                        'text_code' => 'families.'.$key,
                    ]);

                // Insert into translations table
                Translation::create([
                    'group' => 'families',
                    'key' => $key,
                    'locale' => $locale,
                    'value' => $value,
                ]);
            }
        }
    }
}
