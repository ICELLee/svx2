<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SlotImageController extends Controller
{
    public function show(Slot $slot)
    {
        // Placeholder, falls keine URL
        if (!$slot->image_url) {
            return response()->file(public_path('images/placeholder-slot.png'));
        }

        // Cache-Pfad (hash über URL)
        $hash = sha1($slot->image_url);
        $dir  = "slot_cache/".substr($hash, 0, 2);
        $path = "$dir/$hash";

        // Schon im Cache?
        if (Storage::disk('public')->exists($path)) {
            return $this->fileResponse($path);
        }

        // Remote holen (mit harmlosen Headers; folgt Redirects)
        $res = Http::withHeaders([
            'User-Agent' => 'SVX/1.0 (+https://example.com)',
            'Accept'     => 'image/*,*/*;q=0.8',
            'Referer'    => '', // no-referrer
        ])
            ->timeout(15)
            ->retry(2, 300)
            ->get($slot->image_url);

        if (!$res->ok()) {
            return response()->file(public_path('images/placeholder-slot.png'));
        }

        // Content-Type prüfen
        $ct = strtolower($res->header('content-type', ''));
        if (!Str::startsWith($ct, 'image/')) {
            return response()->file(public_path('images/placeholder-slot.png'));
        }

        // Dateiendung ermitteln
        $ext = match (true) {
            str_contains($ct, 'png')  => 'png',
            str_contains($ct, 'jpeg'),
            str_contains($ct, 'jpg')  => 'jpg',
            str_contains($ct, 'webp') => 'webp',
            default                   => 'img',
        };

        $path .= ".$ext";
        Storage::disk('public')->put($path, $res->body());

        return $this->fileResponse($path, $ct);
    }

    private function fileResponse(string $storagePath, ?string $contentType = null)
    {
        $full = Storage::disk('public')->path($storagePath);
        $headers = [
            'Cache-Control' => 'public, max-age=86400',
        ];
        if ($contentType) $headers['Content-Type'] = $contentType;
        return response()->file($full, $headers);
    }
}
