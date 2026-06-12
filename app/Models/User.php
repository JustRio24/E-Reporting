<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class, 'reporter_id');
    }

    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function createdWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_by');
    }

    public function repairProgressEntries(): HasMany
    {
        return $this->hasMany(RepairProgress::class, 'created_by');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }

    public function internalNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // ─── Helpers ────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isInspector(): bool
    {
        return $this->role === UserRole::INSPECTOR;
    }

    public function isSupervisor(): bool
    {
        return $this->role === UserRole::SUPERVISOR;
    }

    public function isMaintenance(): bool
    {
        return $this->role === UserRole::MAINTENANCE;
    }
}
