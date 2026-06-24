<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    /**
     * Any authenticated user can view locations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Location $location): bool
    {
        return true;
    }

    /**
     * Only admin can manage locations.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Location $location): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Location $location): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
