<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is logged in, check their account status
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Handle Suspended Accounts
            if ($user->status === 'suspended') {
                Auth::logout(); // Forcefully log them out immediately
                
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been suspended by an administrator.'
                ]);
            }

            // 2. Handle Pending Accounts
            if ($user->status === 'pending') {
                Auth::logout(); // Forcefully log them out immediately
                
                return redirect()->route('login')->withErrors([
                    'email' => 'Your registration is pending administrator approval.'
                ]);
            }
        }

        // If they are approved (or not logged in at all for public pages), let them proceed
        return $next($request);
    }
}