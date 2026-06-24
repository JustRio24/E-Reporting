<?php

namespace App\Models;

use App\Enums\WorkOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'damage_report_id',
        'assigned_to',
        'assigned_by',
        'assigned_date',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'due_date' => 'date',
            'status' => WorkOrderStatus::class,
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function damageReport(): BelongsTo
    {
        return $this->belongsTo(DamageReport::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function progressEntries(): HasMany
    {
        return $this->hasMany(RepairProgress::class);
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', WorkOrderStatus::COMPLETED)
                     ->where('status', '!=', WorkOrderStatus::CANCELLED);
    }

    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->due_date < now() &&
               $this->status !== WorkOrderStatus::COMPLETED &&
               $this->status !== WorkOrderStatus::CANCELLED;
    }

    public function getProgressPercentageAttribute(): int
    {
        $latest = $this->progressEntries()->latest('created_at')->first();
        return $latest ? (int) $latest->progress_percentage : 0;
    }
}
