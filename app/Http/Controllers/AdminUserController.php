<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private const ROLES = ['user', 'owner'];

    public function index(Request $request)
    {
        $query = User::query()->latest();

        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');

                if (ctype_digit($search)) {
                    $builder->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('role') && in_array($request->string('role')->toString(), self::ROLES, true)) {
            $query->where('role', $request->string('role')->toString());
        }

        if ($request->filled('status') && in_array($request->string('status')->toString(), [User::STATUS_ACTIVE, User::STATUS_INACTIVE], true)) {
            $query->where('status', $request->string('status')->toString());
        }

        $users = $query->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(self::ROLES)],
            'status' => ['nullable', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'] ?? User::STATUS_ACTIVE,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentCustomOrders = CustomOrderRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'recentOrders', 'recentCustomOrders'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $isSelf = Auth::id() === $user->id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => [$isSelf ? 'nullable' : 'required', Rule::in(self::ROLES)],
        ]);

        if ($isSelf) {
            if ($request->filled('role') && $request->string('role')->toString() !== $user->role) {
                return back()->withErrors(['role' => 'You cannot change your own role.'])->withInput();
            }

            $validated['role'] = $user->role;
        }

        if ($user->role === 'owner' && ($validated['role'] ?? $user->role) !== 'owner' && $this->activeOwnerCount() <= 1) {
            return back()->withErrors(['role' => 'At least one active owner account must remain.'])->withInput();
        }

        $user->update([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'role' => $validated['role'] ?? $user->role,
        ]);

        if ($isSelf) {
            Auth::setUser($user->fresh());
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['status' => 'You cannot deactivate your own account.']);
        }

        if ($user->role === 'owner' && $user->status === User::STATUS_ACTIVE && $this->activeOwnerCount() <= 1) {
            return back()->withErrors(['status' => 'At least one active owner account must remain.']);
        }

        $user->update([
            'status' => $user->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    private function activeOwnerCount(): int
    {
        return User::where('role', 'owner')
            ->where('status', User::STATUS_ACTIVE)
            ->count();
    }
}
