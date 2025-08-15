<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model {
    protected $fillable = ['code','tools','duration_days','is_locked','max_uses','used_count','starts_at','expires_at','created_by'];
    protected $casts = ['tools'=>'array','is_locked'=>'boolean','starts_at'=>'datetime','expires_at'=>'datetime'];
    public function redemptions(){ return $this->hasMany(CodeRedemption::class); }
}
