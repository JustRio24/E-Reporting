<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

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
}
