<?php

namespace App\Http\Controllers;

use App\Models\ChatbotFaq;
use Illuminate\Http\Request;

class AdminChatbotFaqController extends Controller
{
    public function index()
    {
        $faqs = ChatbotFaq::latest()->get();

        return view('admin.chatbot.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.chatbot.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string',
        ]);

        ChatbotFaq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'keywords' => $request->keywords,
        ]);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(ChatbotFaq $faq)
    {
        return view('admin.chatbot.edit', compact('faq'));
    }

    public function update(Request $request, ChatbotFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'keywords' => $request->keywords,
        ]);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(ChatbotFaq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}