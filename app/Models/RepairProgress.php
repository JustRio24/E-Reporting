<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RepairProgress extends Model
{
    use HasFactory;

    protected $table = 'repair_progress';

    protected $fillable = [
        'work_order_id',
        'progress_percentage',
        'description',
        'photo',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'progress_percentage' => 'integer',
        ];
    }

    // ─── Relationships ──────────────────────────────────────

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ──────────────────────────────────────────

    /**
     * Get the full URL to the evidence photo.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        return Storage::disk('public')->url($this->photo);
    }
}
