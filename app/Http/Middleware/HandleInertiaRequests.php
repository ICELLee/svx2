<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Arr; // optional, wenn du Arr::only nutzen willst

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        $user = $request->user()?->loadMissing('roles:id,name');

        return [
            ...parent::share($request),

            'name'  => config('app.name'),
            'quote' => (function () {
                [$m, $a] = str(\Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                return ['message' => trim($m), 'author' => trim($a)];
            })(),

            'auth' => [
                'user' => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->map->only(['id','name'])->values(),
                ] : null,
            ],

            'ziggy' => [
                ...(new \Tighten\Ziggy\Ziggy)->toArray(),
                'location' => $request->url(),
            ],

            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',

            // Lazy flash so es nur ausgewertet wird, wenn gebraucht
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
        ];
    }

}
