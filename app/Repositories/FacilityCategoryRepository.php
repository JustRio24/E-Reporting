<?php

namespace App\Repositories;

use App\Models\FacilityCategory;
use Illuminate\Database\Eloquent\Collection;

class FacilityCategoryRepository extends BaseRepository
{
    public function __construct(FacilityCategory $model)
    {
        parent::__construct($model);
    }

    public function getAllWithFacilityCount(): Collection
    {
        return $this->newQuery()->withCount('facilities')->orderBy('name')->get();
    }
}
