<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ExtensionSetupLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExtensionTokenController extends Controller
{
    public function index(Request $req) {
        $u = $req->user();
        $tokens = ExtensionSetupLink::where('user_id', $u->id)
            ->latest()
            ->get(['id','last_four','created_at']);

        return response()->json([
            'tokens' => $tokens,
            'live_url' => $u->live_slug ? route('slot.live', ['slug'=>$u->live_slug]) : null,
            'live_slug_set' => filled($u->live_slug),
        ]);
    }

    public function store(Request $req) {
        $u = $req->user();

        // 1) Plain Token generieren (nur einmal ausgeben)
        $plain = Str::password(64, symbols:false);
        $hash  = hash('sha256', $plain);

        ExtensionSetupLink::create([
            'user_id'   => $u->id,
            'token_hash'=> $hash,
            'last_four' => substr($plain, -4),
        ]);

        // 2) Live-Slug einmalig setzen
        if (blank($u->live_slug)) {
            $slug = substr(hash_hmac('sha256', $plain, config('app.key')), 0, 20);
            $u->forceFill(['live_slug' => $slug])->save();
        }

        return response()->json([
            'token'    => $plain,
            'live_url' => route('slot.live', ['slug'=>$u->live_slug]),
        ], 201);
    }

    public function destroy(Request $req, ExtensionSetupLink $extensionToken) {
        abort_unless($extensionToken->user_id === $req->user()->id, 403);
        $extensionToken->delete();
        return response()->noContent();
    }
}
