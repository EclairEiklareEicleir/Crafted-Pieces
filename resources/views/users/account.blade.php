@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Profile</p>
            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">Account Settings</h1>
            <p class="mt-3 text-sm text-[#6f5a51]">Update your customer account details used for orders and communication.</p>

            @if (session('status'))
                <p class="mt-6 rounded-2xl border border-[#e7d6cb] bg-[#fcfaf8] px-4 py-3 text-sm text-[#5d342b]">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('account.update') }}" class="mt-8 space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-[#5d342b]">Full name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-[#5d342b]">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="email"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm text-[#3f2a22] focus:border-[#b8745f] focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">
                    Save changes
                </button>
            </form>
        </div>
    </section>
@endsection
