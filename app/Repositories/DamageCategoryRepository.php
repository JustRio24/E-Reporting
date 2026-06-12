<?php

namespace App\Repositories;

use App\Models\DamageCategory;
use Illuminate\Database\Eloquent\Collection;

class DamageCategoryRepository extends BaseRepository
{
    public function __construct(DamageCategory $model)
    {
        parent::__construct($model);
    }

    public function getAllWithReportCount(): Collection
    {
        return $this->newQuery()->withCount('damageReports')->orderBy('name')->get();
    }
}
