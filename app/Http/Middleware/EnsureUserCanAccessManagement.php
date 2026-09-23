<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessManagement
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $management = null): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $requested = $management ?? $request->route()?->getName();

        if ($requested === 'dashboard' && ! $request->user()->isAdmin()) {
            abort(403, 'Akses dashboard hanya untuk admin.');
        }

        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        if ($request->user()->management !== $requested) {
            abort(403, 'Akses ditolak untuk manajemen ini.');
        }

        return $next($request);
    }
}
