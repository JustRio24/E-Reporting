<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LocationRepository extends BaseRepository
{
    public function __construct(Location $model)
    {
        parent::__construct($model);
    }

    public function getAllWithFacilityCount(): Collection
    {
        return $this->newQuery()->withCount('facilities')->orderBy('name')->get();
    }

    public function getLocationsWithCoordinates(): Collection
    {
        return $this->newQuery()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->withCount('facilities');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
