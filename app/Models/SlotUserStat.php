<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotUserStat extends Model
{
    protected $table = 'slot_user_stats';
    protected $fillable = ['user_id','slot_id','personal_bet','personal_win','personal_x'];

    public function slot() { return $this->belongsTo(Slot::class); }
    public function user() { return $this->belongsTo(User::class); }
}
