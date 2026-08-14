<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
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
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // If user has any of the required roles, let them pass
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // If they don't have permission, abort with 403 Forbidden
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
