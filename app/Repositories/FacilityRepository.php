<?php

namespace App\Repositories;

use App\Models\Facility;
use Illuminate\Pagination\LengthAwarePaginator;

class FacilityRepository extends BaseRepository
{
    public function __construct(Facility $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->paginateWithRelations($filters, $perPage);
    }

    public function paginateWithRelations(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with(['category', 'location']);

        if (!empty($filters['category_id'])) {
            $query->where('facility_category_id', $filters['category_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('facility_name', 'like', "%{$filters['search']}%")
                  ->orWhere('facility_code', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findWithRelations(int $id): Facility
    {
        return $this->newQuery()
            ->with(['category', 'location', 'damageReports'])
            ->findOrFail($id);
    }
}
