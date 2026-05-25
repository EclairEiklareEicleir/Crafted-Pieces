@extends('layouts.admin')

@section('content')

<div class="grid gap-6 lg:grid-cols-[1fr_20rem]">

    <div class="lg:col-span-2">
        <x-back-button href="{{ route('admin.users.index') }}" label="Back to Users" />
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
            New Account
        </p>

        <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
            Add User
        </h1>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 space-y-5" data-preserve-scroll>
            @csrf

            <div>
                <label class="text-sm font-semibold text-brand-primary">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-2 brand-input" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-brand-primary">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-2 brand-input" required>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-brand-primary">Password</label>
                    <input type="password" name="password" class="mt-2 brand-input" required minlength="8" autocomplete="new-password">
                </div>

                <div>
                    <label class="text-sm font-semibold text-brand-primary">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 brand-input" required minlength="8" autocomplete="new-password">
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-brand-primary">Role</label>
                    <select name="role" class="mt-2 brand-input" required>
                        <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Customer</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-brand-primary">Status</label>
                    <select name="status" class="mt-2 brand-input" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button class="brand-btn-primary px-6 py-3 text-sm">Create User</button>
                <a href="{{ route('admin.users.index') }}" class="brand-btn-secondary px-6 py-3 text-sm">Cancel</a>
            </div>
        </form>
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Access Notes
        </h2>

        <div class="mt-4 space-y-4 text-sm text-brand-ink/70">
            <p>
                Admin accounts use the existing <span class="font-semibold text-brand-primary">owner</span> role and can access this panel.
            </p>

            <p>
                Customer accounts use the existing <span class="font-semibold text-brand-primary">user</span> role and keep the normal storefront access.
            </p>

            <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                Email addresses must be unique and passwords are stored through Laravel's hashed password handling.
            </div>
        </div>
    </div>

</div>

@endsection
