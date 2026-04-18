@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-xl rounded-[2rem] border border-[#eadfd7] bg-white p-8 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Account</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Create Account</h1>
            <p class="mt-2 text-sm text-[#6f5a51]">Customer accounts can place orders and view their own order history.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-[#5d342b]">Full name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-[#5d342b]">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
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
                        autocomplete="new-password"
                        class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#5d342b]">Confirm password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-2xl border border-[#e7d6cb] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                </div>

                <button type="submit" class="w-full rounded-full bg-[#b8745f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#a96550]">
                    Register
                </button>
            </form>

            <p class="mt-6 text-sm text-[#6f5a51]">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-[#8d5848] hover:text-[#a86b57]">Log in</a>
            </p>
        </div>
    </section>
@endsection
