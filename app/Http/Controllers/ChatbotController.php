<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = strtolower($request->message);

        // TEMPORARY TEST RESPONSES

        if (str_contains($message, 'track')) {

            return response()->json([
                'reply' => 'You can track your order from the Track Order page.',
            ]);
        }

        if (str_contains($message, 'custom')) {

            return response()->json([
                'reply' => 'You can submit a custom order through the Custom Order page.',
            ]);
        }

        if (str_contains($message, 'payment')) {

            return response()->json([
                'reply' => 'We currently support PayMongo payments.',
            ]);
        }

        return response()->json([
            'reply' => "Sorry, I couldn't understand your question yet.",
        ]);
    }
}