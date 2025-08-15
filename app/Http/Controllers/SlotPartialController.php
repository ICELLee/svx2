<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SlotPartialController extends Controller
{
    public function show(string $key)
    {
        // tolerant auflösen: key, slug oder normalisierter Name
        $norm = Str::of($key)->lower()->replace(['_', ' '], '-');

        $slot = Slot::query()
            ->where('key', $key)
            ->orWhere('slug', $key)
            ->orWhereRaw('LOWER(REPLACE(REPLACE(name,"_","-")," ","-")) = ?', [$norm])
            ->first();

        // Falls nichts gefunden: Partial mit $slot = null zurückgeben (kein 500)
        return view('extension.slot._slotbox', [
            'slot' => $slot ?: null
        ]);
    }
}
