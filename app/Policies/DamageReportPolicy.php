<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DamageReport;
use App\Models\User;

class DamageReportPolicy
{
    /**
     * Any authenticated user can view the damage report listing.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Any authenticated user can view a specific damage report.
     */
    public function view(User $user, DamageReport $report): bool
    {
        // Inspectors can only view their own reports
        if ($user->role === UserRole::INSPECTOR) {
            return $report->reporter_id === $user->id;
        }

        // Maintenance can only view reports assigned to them
        if ($user->role === UserRole::MAINTENANCE) {
            return $report->workOrder?->assigned_to === $user->id;
        }

        // Admin and Supervisor can view all
        return true;
    }

    /**
     * RULE-001: Only Inspector can create damage reports.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::INSPECTOR || $user->role === UserRole::ADMIN;
    }

    /**
     * Only the reporter (Inspector) can update a draft report.
     */
    public function update(User $user, DamageReport $report): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->role === UserRole::INSPECTOR
            && $report->reporter_id === $user->id
            && $report->status->value === 'draft';
    }

    /**
     * Only admin can delete reports, and only if in draft status.
     */
    public function delete(User $user, DamageReport $report): bool
    {
        return $user->role === UserRole::ADMIN && $report->status->value === 'draft';
    }

    /**
     * RULE-002: Only Supervisor can verify reports.
     */
    public function verify(User $user, DamageReport $report): bool
    {
        return ($user->role === UserRole::SUPERVISOR || $user->role === UserRole::ADMIN)
            && $report->status->value === 'reported';
    }

    /**
     * RULE-005: Completed report must pass Supervisor verification.
     */
    public function verifyCompletion(User $user, DamageReport $report): bool
    {
        return ($user->role === UserRole::SUPERVISOR || $user->role === UserRole::ADMIN)
            && $report->status->value === 'waiting_verification';
    }
}
