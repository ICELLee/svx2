<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserToolEntitlement extends Model
{
    protected $fillable = ['user_id','tool','expires_at','last_code_id'];
    protected $casts = ['expires_at' => 'datetime'];

    // Canonicalize tool-Namen
    protected static function booted(): void
    {
        static::saving(function (self $m) {
            $m->tool = Str::of($m->tool)->lower()->replace(' ', '')->toString();
        });
    }

    public function user(){ return $this->belongsTo(User::class); }

    /** Scope: aktiv = unbefristet oder in Zukunft gültig */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where(function($q){
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });
    }

    /** true, wenn Zugriff aktuell gültig */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->expires_at) || now()->lte($this->expires_at);
    }

    /**
     * Verlängern/Freischalten.
     * $days=null => unbefristet; ansonsten: addDays ab jetzigem Ablauf (falls in Zukunft) oder ab jetzt.
     */
    public function extend(?int $days): self
    {
        if (is_null($days)) {
            $this->expires_at = null;
            return $this;
        }

        $base = ($this->exists && $this->expires_at && $this->expires_at->gt(now()))
            ? $this->expires_at
            : now();

        $this->expires_at = $base->copy()->addDays($days);
        return $this;
    }
}
