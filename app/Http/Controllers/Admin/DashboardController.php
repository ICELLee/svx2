<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

// Models (nur genutzt, wenn Tabellen existieren)
use App\Models\UserToolEntitlement;
use App\Models\CodeRedemption;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        // --- Users
        $registeredUsers = Schema::hasTable('users')
            ? DB::table('users')->count()
            : 0;

        $onlineUsers = Schema::hasTable('sessions')
            ? DB::table('sessions')
                ->where('last_activity', '>=', $now->copy()->subMinutes(5)->timestamp)
                ->count()
            : 0;

        // --- Tools (eigene Tabelle; falls nicht vorhanden => 0)
        $activeTools = Schema::hasTable('tools')
            ? DB::table('tools')->where('active', 1)->count()
            : 0;

        // --- Umsatz (falls Orders existieren)
        $revenue = Schema::hasTable('orders')
            ? (float) DB::table('orders')->where('status', 'paid')->sum('amount')
            : 0.0;

        // --- Server/Fehler
        $serverErrors = Schema::hasTable('failed_jobs')
            ? DB::table('failed_jobs')->count()
            : 0;

        // Storage
        $root   = base_path();
        $free   = @disk_free_space($root);
        $total  = @disk_total_space($root);
        $storageUsage = ($total && $free !== false)
            ? sprintf('%s / %s', $this->humanBytes($total - $free), $this->humanBytes($total))
            : 'n/a';

        $serverUp = true;

        // --- Entitlements (global)
        $entitlementsActive        = 0;
        $entitlementsExpiringSoon  = 0; // innerhalb 7 Tage
        $entitlementsExpired       = 0;
        $toolsActiveByKey          = [];

        if (Schema::hasTable('user_tool_entitlements')) {
            // aktiv = unbefristet ODER expires_at >= now
            $entitlementsActive = UserToolEntitlement::query()
                ->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', $now);
                })
                ->count();

            $entitlementsExpiringSoon = UserToolEntitlement::query()
                ->whereNotNull('expires_at')
                ->whereBetween('expires_at', [$now, $now->copy()->addDays(7)])
                ->count();

            $entitlementsExpired = UserToolEntitlement::query()
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', $now)
                ->count();

            // Verteilung aktive Entitlements pro Tool
            $toolsActiveByKey = UserToolEntitlement::query()
                ->select('tool', DB::raw('COUNT(*) as c'))
                ->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', $now);
                })
                ->groupBy('tool')
                ->pluck('c', 'tool')
                ->toArray();
        }

        // --- Codes / Redemptions (global)
        $codesRedeemedTotal = 0;
        $codesRedeemed7d    = 0;
        $lastCodeRedeemedAt = null;

        if (Schema::hasTable('code_redemptions')) {
            $codesRedeemedTotal = CodeRedemption::query()->count();

            $codesRedeemed7d = CodeRedemption::query()
                ->where('redeemed_at', '>=', $now->copy()->subDays(7))
                ->count();

            // WICHTIG: kein Aggregat-String → echtes Model für Carbon
            $lastCodeRedeemedAt = optional(
                CodeRedemption::query()->latest('redeemed_at')->first()
            )->redeemed_at; // Carbon|null
        }

        $stats = [
            // deine bestehenden Kacheln
            'onlineUsers'     => $onlineUsers,
            'activeTools'     => $activeTools,
            'revenue'         => $revenue,
            'storageUsage'    => $storageUsage,
            'registeredUsers' => $registeredUsers,
            'serverUp'        => $serverUp,
            'serverErrors'    => $serverErrors,

            // neue sinnvolle Admin-Kennzahlen
            'entitlementsActive'       => $entitlementsActive,
            'entitlementsExpiringSoon' => $entitlementsExpiringSoon,
            'entitlementsExpired'      => $entitlementsExpired,
            'toolsActiveByKey'         => $toolsActiveByKey,

            'codesRedeemedTotal' => $codesRedeemedTotal,
            'codesRedeemed7d'    => $codesRedeemed7d,
            'lastCodeRedeemedAt' => $lastCodeRedeemedAt instanceof Carbon
                ? $lastCodeRedeemedAt->toIso8601String()
                : null,

            'generatedAt' => $now->toIso8601String(),
        ];

        return Inertia::render('Admin/Dashboard', ['stats' => $stats]);
    }

    private function humanBytes(int|float $bytes, int $decimals = 1): string
    {
        $units = ['B','KB','MB','GB','TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, $decimals) . ' ' . $units[$i];
    }
}
