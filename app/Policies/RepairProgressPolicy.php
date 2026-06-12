<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\RepairProgress;
use App\Models\User;

class RepairProgressPolicy
{
    /**
     * Supervisors, admin, and maintenance can view progress entries.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::SUPERVISOR, UserRole::MAINTENANCE]);
    }

    /**
     * RULE-004: Only Maintenance Team can create/update repair progress.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::MAINTENANCE || $user->role === UserRole::ADMIN;
    }

    /**
     * Only the creator can update their own progress entry.
     */
    public function update(User $user, RepairProgress $progress): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->role === UserRole::MAINTENANCE && $progress->created_by === $user->id;
    }
}
