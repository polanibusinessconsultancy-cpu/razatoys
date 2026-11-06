<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AdminPermission;
use App\Models\Role;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $page): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('sign-in')->with('error', 'Please login to access this page');
        }

        $user = Auth::user();

        // If the user has the Admin role, they have complete access to all pages
        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        return abort(403, 'Unauthorized action.');
    }
}
