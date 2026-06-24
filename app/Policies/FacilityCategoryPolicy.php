<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\FacilityCategory;
use App\Models\User;

class FacilityCategoryPolicy
{
    /**
     * Any authenticated user can view facility categories.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FacilityCategory $category): bool
    {
        return true;
    }

    /**
     * Only admin can manage facility categories.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, FacilityCategory $category): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, FacilityCategory $category): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
