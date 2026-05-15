<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return view('user.account', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        // dd(Auth::user());
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        /** @var \App\Models\User $user */
        $user->update($validated);
        Auth::setUser($user->fresh());
        
        return back()->with('status', 'Account updated successfully.');
    }
}