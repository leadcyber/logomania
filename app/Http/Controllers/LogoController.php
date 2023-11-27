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
            $prompt = "The activity of my company is: $desc. Propose 18 short names for it (WITHOUT NUMBERING) composed of a single word (or two words separated by a space). Note that I forbid you from using accented words. Respond with an unordered list, without introduction, and separate each name with a comma.";
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
            if (count($families) <= 0 || count($families) <= 0) break;
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
                if ($family_ratio[$family] == 0) unset($families[array_search($family, $families)]);
                if ($type_ratio[$type] == 0) unset($types[array_search($type, $types)]);
            }
        }

        return $combinations;
    }
}
