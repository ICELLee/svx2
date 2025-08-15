<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class SlotLookupController extends Controller
{
    public function search(Request $req)
    {
        $q = Slot::query()->where('is_active', true);

        $s = trim((string)$req->query('s', ''));
        if ($s !== '') {
            $q->where(function($x) use ($s){
                $x->where('name','like',"%{$s}%")
                    ->orWhere('key','like',"%{$s}%")
                    ->orWhere('provider','like',"%{$s}%");
            });
        }

        $provider = trim((string)$req->query('provider', ''));
        if ($provider !== '') $q->where('provider', $provider);

        $q->orderBy('name')->orderBy('id','desc');

        return $q->select('id','name','key','provider','image_url')->paginate(10);
    }
}
