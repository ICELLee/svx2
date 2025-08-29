<?php

namespace App\Models\Concerns;

use App\Models\UserToolEntitlement;

trait HasToolAccess
{
    public function entitlements()
    {
        return $this->hasMany(UserToolEntitlement::class);
    }

    public function hasTool(string $tool): bool
    {
        return $this->entitlements()
            ->where('tool', strtolower(str_replace(' ', '', $tool)))
            ->active()
            ->exists();
    }
}
