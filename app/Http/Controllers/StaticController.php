<?php

namespace App\Http\Controllers;

class StaticController extends Controller
{
    public function about()
    {
        $faq = [
            [
                'q' => 'Do you accept custom orders?',
                'a' => 'Yes, we accept custom crochet requests depending on complexity and schedule.',
            ],
            [
                'q' => 'How long does production take?',
                'a' => 'Usually 3–10 days depending on the item.',
            ],
            [
                'q' => 'Do you require full payment upfront?',
                'a' => 'Yes for custom orders unless stated otherwise.',
            ],
        ];

        return view('user.about', compact('faq'));
    }
}