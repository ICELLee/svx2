<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SlotLiveController extends Controller
{
    public function show(Request $req, string $slug) {
        $user = User::where('live_slug', $slug)->firstOrFail();

        // TODO: Hier holst du die "current slot"-Daten des Users,
        // z.B. aus Redis/DB: CurrentSlot::forUser($user->id) …
        // Für jetzt nur eine simple Blade-Ansicht.
        return view('slot.live', [
            'owner' => $user,
            // 'currentSlot' => $slotData,
        ]);
    }
}
