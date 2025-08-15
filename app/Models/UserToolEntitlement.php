<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserToolEntitlement extends Model {
    protected $fillable = ['user_id','tool','expires_at','last_code_id'];
    protected $casts = ['expires_at'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
}
