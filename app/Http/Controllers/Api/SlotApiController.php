<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;  // <- WICHTIG!
use Illuminate\Support\Facades\Log;

class SlotApiController extends Controller
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

        // Debug-Ausgabe
        if (!$slot) {
            return response()->json([
                'debug' => 'Kein Slot gefunden',
                'cacheKey' => $cacheKey,
                'key' => $key,
            ]);
        }

        return view('extension.slot', [
            'owner' => $user,
            'slot'  => $slot,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'key' => ['required','string','max:255'],
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['ok'=>false,'error'=>'unauthenticated'], 401);
            }

            // Optional: Sanctum-Abilities prüfen (falls du beim Token z.B. ['slot:track'] gesetzt hast)
            if (method_exists($user->currentAccessToken(), 'can')
                && !$user->tokenCan('slot:track')) {
                return response()->json(['ok'=>false,'error'=>'forbidden'], 403);
            }

            $cacheKey = "slot:current:user:{$user->id}";
            Cache::put($cacheKey, $data['key'], now()->addHours(2));

            return response()->json(['ok'=>true, 'key'=>$data['key']]);
        } catch (\Throwable $e) {
            Log::error('Slot store failed', [
                'msg' => $e->getMessage(),
                'line'=> $e->getLine(),
                'file'=> $e->getFile(),
            ]);
            return response()->json([
                'ok'=>false,
                'error'=>$e->getMessage(),
            ], 500);
        }
    }
}
