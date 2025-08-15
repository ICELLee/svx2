<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;  // <- WICHTIG!
use Illuminate\Support\Facades\Log;

class SlotApiController extends Controller
{
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
