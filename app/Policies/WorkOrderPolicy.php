<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    /**
     * Supervisors and admins can view all work orders.
     * Maintenance can view their own assignments.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::SUPERVISOR, UserRole::MAINTENANCE]);
    }

    /**
     * View a specific work order.
     */
    public function view(User $user, WorkOrder $workOrder): bool
    {
        if (in_array($user->role, [UserRole::ADMIN, UserRole::SUPERVISOR])) {
            return true;
        }

        return $user->role === UserRole::MAINTENANCE && $workOrder->assigned_to === $user->id;
    }

    /**
     * RULE-003: Only Supervisor can create work orders.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::SUPERVISOR || $user->role === UserRole::ADMIN;
    }

    /**
     * Only supervisors/admin can update work order details.
     */
    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::SUPERVISOR || $user->role === UserRole::ADMIN;
    }

    /**
     * Only admin can delete work orders.
     */
    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function startWork(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::SUPERVISOR;
    }

    public function completeWork(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::SUPERVISOR || $user->role === UserRole::ADMIN || $user->id === $workOrder->assigned_to;
    }
}
