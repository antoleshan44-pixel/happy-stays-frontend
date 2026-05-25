<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $adminPermissions = session('admin_permissions', []);

        if (in_array('*', $adminPermissions) || in_array($permission, $adminPermissions)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
