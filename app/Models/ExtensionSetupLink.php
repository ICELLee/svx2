<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionSetupLink extends Model
{
    protected $fillable = [
        'ulid','user_id','token_id','expires_at','used_at', 'last_four', 'token_hash',
    ];
    protected $hidden = ['token_hash'];
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function token() {
        return $this->belongsTo(\Laravel\Sanctum\PersonalAccessToken::class, 'token_id');
    }

    public function isExpired(): bool {
        return $this->expires_at?->isPast() ?? true;
    }

    public function isUsed(): bool {
        return !is_null($this->used_at);
    }
}
