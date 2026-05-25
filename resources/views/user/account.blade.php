@extends('layouts.store')

@section('content')
@php
    $user = auth()->user();
@endphp

<section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="space-y-8">

        {{-- ACCOUNT INFORMATION --}}
        <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Profile
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Account Settings
            </h1>

            <p class="mt-3 text-sm text-brand-ink/70">
                Update your customer account details used for orders and communication.
            </p>

            @if (session('status'))
                <p class="mt-6 rounded-2xl border border-brand-border bg-brand-light/40 px-4 py-3 text-sm text-brand-primary">
                    {{ session('status') }}
                </p>
            @endif

            <form method="POST" action="{{ route('account.update') }}" class="mt-8 space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-brand-primary">
                        Full name
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="brand-input"
                    >
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-brand-primary">
                        Email address
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="brand-input"
                    >
                </div>

                <button type="submit"
                        class="brand-btn-primary">
                    Save changes
                </button>
            </form>

        </div>

        {{-- PASSWORD CHANGE --}}
        <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Security
            </p>

            <h2 class="mt-2 text-3xl font-semibold text-brand-primary">
                Change Password
            </h2>

            <p class="mt-3 text-sm text-brand-ink/70">
                Update your password to keep your account secure.
            </p>

            <form method="POST"
                  action="{{ route('account.password.update') }}"
                  class="mt-8 space-y-5">

                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password"
                           class="mb-2 block text-sm font-semibold text-brand-primary">
                        Current password
                    </label>

                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        required
                        class="brand-input"
                    >
                </div>

                <div>
                    <label for="new_password"
                           class="mb-2 block text-sm font-semibold text-brand-primary">
                        New password
                    </label>

                    <input
                        id="new_password"
                        name="new_password"
                        type="password"
                        required
                        class="brand-input"
                    >
                </div>

                <div>
                    <label for="new_password_confirmation"
                           class="mb-2 block text-sm font-semibold text-brand-primary">
                        Confirm new password
                    </label>

                    <input
                        id="new_password_confirmation"
                        name="new_password_confirmation"
                        type="password"
                        required
                        class="brand-input"
                    >
                </div>

                <button type="submit"
                        class="brand-btn-primary">
                    Update password
                </button>

            </form>

        </div>

    </div>

</section>
@endsection