<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            $dashboardRoute = match ($user->role) {
                'admin' => 'admin.dashboard',
                'employer' => 'employer.dashboard',
                'candidate' => 'candidate.dashboard',
                default => null,
            };

            if ($dashboardRoute) {
                return redirect()->route($dashboardRoute);
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
