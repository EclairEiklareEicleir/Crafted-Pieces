<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCustomerLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            return $next($request);
        }

        return redirect()
            ->route('home')
            ->withErrors([
                'auth' => 'Please log in to access custom orders.',
            ])
            ->with('auth_form', 'login');
    }
}