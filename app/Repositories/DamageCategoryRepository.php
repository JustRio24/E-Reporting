<?php

namespace App\Repositories;

use App\Models\DamageCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->withCount('damageReports');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
