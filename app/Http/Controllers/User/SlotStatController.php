<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\SlotUserStat;
use Illuminate\Http\Request;

class SlotStatController extends Controller
{
    public function upsert(Request $request)
    {
        $data = $request->validate([
            'key' => ['required','string'],
            'bet' => ['required','numeric','gt:0'],
            'win' => ['required','numeric','gte:0'],
        ]);

        $slot = Slot::where('key', $data['key'])->firstOrFail();
        $x = $data['win'] / $data['bet'];

        SlotUserStat::updateOrCreate(
            ['user_id' => $request->user()->id, 'slot_id' => $slot->id],
            [
                'personal_bet' => $data['bet'],
                'personal_win' => $data['win'],
                'personal_x'   => $x,
            ]
        );

        return response()->json(['ok' => true, 'x' => $x]);
    }

    public function get(Request $request, string $key)
    {
        $slot = Slot::where('key', $key)->firstOrFail();

        $stat = SlotUserStat::where('user_id',$request->user()->id)
            ->where('slot_id',$slot->id)
            ->first();

        return response()->json([
            'bet' => (float)($stat->personal_bet ?? 0),
            'win' => (float)($stat->personal_win ?? 0),
            'x'   => (float)($stat->personal_x ?? 0),
        ]);
    }
}
