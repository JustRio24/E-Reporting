<?php

namespace App\Models;

use App\Enums\DamageSeverity;
use App\Enums\DamageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'facility_id',
        'reporter_id',
        'damage_category_id',
        'severity',
        'title',
        'description',
        'latitude',
        'longitude',
        'status',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'severity' => DamageSeverity::class,
            'status' => DamageStatus::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'reported_at' => 'datetime',
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function damageCategory(): BelongsTo
    {
        return $this->belongsTo(DamageCategory::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(DamagePhoto::class);
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(WorkOrder::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', '!=', DamageStatus::COMPLETED);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', DamageSeverity::CRITICAL);
    }

    public function scopeByStatus($query, DamageStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySeverity($query, DamageSeverity $severity)
    {
        return $query->where('severity', $severity);
    }
}
