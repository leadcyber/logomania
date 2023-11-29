<?php

namespace App\Services;

use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {
        // Fetch translations from the database based on the provided parameters
        $translations = \App\Models\Translation::where('locale', $locale)
            // ->where('group', $group)
            ->pluck('value', 'key')
            ->toArray();

        return $translations;
    }
}