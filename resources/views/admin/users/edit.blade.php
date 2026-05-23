@extends('layouts.admin')

@section('content')

@php
    $isSelf = auth()->id() === $user->id;
@endphp

<div class="grid gap-6 lg:grid-cols-[1fr_20rem]">

    <div class="lg:col-span-2">
        <x-back-button href="{{ route('admin.users.show', $user) }}" label="Back to User Details" />
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
            Edit User
        </p>

        <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
            {{ $user->name }}
        </h1>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-semibold text-brand-primary">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 brand-input" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 brand-input" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-brand-primary">Role</label>

                @if ($isSelf)
                    <input type="text" value="{{ ucfirst($user->role) }}" class="mt-2 brand-input bg-brand-light/30" disabled>
                    <p class="mt-2 text-xs text-brand-ink/60">
                        Your own role cannot be changed here.
                    </p>
                @else
                    <select name="role" class="mt-2 brand-input" required>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Customer</option>
                        <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button class="brand-btn-primary px-6 py-3 text-sm">Save Changes</button>
                <a href="{{ route('admin.users.show', $user) }}" class="brand-btn-secondary px-6 py-3 text-sm">Cancel</a>
            </div>
        </form>
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Account Status
        </h2>

        <div class="mt-4 space-y-4">
            <x-status-badge :status="$user->status ?? 'active'" context="toggle" />

            @if (! $isSelf)
                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button class="brand-btn-secondary w-full px-5 py-3 text-sm">
                        {{ $user->status === 'active' ? 'Disable Account' : 'Enable Account' }}
                    </button>
                </form>
            @else
                <p class="text-sm text-brand-ink/60">
                    Self-deactivation is disabled to avoid accidental lockout.
                </p>
            @endif
        </div>
    </div>

</div>

@endsection