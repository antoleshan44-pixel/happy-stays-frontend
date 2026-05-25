<?php
// File: app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check if user's role is in the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        } elseif ($user->isOwner()) {
            return redirect()->route('owner.dashboard')->with('error', 'Unauthorized access.');
        } else {
            return redirect()->route('customer.dashboard')->with('error', 'Unauthorized access.');
        }
    }
}