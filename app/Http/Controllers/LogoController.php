<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Topic;
use App\Models\Combination;
use App\Models\Logo;
use App\Models\Font;
use App\Models\Palette;
use App\Models\Icon;
use Illuminate\Http\Request;

use Imagick;

class LogoController extends Controller
{

    public function index() {
        $topic = session('topic', null);
        if (!$topic) {
            return redirect()->route('topic');
        }
        
        $logos = session('logos', null);
        if (!$logos) {
            $combinations = [];

            // Ratios according to logo type and family
            $type_ratio = ['text_only' => 45, 'text_icon_left' => 30, 'text_icon_top' => 25];
            $family_ratio = ['primary' => 70, 'secondary' => 30];

            // Fetch existing combinations with primary and secondary families
            $combinations1 = Combination::with(['fonts', 'palettes', 'icons'])
                ->where('family_id', $topic['family1_id'])
                ->select(['family_id', 'font_id', 'palette_id', 'icon_id', 'type'])
                ->orderBy('score', 'desc')
                ->limit(15)->get()->toArray();
            $combinations2 = Combination::with(['fonts', 'palettes', 'icons'])
                ->where('family_id', $topic['family2_id'])
                ->select(['family_id', 'font_id', 'palette_id', 'icon_id', 'type'])
                ->orderBy('score', 'desc')
                ->limit(5)->get()->toArray();
            $combinations = array_merge($combinations1, $combinations2);
            // Calculates the number of logos to be newly generated
            foreach ($combinations as $combination) {
                $type_ratio[$combination['type']]--;
                if ($combination['family_id'] == $topic['family1_id'])
                    $type_ratio['primary']--;
                else
                    $type_ratio['secondary']--;
            }

            // Fetch fonts, palettes and icons for primary and secondary families
            $resources = [
                'primary' => Family::with(['fonts', 'palettes', 'icons'])
                    ->find($topic['family1_id'])
                    ->toArray(),
                'secondary' => Family::with(['fonts', 'palettes', 'icons'])
                    ->find($topic['family2_id'])
                    ->toArray(),
            ];

            // Generates combinations randomly
            $families = ["primary", 'secondary'];
            $types = ["text_only", 'text_icon_left', 'text_icon_top'];
            while (count($combinations) < 100) {
                // Select family and type randomly
                if (count($families) <= 0 || count($families) <= 0)
                    break;
                $family = $families[array_rand($families)];
                $type = $types[array_rand($types)];

                $res = $resources[$family];
                $font = $res['fonts'][array_rand($res['fonts'])];
                $palette = $res['palettes'][array_rand($res['palettes'])];
                $icon = $res['icons'][array_rand($res['icons'])];
                $combination = [
                    'family_id' => $res['id'],
                    'font_id' => $font['id'],
                    'font' => $font,
                    'palette_id' => $palette['id'],
                    'palette' => $palette,
                    'icon_id' => $icon['id'],
                    'icon' => $icon,
                    'type' => $type,
                ];

                if (!in_array($combination, $combinations, true)) {
                    $combinations[] = $combination;
                    $family_ratio[$family]--;
                    $type_ratio[$type]--;
                    if ($family_ratio[$family] == 0)
                        unset($families[array_search($family, $families)]);
                    if ($type_ratio[$type] == 0)
                        unset($types[array_search($type, $types)]);
                }
            }

            // return $combinations;
            $logoNames = [];
            if ($topic['suggestions']) {
                $logoNames = explode(',', $topic['suggestions']);
                $logoNames = array_map(function ($name) {
                    return trim($name);
                }, $logoNames);
            }
            $logos = [];
            foreach ($combinations as $index => $combination) {
                $text = $logoNames[$index % count($logoNames)];
                $combination['text'] = $text;
                $combination['id'] = $index;
                $logos[] = $combination;
            }
            session(['logos' => $logos]);
        }

        return view('pages.logos.index');
    }

    public function edit(Request $request, $id) {
        $logos = session('logos', null);
        if (empty($logos)) {
            return redirect('topic');
        }
        $combination = $logos[$id];
        session(['combination'=> $combination]);
        $logo = $logos[$id];
        $iconFile = env('PATH_ICONS_DIR').'/'.$logo['icon']['family_id'].'/'.$logo['icon']['type'].'/'.$logo['icon']['filename'];
        $iconContent = file_get_contents($iconFile);
        $logo['icon']['blob'] = base64_encode($iconContent);
        $svg = $this->renderLogo($logo, 800, 500);

        return view('pages.logos.edit', compact('svg', 'logo'));
    }

    public function renderLogos(Request $request) {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 9);
        $sort = $request->get('sort', '');

        $logos = session('logos', null);
        $totalItems = count($logos);
        $favorites = [];
        if ($logos) {
            if ($sort == 'favorite') {
                usort($logos, function ($a, $b) {
                    return $b['favorite'] - $a['favorite'];
                });
            }
            foreach ($logos as $index => &$logo) {
                if (isset($logo['favorite']) && $logo['favorite']) $favorites[] = $logo['id'];
            }
            $startIndex = ($page - 1) * $itemsPerPage;
            $logos = array_slice($logos, $startIndex, $itemsPerPage);
            foreach ($logos as $index => &$logo) {
                $svg = $this->renderLogo($logo);
                if ($svg) {
                    $logo['svg'] = 'data:image/svg+xml;base64,'.base64_encode($svg);
                }
            }
        }

        return json_encode([
            "success" => true,
            "message" => "",
            "logos" => $logos,
            "favorites" => $favorites,
            "total" => $totalItems,
        ]);
    }

    public function favorites(Request $request) {
        $favorites = $request->get("favorites");
        
        $logos = session('logos', null);

        if ($logos) {
            foreach ($logos as $index => &$logo) {
                $logo['favorite'] = in_array($logo['id'], $favorites) ? true : false;
            }
            session(['logos' => $logos]);
        }

        return json_encode([
            "success" => true,
            "message" => "",
        ]);
    }

    public function renderLogosPalette(Request $request) {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 9);
        $offset = ($page - 1) * $itemsPerPage;

        $palettes = Palette::offset($offset)
            ->limit($itemsPerPage)
            ->get()->toArray();

        $data = [
            'icon' => Icon::find(104)->toArray(),
            'font' => Font::find(70)->toArray(),
            'type' => 'text_icon_left',
            'text' => 'Test Logo',
        ];

        foreach ($palettes as $key => &$palette) {
            $data['palette'] = $palette;
            $svg = $this->renderLogo($data);

            if ($svg) {
                $palette['svg'] = 'data:image/svg+xml;base64,'.base64_encode($svg);
            }
        }
        
        return json_encode([
            "success" => true,
            "message" => "",
            "palettes" => $palettes,
            "total" => Palette::count(),
        ]);
    }

    public function renderLogosLayout(Request $request) {
        $data = [
            'icon' => Icon::find(104)->toArray(),
            'font' => Font::find(70)->toArray(),
            'text' => 'Test Logo',
            'palette' => Palette::find(1)->toArray(),
        ];

        $layouts = [
            ["type" => 'text_only'],
            ["type" => 'text_icon_left'],
            ["type" => 'text_icon_top']
        ];

        foreach ($layouts as &$layout) {
            $data['type'] = $layout['type'];
            $svg = $this->renderLogo($data);

            if ($svg) {
                $layout['svg'] = 'data:image/svg+xml;base64,'.base64_encode($svg);
            }
        }
        
        return json_encode([
            "success" => true,
            "message" => "",
            "layouts" => $layouts,
        ]);
    }

    public function renderLogo($data, $width = 380, $height = 240)
    {
        $iconWidth = $width / 10;
        $iconHeight = $width / 10;
        $textWidth = $width * 0.6;
        $textHeight = $iconHeight + 20;
        try {
            $words = explode(' ', $data['text']);
            $text1 = $words[0];
            $text2 = isset($words[1]) ? $words[1] : '';

            $font = $data['font'];
            $icon = $data['icon'];
            $fontFile = env('PATH_FONTS_DIR').'/'.$font['family_id'].'/'.$font['filename'];
            $iconFile = env('PATH_ICONS_DIR').'/'.$icon['family_id'].'/'.$icon['type'].'/'.$icon['filename'];

            $textBox = LogoController::calculateTextSize($text1.$text2, $textWidth, $textHeight, $fontFile);

            if (file_exists($fontFile)) {
                // Font content as a string
                $fontContent = file_get_contents($fontFile);

                // SVG icon content as a string
                $iconContent = file_get_contents($iconFile);

                // Change the color of icon
                if ($icon['type'] == 'fillable') {
                    $iconContent = preg_replace('/fill="#[0-9a-fA-F]{3,6}"/', 'fill="#' . $data['palette']['icon'] . '"', $iconContent);
                    $iconContent = preg_replace('/fill:#[0-9a-fA-F]{3,6}/', 'fill:#' . $data['palette']['icon'], $iconContent);
                    $iconContent = preg_replace('/stroke="#[0-9a-fA-F]{3,6}"/', 'stroke="#' . $data['palette']['icon'] . '"', $iconContent);
                    $iconContent = preg_replace('/stroke:#[0-9a-fA-F]{3,6}/', 'stroke:#' . $data['palette']['icon'], $iconContent);
                    $iconContent = preg_replace_callback('/<svg(.*?)>/', function ($matches) use ($data) {
                        if (strpos($matches[1], 'fill=') === false) {
                            // If fill attribute is not present, add it
                            return '<svg fill="#' . $data['palette']['icon'] . '" ' . $matches[1] . '>';
                        } else {
                            // If fill attribute is present, leave it unchanged
                            return '<svg' . $matches[1] . '>';
                        }
                    }, $iconContent);
                }

                // Icon position styles
                $margin = $data['type'] == 'text_icon_top' ? 'margin-bottom: 5px;' : 'margin-right: 5px;';
                $image = '<img part="icon" style="'.$margin.' width: '.$iconWidth.'px; height: '.$iconHeight.'px; '.($data['type'] == 'text_only' ? 'display: none;' : '').'" src="data:image/svg+xml;base64,'.base64_encode($iconContent).'"></img>';
                $flexDirection = $data['type'] == 'text_icon_top' ? 'column' : 'row';
                
                $svg = '<svg width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg">
                    <style>@font-face { font-family: "'.$font['fontname'].'"; src: url("data:font/ttf;base64,'.base64_encode($fontContent).'") format("truetype"); } .watermark {position: absolute; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 60px; transform: rotate(-25deg); font-weight: 900; opacity: 0.04;}</style>
                    <foreignObject width="100%" height="100%">
                        <div part="background" xmlns="http://www.w3.org/1999/xhtml" style="background: #'.$data['palette']['background'].'; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: '.$flexDirection.';">
                            '.$image.'
                            <span part="font" style="font-family: \''.$font['fontname'].'\'; font-size: '.$textBox['font_size'].'pt; line-height: 1;">
                                <span part="text1" style="color: #'.$data['palette']['text1'].'">'.$text1.'</span><span part="text2" style="color: #'.$data['palette']['text2'].'">'.$text2.'</span>
                            </span>
                            <span class="watermark">LOGOFULL</span>
                        </div>
                    </foreignObject>
                </svg>';

                return $svg;
            } else {
                // echo "Font file not found: $fontFile";
                return null;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Function to calculate the font size to fill the most space
     *
     * @param string $text
     * @param float $maxWidth
     * @param float $maxHeight
     * @return array
     */
    public static function calculateTextSize($text, $maxWidth, $maxHeight, $fontFile)
    {
        $size = 0; // Starting font size
        $width = 0;
        $height = 0;

        do {
            $fontBox = imagettfbbox($size, 0, $fontFile, $text);
            $textWidth = $fontBox[2] - $fontBox[0];
            $textHeight = $fontBox[1] - $fontBox[7];

            // Check if the text fits within the maximum width and height
            if ($textWidth <= $maxWidth && $textHeight <= $maxHeight) {
                $width = $textWidth;
                $height = $textHeight;
                $size++;
            } else {
                // Reduce font size to fit within the maximum dimensions
                $size--;
                break;
            }
        } while (true);

        return [
            'font_size' => $size,
            'width' => $width,
            'height' => $height,
        ];
    }
}
