<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotFaq;

class ChatbotController extends Controller
{
    private function fallbackReplies($input)
    {
        $input = strtolower($input);

        if (str_contains($input, 'order')) {
            return "I can help you with orders 😊. Are you trying to track an order or view details?";
        }

        if (str_contains($input, 'custom')) {
            return "Custom orders are one of our specialties 🎨. Do you want to know how to place one or check an existing request?";
        }

        if (str_contains($input, 'payment') || str_contains($input, 'pay')) {
            return "We currently support PayMongo for secure payments 💳. Do you need help with checkout?";
        }

        if (str_contains($input, 'hello') || str_contains($input, 'hi')) {
            return "Hi there 👋 How can I help you today?";
        }

        return null;
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $input = strtolower($request->message);
        $input = preg_replace('/[^a-z0-9\s]/', '', $input);
        $inputTokens = array_filter(explode(' ', $input));

        $faqs = ChatbotFaq::where('is_active', true)->get();

        $bestMatch = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {

            $question = strtolower($faq->question);
            $question = preg_replace('/[^a-z0-9\s]/', '', $question);

            $questionTokens = array_filter(explode(' ', $question));

            $keywords = array_filter(
                array_map('trim', explode(',', strtolower($faq->keywords ?? '')))
            );

            $score = 0;

            if (str_contains($input, $question)) {
                $score += 8;
            }

            if (str_contains($question, $input)) {
                $score += 6;
            }

            foreach ($keywords as $keyword) {
                if (str_contains($input, $keyword)) {
                    $score += 4;
                }
            }

            $matches = 0;

            foreach ($inputTokens as $token) {
                if (in_array($token, $questionTokens)) {
                    $matches++;
                }
            }

            if (count($questionTokens) > 0) {
                $score += ($matches / count($questionTokens)) * 5;
            }

            similar_text($input, $question, $percent);
            $score += ($percent / 100) * 3;

            $lengthDiff = abs(strlen($input) - strlen($question));

            if ($lengthDiff > 40) {
                $score -= 1;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $faq;
            }
        }

        if (!$bestMatch || $bestScore < 4) {

            $fallback = $this->fallbackReplies($input);

            return response()->json([
                'reply' => $fallback ?? "Hmm 🤔 I’m not fully sure yet, but I can help you with orders, custom requests, or payments. What would you like to know?"
            ]);
        }

        $answer = $this->formatResponse($bestMatch->answer, $input);

        return response()->json([
            'reply' => $answer
        ]);
    }

    private function formatResponse($answer, $input)
    {
        $input = strtolower($input);

        if (str_contains($input, 'how') || str_contains($input, 'what') || str_contains($input, 'why')) {
            return "Sure! " . $answer . " 😊";
        }

        if (str_contains($input, 'order')) {
            return "Got it — " . $answer . " 🚚";
        }

        if (str_contains($input, 'payment') || str_contains($input, 'pay')) {
            return $answer . " 💳 Let me know if you want help with checkout.";
        }

        return $answer . " 😊 Let me know if you need anything else!";
    }
}