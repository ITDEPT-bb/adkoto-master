<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfanityWord;

class TextController extends Controller
{
    // public function checkProfanity(Request $request)
    // {
    //     $text = $request->input('text');
    //     $profanityWords = ProfanityWord::pluck('word')->toArray();

    //     $foundWords = [];
    //     foreach ($profanityWords as $word) {
    //         if (stripos($text, $word) !== false) {
    //             $foundWords[] = $word;
    //         }
    //     }

    //     return response()->json([
    //         'hasProfanity' => !empty($foundWords),
    //         'foundWords' => $foundWords,
    //     ]);
    // }

    public function checkProfanity(Request $request)
    {
        $text = $request->input('text');
        $profanityWords = ProfanityWord::pluck('word')->toArray();

        $foundWords = [];
        foreach ($profanityWords as $word) {
            if (preg_match("/\b" . preg_quote($word, '/') . "\b/i", $text)) {
                $foundWords[] = $word;
            }
        }

        return response()->json([
            'hasProfanity' => !empty($foundWords),
            'foundWords' => $foundWords,
        ]);
    }

}
