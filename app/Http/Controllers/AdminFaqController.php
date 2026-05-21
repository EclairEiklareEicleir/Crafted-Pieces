<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    /**
     * Display a listing of all FAQs
     */
    public function index()
    {
        $faqs = Faq::orderBy('order', 'asc')->paginate(20);

        return view('admin.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created FAQ in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        Faq::create($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ has been created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        $faq->update($validated);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ has been updated successfully.');
    }

    /**
     * Remove the specified FAQ from storage
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ has been deleted successfully.');
    }
}
