<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // ─── Relationships ──────────────────────────────────────

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
