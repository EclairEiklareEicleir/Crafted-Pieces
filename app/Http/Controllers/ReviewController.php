<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
            'is_anonymous' => ['nullable'],
        ], [
            'rating.required' => 'Please select a rating from 1 to 5 yarns.',
            'rating.integer' => 'Please select a valid rating from 1 to 5 yarns.',
            'rating.min' => 'Please select a valid rating from 1 to 5 yarns.',
            'rating.max' => 'Please select a valid rating from 1 to 5 yarns.',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => trim($validated['comment']),
            'is_anonymous' => $request->boolean('is_anonymous'),
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
