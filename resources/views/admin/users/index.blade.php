@extends('layouts.admin')

@section('content')

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Admin Directory
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                User Management
            </h2>

            <p class="mt-2 text-sm text-brand-ink/60">
                Review customer accounts, roles, and account status.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
            <a href="{{ route('admin.history.index') }}" class="brand-btn-secondary px-5 py-2 text-sm">
                View Order History
            </a>
        </div>
    </div>

    <form method="GET" id="filterForm" class="mt-6 grid gap-3 lg:grid-cols-[minmax(0,1fr)_12rem_12rem]">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by name or email"
               class="brand-input py-3"
               oninput="submitFilter()">

        <select name="role" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Roles</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer</option>
            <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
        </select>

        <select name="status" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Disabled</option>
        </select>
    </form>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">
                <tr>
                    <th class="py-3 pr-4">ID</th>
                    <th class="py-3 pr-4">User</th>
                    <th class="py-3 pr-4">Email</th>
                    <th class="py-3 pr-4">Role</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Created</th>
                    <th class="py-3 pr-4">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-brand-border">
                @forelse ($users as $user)
                    <tr>
                        <td class="py-4 pr-4 font-medium text-brand-primary">
                            #{{ $user->id }}
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/75">
                            <div class="flex flex-col">
                                <span class="font-medium text-brand-primary">
                                    {{ $user->name }}
                                    @if (auth()->id() === $user->id)
                                        <span class="ml-2 rounded-full bg-brand-light px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-primary">You</span>
                                    @endif
                                </span>
                                <span class="text-xs text-brand-ink/55">
                                    {{ $user->role === 'owner' ? 'Admin owner' : 'Customer account' }}
                                </span>
                            </div>
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/75">
                            {{ $user->email }}
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$user->role" context="generic" :label="ucfirst($user->role)" />
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$user->status ?? 'active'" context="toggle" />
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">
                            {{ $user->created_at?->format('M d, Y') ?? '—' }}
                        </td>

                        <td class="py-4 pr-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                                    View
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-primary transition hover:border-brand-primary hover:bg-brand-light/60">
                                    Edit
                                </a>

                                @if (auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-ink transition hover:border-brand-secondary hover:bg-brand-light/40">
                                            {{ $user->status === 'active' ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-brand-border bg-brand-light/40 px-3 py-2 text-xs font-semibold text-brand-ink/55">
                                        Protected
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-brand-ink/60">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>

<script>
let filterTimeout

function submitFilter() {
    clearTimeout(filterTimeout)
    filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit()
    }, 250)
}
</script>

@endsection