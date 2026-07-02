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
            'photo' => 'array',
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
     * Get the full URLs to the evidence photos.
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (empty($this->photo)) {
            return [];
        }

        return array_map(function ($path) {
            return Storage::disk('public')->url($path);
        }, $this->photo);
    }
}
