<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Facility;
use App\Models\User;

class FacilityPolicy
{
    /**
     * Any authenticated user can view facilities.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Facility $facility): bool
    {
        return true;
    }

    /**
     * Only admin can manage facilities.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Facility $facility): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Facility $facility): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
