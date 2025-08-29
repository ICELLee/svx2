<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\HasToolAccess;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens, HasToolAccess;

    // Beziehungen/Rollen
    public function roles(){ return $this->belongsToMany(\App\Models\Role::class); }
    public function hasRole(string $role): bool { return $this->roles()->where('name',$role)->exists(); }

    // Mass Assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'public_id',
        'country',
        'street',
        'house_number',
        'postal_code',
        'phone',
        'avatar',
    ];


    protected $hidden = ['password','remember_token'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
     | Öffentliche ID automatisch setzen (SVX-U + 7 Ziffern)
     */
    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (empty($user->public_id)) {
                $user->public_id = static::generatePublicId();
            }
        });
    }

    public static function generatePublicId(): string
    {
        do {
            $candidate = 'SVX-U' . str_pad((string) random_int(0, 9_999_999), 7, '0', STR_PAD_LEFT);
        } while (static::where('public_id', $candidate)->exists());

        return $candidate;
    }
}
