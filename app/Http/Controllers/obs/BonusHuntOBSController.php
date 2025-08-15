<?php

namespace App\Http\Controllers\obs;

use App\Http\Controllers\Controller;
use App\Models\BonusHunt;
use App\Models\BonusHuntEntry;
use Illuminate\Support\Facades\DB;

class BonusHuntOBSController extends Controller
{
    public function show(string $token)
    {
        $hunt = BonusHunt::where('public_token', $token)
            ->with(['entries.slot'])
            ->firstOrFail();

        abort_if(!$hunt->is_active, 404);

        return view('bonushunt.show', compact('hunt'));
    }

    public function partial(string $token)
    {
        $hunt = BonusHunt::where('public_token', $token)->with(['entries.slot'])->firstOrFail();
        abort_if(!$hunt->is_active, 404);

        return response()
            ->view('bonushunt._entries', compact('hunt'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }


    public function updatedAt(string $token)
    {
        $hunt = BonusHunt::where('public_token', $token)->firstOrFail();
        abort_if(!$hunt->is_active, 404);

        $entries = BonusHuntEntry::where('bonus_hunt_id', $hunt->id);

        $verParts = [
            // Hunt-Stammdaten + Zeit
            optional($hunt->updated_at)?->format('Uu') ?? '0',
            (string) $hunt->start_amount,
            (string) $hunt->name,
            (int) $hunt->is_active,

            // Entries
            optional((clone $entries)->max('updated_at'))?->format('Uu') ?? '0',
            (clone $entries)->count(),
            (clone $entries)->sum(DB::raw('COALESCE(win,0)')),
            (clone $entries)->sum(DB::raw('COALESCE(bet,0)')),
        ];

        $version = sha1(implode('|', $verParts));

        return response()
            ->json(['version' => $version])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }


    public function stats(string $token)
    {
        $hunt = \App\Models\BonusHunt::where('public_token', $token)
            ->with(['entries.slot'])
            ->firstOrFail();

        abort_if(!$hunt->is_active, 404);

        return response()
            ->view('bonushunt._stats', compact('hunt'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
