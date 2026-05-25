<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->isOwner()) {
                    return redirect()->route('owner.dashboard');
                } else {
                    return redirect()->route('customer.dashboard');
                }
            }
        }

        return $next($request);
    }
}