@extends('layouts.admin')

@section('content')

@php
    $isSelf = auth()->id() === $user->id;
@endphp

<div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">

    <div class="lg:col-span-2">
        <x-back-button href="{{ route('admin.users.index') }}" label="Back to Users" />
    </div>

    <div class="space-y-6">
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                User Profile
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                {{ $user->name }}
            </h1>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Email</p>
                    <p class="mt-2 text-sm text-brand-primary">{{ $user->email }}</p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Role</p>
                    <p class="mt-2 text-sm text-brand-primary">{{ $user->role === 'owner' ? 'Admin' : 'Customer' }}</p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Status</p>
                    <div class="mt-2">
                        <x-status-badge :status="$user->status ?? 'active'" context="toggle" />
                    </div>
                </div>

                <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Created</p>
                    <p class="mt-2 text-sm text-brand-primary">{{ $user->created_at?->format('M d, Y h:i A') ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="brand-btn-primary px-5 py-2 text-sm">Edit User</a>

                @if (! $isSelf)
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button class="brand-btn-secondary px-5 py-2 text-sm">
                            {{ $user->status === 'active' ? 'Disable Account' : 'Enable Account' }}
                        </button>
                    </form>
                @else
                    <span class="brand-btn-secondary px-5 py-2 text-sm opacity-70">Current account protected</span>
                @endif
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Recent Product Orders
            </h2>

            <div class="mt-4 space-y-3">
                @forelse ($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="block rounded-3xl border border-brand-border bg-brand-light/30 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-brand-primary">Order #{{ $order->id }}</p>
                                <p class="text-sm text-brand-ink/60">{{ $order->created_at?->format('M d, Y') ?? '—' }}</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$order->status" context="order" />
                                <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-brand-ink/60">No product orders found.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Recent Custom Orders
            </h2>

            <div class="mt-4 space-y-3">
                @forelse ($recentCustomOrders as $order)
                    <a href="{{ route('admin.custom.show', $order) }}" class="block rounded-3xl border border-brand-border bg-brand-light/30 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-brand-primary">{{ $order->item_type }} #{{ $order->id }}</p>
                                <p class="text-sm text-brand-ink/60">{{ $order->created_at?->format('M d, Y') ?? '—' }}</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$order->status" context="custom" />
                                <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-brand-ink/60">No custom orders found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm lg:min-h-[24rem]">
        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Account Notes
        </h2>

        <div class="mt-4 space-y-4 text-sm text-brand-ink/70">
            <p>
                User ID: <span class="font-semibold text-brand-primary">#{{ $user->id }}</span>
            </p>

            <p>
                Account type: <span class="font-semibold text-brand-primary">{{ $user->role === 'owner' ? 'Admin' : 'Customer' }}</span>
            </p>

            <p>
                Status changes and role updates are validated to prevent accidental self-lockout.
            </p>

            @if ($isSelf)
                <div class="rounded-3xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                    This is your own account. Role and status changes are blocked for safety.
                </div>
            @else
                <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                    You can disable this account or update its role from the edit page.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection
