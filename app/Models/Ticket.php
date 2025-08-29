<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','title','message','reason','products',
        'priority','status','extra_info','claimed_by'
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function claimedBy() {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    public function messages() {
        return $this->hasMany(TicketMessage::class);
    }
}
