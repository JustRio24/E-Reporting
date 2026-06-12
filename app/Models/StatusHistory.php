<?php

namespace App\Models;

use App\Enums\DamageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'damage_report_id',
        'old_status',
        'new_status',
        'changed_by',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'old_status' => DamageStatus::class,
            'new_status' => DamageStatus::class,
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function damageReport(): BelongsTo
    {
        return $this->belongsTo(DamageReport::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
