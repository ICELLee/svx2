<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\UserToolEntitlement;
use App\Models\SlotUserStat;
use App\Models\CodeRedemption;

// Optional: nur wenn diese Models existieren – sonst bleiben die Zähler 0
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $u   = $request->user();
        $now = now();

        // Entitlements
        $ents = UserToolEntitlement::where('user_id', $u->id)->get(['tool','expires_at']);
        $activeEnts = $ents->filter(fn($e) => is_null($e->expires_at) || $e->expires_at->gte($now))->values();
        $soonDays  = 3;
        $soonEnts  = $activeEnts->filter(fn($e) => $e->expires_at && $e->expires_at->lte($now->copy()->addDays($soonDays)))->values();
        $nextExp   = $activeEnts->filter(fn($e) => $e->expires_at)->sortBy('expires_at')->first();

        // Codes
        $codesRedeemed  = CodeRedemption::where('user_id', $u->id)->count();
        $lastRedeemedAt = CodeRedemption::where('user_id', $u->id)
            ->latest('redeemed_at')
            ->first()?->redeemed_at; // Carbon|null

        // Slot-Stats
        $personalStatsCount = SlotUserStat::where('user_id', $u->id)->count();

        // Optionales: BonusHunt / Tokens, nur wenn Models existieren
        $bonusHuntsCount = class_exists(\App\Models\BonusHunt::class)
            ? \App\Models\BonusHunt::where('user_id', $u->id)->count() : 0;

        $bonusHuntEntriesCount = (class_exists(\App\Models\BonusHuntEntry::class) && class_exists(\App\Models\BonusHunt::class))
            ? \App\Models\BonusHuntEntry::whereHas('hunt', fn($q) => $q->where('user_id', $u->id))->count() : 0;

        $tokensCount = class_exists(\App\Models\ExtensionToken::class)
            ? \App\Models\ExtensionToken::where('user_id', $u->id)->count() : 0;

        $usageHours  = 0; // solange kein Tracking
        $ticketsOpen = 0;

        return Inertia::render('User/Dashboard', [
            'stats' => [
                'myTools'     => $activeEnts->count(),
                'usageHours'  => $usageHours,
                'ticketsOpen' => $ticketsOpen,

                'tools' => $activeEnts->map(fn($e) => [
                    'tool'       => $e->tool,
                    'expires_at' => $e->expires_at?->toIso8601String(),
                ])->values(),
                'toolsSoon' => $soonEnts->map(fn($e) => [
                    'tool'       => $e->tool,
                    'expires_at' => $e->expires_at?->toIso8601String(),
                ])->values(),
                'nextExpiry' => $nextExp ? [
                    'tool'              => $nextExp->tool,
                    'expires_at'        => $nextExp->expires_at?->toIso8601String(),
                    'remaining_seconds' => $nextExp->expires_at?->diffInSeconds($now),
                ] : null,

                'toolsTotal'       => $ents->count(),
                'codesRedeemed'    => $codesRedeemed,
                'lastRedeemedAt'   => $lastRedeemedAt ? $lastRedeemedAt->toIso8601String() : null,
                'slotStats'        => $personalStatsCount,
                'bonusHunts'       => $bonusHuntsCount,
                'bonusHuntEntries' => $bonusHuntEntriesCount,
                'extensionTokens'  => $tokensCount,
                'soonDays'         => $soonDays,
                'now'              => $now->toIso8601String(),
            ],
        ]);
    }

}
