<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Icon;

class IconsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = $this->scanIconsDirectory(env('DIR_ICONS'));
        foreach ($icons as $icon) {
            if ($icon['family_id']) {
                Icon::create($icon);
            }
        }
    }

    function scanIconsDirectory($directory)
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
                $result = array_merge($result, $this->scanIconsDirectory($path));
            } else {
                // If it's a file, process it and add to the result array
                $info = pathinfo($path);
                if (isset($info['extension']) && strtolower($info['extension']) === 'svg') {
                    $familyId = basename(dirname(dirname($path)));
                    $colorFillable = basename(dirname($path));
                    $filename = basename($path);

                    $result[] = [
                        'family_id' => $familyId,
                        'filename' => $filename,
                        'type' => ($colorFillable === 'color') ? 'color' : 'fillable',
                    ];
                }
            }
        }

        return $result;
    }
}
