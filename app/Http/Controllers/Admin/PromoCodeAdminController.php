<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeAdminController extends Controller
{
    // Liste aktiver Codes
    public function index(Request $r) {
        $q = PromoCode::query();
        if ($r->boolean('active_only', true)) {
            $now = now();
            $q->where('is_locked', false)
                ->where(function($w) use ($now){ $w->whereNull('starts_at')->orWhere('starts_at','<=',$now); })
                ->where(function($w) use ($now){ $w->whereNull('expires_at')->orWhere('expires_at','>=',$now); })
                ->whereColumn('used_count','<','max_uses');
        }
        return response()->json($q->orderByDesc('id')->paginate(50));
    }

    // Codes generieren (n Stück)
    public function store(Request $r) {
        $data = $r->validate([
            'count'         => 'required|integer|min:1|max:500',
            'duration_days' => 'required|integer|min:1|max:3650',
            'tools'         => 'required|array|min:1',
            'tools.*'       => 'in:bonushunt,slottracker,tournament,slotrequest',
            'max_uses'      => 'nullable|integer|min:1|max:100000',
            'starts_at'     => 'nullable|date',
            'expires_at'    => 'nullable|date|after_or_equal:starts_at',
            'prefix'        => 'nullable|string|max:16',
        ]);
        $created = [];
        for ($i=0; $i < $data['count']; $i++) {
            $code = strtoupper(($data['prefix'] ?? 'SVX').'-'.Str::random(5).'-'.Str::random(5));
            $created[] = PromoCode::create([
                'code'          => $code,
                'tools'         => $data['tools'],
                'duration_days' => $data['duration_days'],
                'is_locked'     => false,
                'max_uses'      => $data['max_uses'] ?? 1,
                'starts_at'     => $data['starts_at'] ?? null,
                'expires_at'    => $data['expires_at'] ?? null,
                'created_by'    => $r->user()->id,
            ]);
        }
        return response()->json(['codes' => $created], 201);
    }

    public function lock(Request $r, PromoCode $code) {
        $code->update(['is_locked' => true]);
        return response()->noContent();
    }
    public function unlock(Request $r, PromoCode $code) {
        $code->update(['is_locked' => false]);
        return response()->noContent();
    }
}
