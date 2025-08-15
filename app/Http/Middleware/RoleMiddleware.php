<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->guest(route('login'));
        }

        // ✅ Einzeiler: prüfe Pivot-Relation, fällt zurück auf 'role'-Spalte oder hasRole()
        $has = false;

        if (method_exists($user, 'roles')) {
            $has = $user->roles()->whereIn('name', $roles)->exists();
        } elseif (method_exists($user, 'hasRole')) {
            foreach ($roles as $r) { if ($user->hasRole($r)) { $has = true; break; } }
        } else {
            $has = isset($user->role) && in_array($user->role, $roles, true);
        }

        if (!$has) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Forbidden'], 403)
                : abort(403);
        }

        return $next($request);
    }

}
