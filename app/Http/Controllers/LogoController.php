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
use OpenAI\Laravel\Facades\OpenAI;

use SVG\SVG;
use SVG\Nodes\Shapes\SVGCircle;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Texts\SVGText;
use SVG\Nodes\Structures\SVGGroup;

class LogoController extends Controller
{
    public function topic()
    {
        $families = Family::all();

        return view('pages.logo.topic', compact('families'));
    }

    public function storeTopic(Request $request)
    {
        $topic = $request->all();

        $company_name = $topic['company_name'];
        $desc = $topic['desc'];
        $familyIds = [];

        // Get company name suggestions from openAI
        $nameSuggestions = "";
        if (!$company_name && $desc) {
            $prompt = "The activity of my company is: $desc.\nPropose 20 short names for it (WITHOUT NUMBERING) consisting of two words separated by a SPACE.\nNote that I forbid you from using accented words. Respond with an unordered list, without introduction, and separate each name with a comma. Don't include numbers.\n\nOutput Format: Happy Family,Awesome World";
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $nameSuggestions = $result->choices[0]->message->content;
        }
        else if ($company_name) {
            $nameSuggestions = implode(',', $this->suggestLogoNames($topic['company_name']));
        }

        // Identify industries related to the company
        $familySuggestions = "";
        $families = Family::all()->toArray();
        $familyNames = array_map(function ($family) {
            return str_replace("families.", "", $family['text_code']);
        }, $families);
        if ($company_name || $desc) {
            $prompt = "Company Name: '$company_name'\nDescription: '$desc'\n\n
            Identify 2 industries related to this company from the following array. Please use original values without numbering, ordering, introduction or comments.\n\n".json_encode($familyNames)."\n\nOutput Format: home,family";
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
            
            $familySuggestions = explode(',', $result->choices[0]->message->content);
            if (count($familySuggestions) >= 1) {
                foreach ($familySuggestions as $familySuggestion) {
                    foreach ($families as $family) {
                        if (str_contains($family['text_code'], trim($familySuggestion))) {
                            $familyIds[] = $family['id'];
                        }
                    }
                }
            }
        }

        $user_id = null;
        if (auth()->check()) {
            $user_id = auth()->user()->id;
        }

        // Store topic to database
        if (count($familyIds) >= 1) {
            $topic = Topic::create([
                'user_id' => $user_id,
                'company_name' => $company_name,
                'desc' => $desc,
                'family1_id' => $familyIds[0],
                'family2_id' => isset($familyIds[1]) ? $familyIds[1] : $familyIds[0],
                'suggestions' => $nameSuggestions,
            ]);

            // Store topic into session to track the progress
            session(['topic' => $topic, 'logos' => null]);

            // Redirect to logo generation page
            return redirect()->route('logo.generate');
        }

        // Exception handler when openai doesn't work as expected
    }

    public function generate()
    {
        $topic = session('topic', null);
        if (!$topic) {
            return redirect()->route('logo.topic');
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
                $text = $logoNames[array_rand($logoNames)];
                $combination['text'] = $text;
                $logos[] = $combination;
                // if ($text) {
                    // $logoSVG = $this->generateLogo($index, $combination, $text);
                    // if ($logoSVG) {
                    //     $logos[] = $logoSVG;
                    // }
                // }
            }
            session(['logos' => $logos]);
        }

        return view('pages.logo.generate');
    }

    public function generateSVGs(Request $request) {
        $page = $request->get('page', 1);
        $itemsPerPage = $request->get('itemsPerPage', 9);

        $logos = session('logos', null);
        $totalItems = count($logos);
        $svgs = [];
        if ($logos) {
            $startIndex = ($page - 1) * $itemsPerPage;
            $logos = array_slice($logos, $startIndex, $itemsPerPage);
            foreach ($logos as $index => $logo) {
                $svg = $this->generateSVG($logo);
                if ($svg) $svgs[] = $svg;
            }
        }

        return json_encode([
            "success" => true,
            "message" => "",
            "svgs" => $svgs,
            "total" => $totalItems,
        ]);
    }

    public function generateSVG($data)
    {
        $svgMaxWidth = 240;
        $svgMaxHeight = 140;
        $iconWidth = 40;
        $iconHeight = 40;
        try {
            $words = explode(' ', $data['text']);
            $text1 = $words[0];
            $text2 = isset($words[1]) ? $words[1] : '';

            // Estimate font size
            // $textWidth = $svgMaxWidth;
            // $textHeight = $svgMaxHeight - $iconHeight;
            // if ($data['type'] == 'text_icon_top') {
            //     $textWidth = $svgMaxWidth;
            // }
            // else if ($data['type'] == 'text_icon_left') {
            //     $textWidth = $svgMaxWidth - $iconWidth;
            // }

            $font = $data['font'];
            $icon = $data['icon'];
            $fontFile = env('PATH_FONTS_DIR').'/'.$font['family_id'].'/'.$font['filename'];
            $iconFile = env('PATH_ICONS_DIR').'/'.$font['family_id'].'/'.$icon['type'].'/'.$icon['filename'];

            $textBox = $this->calculateTextSize($text1.$text2, 240, 50, $fontFile);

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
                $image = $data['type'] == 'text_only' ? '' : '<img style="'.$margin.' width: '.$iconWidth.'px; height: '.$iconHeight.'px;" src="data:image/svg+xml;base64,'.base64_encode($iconContent).'"></img>';
                $flexDirection = $data['type'] == 'text_icon_top' ? 'column' : 'row';
                
                $fontName = basename($font['filename'], '.ttf');
                $svg = '<svg width="380" height="240" xmlns="http://www.w3.org/2000/svg">
                    <style>@font-face { font-family: "'.$fontName.'"; src: url("data:font/ttf;base64,'.base64_encode($fontContent).'") format("truetype"); } .watermark {position: absolute; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 60px; transform: rotate(-25deg); font-weight: 900; opacity: 0.04;}</style>
                    <foreignObject width="380" height="240">
                        <div xmlns="http://www.w3.org/1999/xhtml" style="background: #'.$data['palette']['background'].'; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: '.$flexDirection.';">
                            '.$image.'
                            <span style="font-family: \''.$fontName.'\'; font-size: '.$textBox['font_size'].'pt; line-height: 1;">
                                <span style="color: #'.$data['palette']['text1'].'">'.$text1.'</span><span style="color: #'.$data['palette']['text2'].'">'.$text2.'</span>
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
            // echo $e->getMessage();
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
    function calculateTextSize($text, $maxWidth, $maxHeight, $fontFile)
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

    function suggestLogoNames($input) {
        // Remove legal business suffixes
        $suffixes = ['LLC', 'Corp', 'Inc', 'Ltd'];
        $input = preg_replace('/\b(' . implode('|', $suffixes) . ')\b/i', '', $input);
    
        // Remove special characters and ensure the name contains only letters and digits
        $input = preg_replace('/[^a-zA-Z0-9 ]/', '', $input);
        $input = trim($input);
    
        // Split the input into words
        $words = explode(' ', $input);
        
        // Capitalize the first letter of each word
        $capitalizedWords = array_map('ucfirst', $words);
        
        // Combine the words in different ways
        $combinations = [];
        $numWords = count($capitalizedWords);
    
        for ($i = 0; $i < $numWords; $i++) {
            $firstPart = implode('', array_slice($capitalizedWords, 0, $i + 1));
            $secondPart = implode('', array_slice($capitalizedWords, $i + 1));
    
            // Concatenate the two parts with a space
            $combination = $firstPart . ' ' . $secondPart;
    
            // Add the combination to the result array
            $combinations[] = $combination;
        }
    
        return $combinations;
    }
}
