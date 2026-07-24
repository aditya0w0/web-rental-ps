<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        // Jika tidak ada role yang diberikan (mis. middleware 'admin'), default ke 'admin'
        if (empty($roles)) {
            $roles = ['admin'];
        }
        
        foreach ($roles as $role) {
            if ($user->role === $role || ($role === 'admin' && in_array($user->role, ['owner', 'admin'], true))) {
                return $next($request);
            }
        }

        if (in_array($user->role, ['owner', 'admin'], true)) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Admin accounts use the admin dashboard for management.');
        }

        abort(403, 'Unauthorized action.');
    }
}
