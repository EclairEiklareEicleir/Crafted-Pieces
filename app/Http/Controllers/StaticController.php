<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Faq;

class StaticController extends Controller
{
    public function about()
    {
        // Get active FAQs ordered by order field, fallback to empty array
        $faq = Faq::where('active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn($item) => ['q' => $item->question, 'a' => $item->answer])
            ->toArray();

        $aboutSection = AboutSection::latest()->first();

        return view('user.about', compact('faq', 'aboutSection'));
    }

    public function privacy()
    {
        return view('user.privacy-policy');
    }

    public function terms()
    {
        return view('user.terms-of-service');
    }
}