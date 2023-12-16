<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Family;

use OpenAI\Laravel\Facades\OpenAI;

class TopicController extends Controller
{
    public function index()
    {
        $families = Family::all();

        return view('pages.logos.topic', compact('families'));
    }

    public function store(Request $request)
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
            return redirect()->route('logos.list');
        }

        // Exception handler when openai doesn't work as expected
    }
}
