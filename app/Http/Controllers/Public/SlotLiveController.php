<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SlotLiveController extends Controller
{
    public function show(string $slug)
    {
        $user = User::where('live_slug', $slug)->firstOrFail();

        $cacheKey = "slot:current:user:{$user->id}";
        $key = Cache::get($cacheKey);

        $slot = null;
        if ($key) {
            $slot = Slot::where('key', $key)->first();
        }

        return view('extension.slot', [
            'owner' => $user,
            'slot'  => $slot,
            'slug'  => $slug,
        ]);
    }


    public function current(string $slug)
    {
        $user = User::where('live_slug', $slug)->firstOrFail();
        $key = Cache::get("slot:current:user:{$user->id}");

        return response()->json(['key' => $key]);
    }
}
