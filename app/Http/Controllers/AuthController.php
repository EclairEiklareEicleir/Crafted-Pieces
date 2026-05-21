<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        $credentials = [
            'email' => trim($validated['email']),
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', 'Logged in successfully!');
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