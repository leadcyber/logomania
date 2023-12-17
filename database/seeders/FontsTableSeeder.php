<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Font;

class FontsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fonts = $this->scanFontsDirectory(env("PATH_FONTS_DIR"));
        foreach ($fonts as $font) {
            if ($font['family_id']) {
                Font::create($font);
            }
        }
    }

    function scanFontsDirectory($directory)
    {
        $result = [];

        // Get all files and directories in the current directory
        $items = scandir($directory);

        foreach ($items as $item) {
            // Skip special directories (. and ..)
            if ($item == "." || $item == "..") {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;

            // If it's a directory, recursively scan it
            if (is_dir($path)) {
                $result = array_merge($result, $this->scanFontsDirectory($path));
            } else {
                // If it's a file, add it to the result array
                $familyId = basename($directory);
                $font = \FontLib\Font::load($path);
                $result[] = [
                    'family_id' => $familyId,
                    'filename' => $item,
                    'fontname' => $font->getFontFullName(),
                ];
            }
        }

        return $result;
    }
}
