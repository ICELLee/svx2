<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Models\BonusHunt;
use App\Policies\BonusHuntPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(BonusHunt::class, BonusHuntPolicy::class);

        Route::aliasMiddleware('role', RoleMiddleware::class);
    }
}
