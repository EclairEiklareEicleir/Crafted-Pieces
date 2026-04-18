@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-xl rounded-[2rem] border border-[#eadfd7] bg-white p-8 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Account</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Log In</h1>
            <p class="mt-2 text-sm text-[#6f5a51]">Use your customer or staff account credentials.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-[#5d342b]">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-[#5d342b]">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-[#6f5a51]">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[#d9c2b5] text-[#b8745f] focus:ring-[#b8745f]">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="w-full rounded-full bg-[#b8745f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#a96550]">
                    Log In
                </button>
            </form>

            <p class="mt-6 text-sm text-[#6f5a51]">
                No account yet?
                <a href="{{ route('register') }}" class="font-semibold text-[#8d5848] hover:text-[#a86b57]">Create one</a>
            </p>
        </div>
    </section>
@endsection
