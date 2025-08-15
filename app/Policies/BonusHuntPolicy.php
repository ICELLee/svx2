<?php

namespace App\Policies;

use App\Models\BonusHunt;
use App\Models\User;

class BonusHuntPolicy
{
    public function view(User $user, BonusHunt $hunt): bool
    {
        return $hunt->user_id === $user->id;
    }

    public function update(User $user, BonusHunt $hunt): bool
    {
        return $hunt->user_id === $user->id;
    }

    public function delete(User $user, BonusHunt $hunt): bool
    {
        return $hunt->user_id === $user->id;
    }
}
