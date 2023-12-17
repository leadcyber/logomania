<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Icon;

class IconController extends Controller
{
    function list(Request $request) {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 9);
        $offset = ($page - 1) * $itemsPerPage;
        $combination = session('combination', null);

        $familyId = 0;
        if ($combination) {
            $familyId = $combination['family_id'];
        }

        $icons = [];
        if ($familyId) {
            $icons = Icon::orderByRaw("CASE WHEN family_id = $familyId THEN 0 ELSE 1 END")
                ->orderBy('family_id') // Replace 'column_to_order_by' with the actual column you want to use for ordering
                ->offset($offset)
                ->limit($itemsPerPage)
                ->get()->toArray();
        }
        else {
            $icons = Icon::offset($offset)
                ->limit($itemsPerPage)
                ->get()->toArray();
        }

        foreach ($icons as $key => &$icon) {
            $path = env('PATH_ICONS_DIR').'/'.$icon['family_id'].'/'.$icon['type'].'/'.$icon['filename'];
            if (file_exists($path)) {
                // Read icon file
                $iconContent = file_get_contents($path);
                $icon['svg'] = $iconContent;
            }
        }
        
        return json_encode([
            "success" => true,
            "message" => "",
            "icons" => $icons,
            "total" => Icon::count(),
        ]);
    }
}
