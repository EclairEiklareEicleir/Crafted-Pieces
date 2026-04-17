@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Custom work</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">Request a quotation</h1>
                <p class="mt-4 text-[#6f5a51]">Tell us what you want and we will reply with a price, timeline, and material notes.</p>
                <div class="mt-8 rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                    <h2 class="font-display text-2xl font-semibold text-[#4d3028]">What to include</h2>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-[#6f5a51]">
                        <li>- Item type and size</li>
                        <li>- Preferred colors and theme</li>
                        <li>- Deadline or event date</li>
                        <li>- Reference photos if available</li>
                    </ul>
                </div>
            </div>
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <form class="grid gap-4 sm:grid-cols-2" method="POST" action="#">
                    @csrf
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="name" placeholder="Full name">
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="email" placeholder="Email address">
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3 sm:col-span-2" name="item_type" placeholder="Item type">
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="design_theme" placeholder="Design theme">
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="preferred_size" placeholder="Preferred size">
                    <textarea class="rounded-2xl border border-[#eadfd7] px-4 py-3 sm:col-span-2" name="description" rows="5" placeholder="Describe your idea"></textarea>
                    <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 sm:col-span-2">Send quotation request</button>
                </form>
            </div>
        </div>
    </section>
@endsection
