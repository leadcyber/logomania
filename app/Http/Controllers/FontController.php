<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Font;

class FontController extends Controller
{
    function fontsRendered(Request $request) {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 9);
        $offset = ($page - 1) * $itemsPerPage;
        $width = $request->get('width', 280);
        $height = $request->get('height', 40);
        $combination = session('combination', null);

        $familyId = 0;
        if ($combination) {
            $familyId = $combination['family_id'];
        }

        $fonts = [];
        if ($familyId) {
            $fonts = Font::orderByRaw("CASE WHEN family_id = $familyId THEN 0 ELSE 1 END")
                ->orderBy('family_id') // Replace 'column_to_order_by' with the actual column you want to use for ordering
                ->offset($offset)
                ->limit($itemsPerPage)
                ->get()->toArray();
        }
        else {
            $fonts = Font::offset($offset)
                ->limit($itemsPerPage)
                ->get()->toArray();
        }

        foreach ($fonts as $key => &$font) {
            $path = env('PATH_FONTS_DIR').'/'.$font['family_id'].'/'.$font['filename'];
            if (file_exists($path)) {
                // Calculate font size for best look
                // $textBox = LogoController::calculateTextSize($font['fontname'], $width, $height, $path);
                // if (isset($textBox['font_size'])) $font['size'] = $textBox['font_size'];
                // else $font['size'] = 16;

                // Read font file
                $fontContent = file_get_contents($path);
                $font['style'] = '@font-face { font-family: "'.$font['fontname'].'"; src: url("data:font/ttf;base64,'.base64_encode($fontContent).'") format("truetype"); }';
            }
        }
        
        return json_encode([
            "success" => true,
            "message" => "",
            "fonts" => $fonts,
            "total" => Font::count(),
        ]);
    }
}
