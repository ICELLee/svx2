<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\CodeRedemption;
use App\Models\UserToolEntitlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedeemCodeController extends Controller
{
    public function redeem(Request $r) {
        $data = $r->validate(['code' => 'required|string|max:64']);
        $user = $r->user();

        return DB::transaction(function () use ($data, $user) {
            $now = now();

            // Code holen & prüfen
            $code = PromoCode::where('code', $data['code'])->lockForUpdate()->first();
            if (!$code) return response()->json(['message'=>'Code ungültig'], 404);
            if ($code->is_locked) return response()->json(['message'=>'Code gesperrt'], 422);
            if ($code->starts_at && $now->lt($code->starts_at)) return response()->json(['message'=>'Code noch nicht gültig'], 422);
            if ($code->expires_at && $now->gt($code->expires_at)) return response()->json(['message'=>'Code abgelaufen'], 422);
            if ($code->used_count >= $code->max_uses) return response()->json(['message'=>'Code verbraucht'], 422);

            // Schon eingelöst von diesem User?
            $already = CodeRedemption::where('promo_code_id',$code->id)->where('user_id',$user->id)->exists();
            if ($already) return response()->json(['message'=>'Code von diesem Benutzer bereits eingelöst'], 422);

            // Entitlements verlängern/setzen (Laufzeit addieren)
            $addedUntil = [];
            foreach ((array)$code->tools as $tool) {
                $ent = UserToolEntitlement::firstOrNew(['user_id'=>$user->id,'tool'=>$tool]);
                $base = $ent->exists && $ent->expires_at && $ent->expires_at->gt($now) ? $ent->expires_at : $now;
                $ent->expires_at = (clone $base)->addDays($code->duration_days);
                $ent->last_code_id = $code->id;
                $ent->save();
                $addedUntil[$tool] = $ent->expires_at->toIso8601String();
            }

            // Redemption & Counter
            CodeRedemption::create(['promo_code_id'=>$code->id,'user_id'=>$user->id,'redeemed_at'=>$now]);
            $code->increment('used_count');

            return response()->json([
                'message' => 'Code eingelöst',
                'tools'   => $code->tools,
                'until'   => $addedUntil,
            ], 200);
        });
    }
}
