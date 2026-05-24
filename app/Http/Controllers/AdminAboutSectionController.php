<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminAboutSectionController extends Controller
{
    /**
     * Edit the default About section
     */
    public function edit()
    {
        $aboutSection = AboutSection::first() ?? new AboutSection([
            'heading' => '',
            'content' => '',
        ]);

        $faqs = Faq::orderBy('order', 'asc')
            ->orderBy('id')
            ->get();

        return view('admin.about.edit', compact('aboutSection', 'faqs'));
    }

    /**
     * Update the About section
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $aboutSection = AboutSection::first() ?? new AboutSection();
        $aboutSection->fill($validated)->save();

        return redirect(route('admin.about.edit') . '#about-content')
            ->with('success', 'About section has been updated.');
    }
}
