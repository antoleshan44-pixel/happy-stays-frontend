<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SpringBootAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user exists in session (from Spring Boot login)
        if (!session('user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
