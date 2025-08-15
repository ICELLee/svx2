<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // ← NEU

class SlotTrackerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tokens = $user->tokens()
            ->select('id','name','abilities','last_used_at','created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($t) {
                $t->abilities = is_array($t->abilities) ? $t->abilities : [];
                return $t;
            });

        // live_url nur liefern, wenn Spalte existiert und Wert da ist
        $liveUrl = null;
        if (Schema::hasColumn('users', 'live_slug') && !empty($user->live_slug)) {
            $liveUrl = route('slot.live', ['slug' => $user->live_slug]);
        }

        return response()->json([
            'tokens'   => $tokens,
            'live_url' => $liveUrl,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['nullable','string','max:255'],
            'abilities' => ['nullable','array'],
        ]);

        $name = $request->input('name') ?: 'extension-'.now()->format('Ymd-His');
        $abilities = $request->input('abilities') ?: ['slot:track'];

        // Token erzeugen
        [$plainTextToken, $tokenModel] = [
            $request->user()->createToken($name, $abilities)->plainTextToken,
            $request->user()->tokens()->latest('id')->first()
        ];

        // live_slug nur setzen, wenn Spalte existiert und noch leer
        $user = $request->user();
        if (Schema::hasColumn('users', 'live_slug') && empty($user->live_slug)) {
            $user->forceFill([
                'live_slug' => substr(hash_hmac('sha256', $plainTextToken, config('app.key')), 0, 20),
            ])->save();
        }

        // live_url nur, wenn vorhanden
        $liveUrl = (Schema::hasColumn('users', 'live_slug') && !empty($user->live_slug))
            ? route('slot.live', ['slug' => $user->live_slug])
            : null;

        return response()->json([
            'id'         => $tokenModel->id ?? null,
            'name'       => $name,
            'abilities'  => $abilities,
            'token'      => $plainTextToken, // nur einmal anzeigen
            'created_at' => $tokenModel->created_at ?? now(),
            'live_url'   => $liveUrl,
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $deleted = $request->user()->tokens()->where('id', $id)->delete();
        abort_if(! $deleted, 404, 'Token nicht gefunden');
        return response()->noContent();
    }
}
