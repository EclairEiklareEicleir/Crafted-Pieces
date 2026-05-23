<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |----------------------------------------
    | LOGIN
    |----------------------------------------
    */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $existingUser = User::where('email', trim($validated['email']))->first();

        if ($existingUser && $existingUser->status !== User::STATUS_ACTIVE) {
            return back()
                ->withErrors([
                    'email' => 'Your account has been disabled. Please contact the administrator.',
                ])
                ->withInput()
                ->with('auth_form', 'login');
        }

        $credentials = [
            'email' => trim($validated['email']),
            'password' => $validated['password'],
            'status' => User::STATUS_ACTIVE,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route(
                Auth::user()?->role === 'owner' ? 'admin.dashboard' : 'home'
            )->with('success', 'Logged in successfully!');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid credentials.'
            ])
            ->withInput()
            ->with('auth_form', 'login');
    }

    /*
    |----------------------------------------
    | REGISTER
    |----------------------------------------
    */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'status' => User::STATUS_ACTIVE,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Account created successfully!');
    }

    /*
    |----------------------------------------
    | LOGOUT
    |----------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Logged out successfully.');
    }
}