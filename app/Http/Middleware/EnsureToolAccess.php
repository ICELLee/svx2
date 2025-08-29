<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureToolAccess
{
    public function handle(Request $request, Closure $next, string $tool)
    {
        $user = $request->user();
        if (!$user) abort(401);

        if (!$user->hasTool($tool)) {
            abort(403, 'Kein Zugriff: nicht freigeschaltet oder abgelaufen.');
        }

        return $next($request);
    }
}
