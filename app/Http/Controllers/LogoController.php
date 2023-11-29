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
        $family1_id = $topic['family1_id'];
        $family2_id = $topic['family2_id'];

        // Get company name suggestions from openAI
        $suggestions = "";
        if (!$company_name && $desc) {
            $prompt = "The activity of my company is: $desc. Propose 20 short names for it (WITHOUT NUMBERING) consisting of two words separated by a space. Note that I forbid you from using accented words. Respond with an unordered list, without introduction, and separate each name with a comma.";
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $suggestions = $result->choices[0]->message->content;
        }

        $user_id = null;
        if (auth()->check()) {
            $user_id = auth()->user()->id;
        }

        // Store topic to database
        $topic = Topic::create([
            'user_id' => $user_id,
            'company_name' => $company_name,
            'desc' => $desc,
            'family1_id' => $family1_id,
            'family2_id' => $family2_id,
            'suggestions' => $suggestions,
        ]);

        // Store topic into session to track the progress
        session(['topic' => $topic]);

        // Redirect to logo generation page
        return redirect()->route('logo.generate');
    }

    public function generate()
    {
        $topic = session('topic');

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
                ->find($topic['family1_id'])
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
        if ($topic['company_name']) {
            $logoNames = $this->suggestLogoNames($topic['company_name']);
        }
        else {
            $logoNames = explode(',', $topic['suggestions']);
            $logoNames = array_map(function ($name) {
                return trim($name);
            }, $logoNames);
        }
        $logos = [];
        foreach ($combinations as $index => $combination) {
            $text = $logoNames[array_rand($logoNames)];
            if ($text)
                $logo = $this->generateLogo($index, $combination, $text);
                if ($logo) {
                    $logos[] = $logo;
                }
        }

        return view('pages.logo.generate', compact('logos'));
    }

    public function generateLogo($output, $data, $text)
    {
        $svgMaxWidth = 320;
        $svgMaxHeight = 240;
        $iconMaxWidth = 50;
        $iconMaxHeight = 50;
        try {
            $words = explode(' ', $text);
            $text1 = $words[0];
            $text2 = isset($words[1]) ? $words[1] : '';

            // Estimate font size
            $textWidth = $svgMaxWidth;
            $textHeight = $svgMaxHeight;
            if ($data['type'] == 'text_icon_top') {
                $textWidth = $svgMaxWidth;
                $textHeight = $svgMaxHeight - $iconMaxHeight;
            }
            else if ($data['type'] == 'text_icon_left') {
                $textWidth = $svgMaxWidth - $iconMaxWidth;
                $textHeight = $svgMaxHeight;
            }

            $font = $data['font'];
            $icon = $data['icon'];
            $fontFile = env('PATH_FONTS_DIR').'/'.$font['family_id'].'/'.$font['filename'];
            $iconFile = env('PATH_ICONS_DIR').'/'.$font['family_id'].'/'.$icon['type'].'/'.$icon['filename'];

            $textBox = $this->calculateTextSize($text1.$text2, $textWidth, $textHeight, $fontFile);

            if (file_exists($fontFile)) {
                // Font content as a string
                $fontContent = file_get_contents($fontFile);

                // SVG icon content as a string
                $iconContent = file_get_contents($iconFile);

                // Change the color of icon
                if ($icon['type'] == 'fillable') {
                    $iconContent = preg_replace('/fill="#[0-9a-fA-F]{6}"/', 'fill="#' . $data['palette']['icon'] . '"', $iconContent);
                }

                // Icon position styles
                $margin = $data['type'] == 'text_icon_top' ? 'margin-bottom: 5px;' : 'margin-right: 5px;';
                $image = $data['type'] == 'text_only' ? '' : '<img style="'.$margin.' width: 50px; height: 50px;" src="data:image/svg+xml;base64,'.base64_encode($iconContent).'"></img>';
                $flexDirection = $data['type'] == 'text_icon_top' ? 'column' : 'row';
                

                $svg = '<svg width="360" height="260" xmlns="http://www.w3.org/2000/svg">
                    <style>@font-face { font-family: font'.$output.'; src: url("data:font/ttf;base64,'.base64_encode($fontContent).'") format("truetype"); }</style>
                    <foreignObject width="360" height="260">
                        <div xmlns="http://www.w3.org/1999/xhtml" style="background: #'.$data['palette']['background'].'; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: '.$flexDirection.';">
                            '.$image.'
                            <span style="font-family: font'.$output.'; font-size: '.$textBox['font_size'].'pt; line-height: 1;">
                                <span style="color: #'.$data['palette']['text1'].'">'.$text1.'</span><span style="color: #'.$data['palette']['text2'].'">'.$text2.'</span>
                            </span>
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
