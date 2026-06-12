<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DamagePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'damage_report_id',
        'photo_path',
        'caption',
    ];

    // ─── Relationships ──────────────────────────────────────

    public function damageReport(): BelongsTo
    {
        return $this->belongsTo(DamageReport::class);
    }

    // ─── Accessors ──────────────────────────────────────────

    /**
     * Get the full URL to the photo.
     */
    public function getPhotoUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->photo_path);
    }
}
