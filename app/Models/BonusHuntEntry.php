<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusHuntEntry extends Model
{
    protected $fillable = [
        'bonus_hunt_id','slot_id','position','bet','win','bonus_bought','bonus_cost',
    ];
    protected $touches = ['hunt'];
    protected $casts = [
        'bet'          => 'decimal:2',
        'win'          => 'decimal:2',
        'bonus_bought' => 'boolean',
        'bonus_cost'   => 'decimal:2',
    ];

    public function hunt(): BelongsTo
    {
        return $this->belongsTo(BonusHunt::class, 'bonus_hunt_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function getXValueAttribute(): ?float
    {
        if ($this->bet && $this->bet > 0 && $this->win !== null) {
            return round($this->win / $this->bet, 2);
        }
        return null;
    }
}
