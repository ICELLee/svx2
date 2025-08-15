<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CodeRedemption extends Model {
    protected $fillable = ['promo_code_id','user_id','redeemed_at'];
    protected $casts = ['redeemed_at'=>'datetime'];
    public function code(){ return $this->belongsTo(PromoCode::class,'promo_code_id'); }
}
