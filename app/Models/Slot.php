<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'name','key','provider','rtp','max_win','image_url',
        'personal_win','personal_bet','personal_x','is_active',
    ];

    protected $casts = [
        'personal_win' => 'decimal:2',
        'personal_bet' => 'decimal:2',
        'personal_x'   => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    // Diese Felder kommen bei JSON-Responses mit
    protected $appends = ['image_proxy'];

    /**
     * Proxy-URL fürs Anzeigen (Admin-geschützt), DB behält den Original-Link.
     */
    public function getImageProxyAttribute(): string
    {
        // Route heißt bei dir: name('media.slot') unter /admin/media/slot/{slot}
        return route('media.slot', ['slot' => $this->id]);
    }

    /**
     * Optional: „Display-URL“ (falls irgendwo direkt gebraucht).
     * Nimmt image_url wenn vorhanden, sonst null.
     */
    public function getImageDisplayUrlAttribute(): ?string
    {
        return $this->image_url ?: null;
    }

    /**
     * Normalisiert eingehende Bild-Links (https, protokoll-relative).
     * DB speichert weiterhin nur die URL – kein Download.
     */
    public function setImageUrlAttribute($value): void
    {
        $url = trim((string) $value);
        if ($url === '') { $this->attributes['image_url'] = null; return; }

        if (str_starts_with($url, '//')) {
            $url = 'https:' . $url;
        }
        // http -> https, Mixed-Content vermeiden
        $url = preg_replace('~^http://~i', 'https://', $url);

        $this->attributes['image_url'] = filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    protected static function booted()
    {
        static::updated(function ($slot) {
            $huntIds = $slot->bonusHuntEntries()->pluck('bonus_hunt_id')->unique()->all();
            if ($huntIds) {
                \App\Models\BonusHunt::whereIn('id', $huntIds)->update(['updated_at' => now()]);
            }
        });
    }
}
