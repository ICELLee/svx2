<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BonusHunt;
use App\Models\BonusHuntEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BonusHuntApiController extends Controller
{
    public function index(Request $req)
    {
        return BonusHunt::where('user_id', $req->user()->id)
            ->withCount('entries')
            ->orderByDesc('updated_at')
            ->paginate(15);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'         => ['required','string','max:255'],
            'start_amount' => ['required','numeric','min:0'],
            'is_active'    => ['boolean'],
        ]);

        $hunt = BonusHunt::create([
            'user_id'      => $req->user()->id,
            'name'         => $data['name'],
            'start_amount' => $data['start_amount'],
            'is_active'    => $data['is_active'] ?? false,
        ]);

        return response()->json($hunt, 201);
    }

    public function update(Request $req, BonusHunt $hunt)
    {
        $this->authorize('update', $hunt);

        $data = $req->validate([
            'name'         => ['sometimes','string','max:255'],
            'start_amount' => ['sometimes','numeric','min:0'],
            'is_active'    => ['sometimes','boolean'],
        ]);

        $hunt->fill($data)->save();
        $hunt->touch(); // für OBS-Refresh
        return $hunt->fresh()->loadCount('entries');
    }

    public function destroy(Request $req, BonusHunt $hunt)
    {
        $this->authorize('delete', $hunt);
        $hunt->delete();
        // KEIN touch() mehr nach delete
        return response()->noContent();
    }

    // ===== Entries =====

    public function entries(Request $req, BonusHunt $hunt)
    {
        $this->authorize('view', $hunt);

        // WICHTIG: KEIN touch() hier, sonst Endlos-Refresh!
        return $hunt->entries()
            ->with(['slot:id,name,provider,image_url'])
            ->orderBy('position')
            ->get();
    }

    public function addEntry(Request $req, BonusHunt $hunt)
    {
        $this->authorize('update', $hunt);

        $data = $req->validate([
            'slot_id'      => ['required','exists:slots,id'],
            'position'     => ['nullable','integer','min:1'],
            'bet'          => ['required','numeric','min:0'],
            'win'          => ['nullable','numeric','min:0'],
            'x_value'      => ['nullable','numeric','min:0'],     // hinzugefügt
            'bonus_bought' => ['boolean'],
            'bonus_cost'   => ['nullable','numeric','min:0'],
        ]);

        // sichere Positions-Berechnung
        $maxPos = (int) ($hunt->entries()->max('position') ?? 0);
        $data['position'] = $data['position'] ?? ($maxPos + 1);

        $entry = $hunt->entries()->create($data);

        $hunt->touch(); // OBS-Refresh auslösen
        return response()->json($entry->load('slot:id,name,provider,image_url'), 201);
    }

    public function updateEntry(Request $req, BonusHunt $hunt, BonusHuntEntry $entry)
    {
        $this->authorize('update', $hunt);
        if ($entry->bonus_hunt_id !== $hunt->id) abort(404);

        $data = $req->validate([
            'position'     => ['nullable','integer','min:1'],
            'bet'          => ['nullable','numeric','min:0'],
            'win'          => ['nullable','numeric','min:0'],
            'x_value'      => ['nullable','numeric','min:0'],     // hinzugefügt
            'bonus_bought' => ['nullable','boolean'],
            'bonus_cost'   => ['nullable','numeric','min:0'],
        ]);

        $entry->fill($data)->save();

        $hunt->touch(); // OBS-Refresh
        return $entry->fresh()->load('slot:id,name,provider,image_url');
    }

    public function deleteEntry(Request $req, BonusHunt $hunt, BonusHuntEntry $entry)
    {
        $this->authorize('update', $hunt);
        if ($entry->bonus_hunt_id !== $hunt->id) abort(404);

        $entry->delete();
        $hunt->touch(); // nach dem Löschen touchen
        return response()->noContent();
    }

    public function link(BonusHunt $hunt)
    {
        $this->authorize('view', $hunt);

        if (empty($hunt->public_token)) {
            $hunt->public_token = (string) Str::ulid();
            $hunt->save();
        }

        return response()->json([
            'public_url'  => route('bonushunt.public',         ['token' => $hunt->public_token]),
            'partial_url' => route('bonushunt.public.partial', ['token' => $hunt->public_token]),
            'updated_url' => route('bonushunt.public.updated', ['token' => $hunt->public_token]),
            'is_active'   => (bool) $hunt->is_active,
            'name'        => $hunt->name,
        ]);
    }
}
