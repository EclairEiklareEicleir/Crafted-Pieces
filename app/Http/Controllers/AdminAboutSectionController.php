<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use Illuminate\Http\Request;

class AdminAboutSectionController extends Controller
{
    /**
     * Edit the default About section
     */
    public function edit()
    {
        $aboutSection = AboutSection::first();

        if (!$aboutSection) {
            abort(404, 'No About section found. Please contact support.');
        }

        return view('admin.about.edit', compact('aboutSection'));
    }

    /**
     * Update the About section
     */
    public function update(Request $request)
    {
        $aboutSection = AboutSection::first();

        if (!$aboutSection) {
            abort(404, 'No About section found. Please contact support.');
        }

        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $aboutSection->update($validated);

        return redirect()->route('admin.about.edit')
            ->with('success', 'About section has been updated.');
    }
}
