<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BonusHunt extends Model
{
    protected $fillable = [
        'user_id','name','start_amount','is_active', // 'public_token' absichtlich NICHT hier
    ];

    protected $casts = [
        'start_amount' => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    // Wenn du die URL im JSON brauchst:
    protected $appends = ['public_url'];

    protected static function booted(): void
    {
        static::creating(function (self $hunt) {
            // Nur setzen, wenn noch leer — vor dem INSERT!
            if (empty($hunt->public_token)) {
                // ULID ist kurz+eindeutig (26 Zeichen). Alternativ: Str::random(40)
                do {
                    $token = (string) Str::ulid();
                } while (self::where('public_token', $token)->exists());
                $hunt->public_token = $token;
            }
        });
    }

    public function entries(): HasMany
    {
        return $this->hasMany(BonusHuntEntry::class, 'bonus_hunt_id')
            ->with(['slot:id,name,provider'])   // kleiner N+1-Fix
            ->orderBy('position');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Nur noch als Helper – KEIN save() mit Seiteneffekt hier
    public function ensurePublicToken(): string
    {
        if (!$this->public_token) {
            $this->public_token = (string) Str::ulid();
            // Caller entscheidet, ob gespeichert wird
        }
        return $this->public_token;
    }

    public function getPublicUrlAttribute(): ?string
    {
        return $this->public_token
            ? route('bonushunt.public', ['token' => $this->public_token])
            : null;
    }
}
