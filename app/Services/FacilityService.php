<?php

namespace App\Services;

use App\Repositories\FacilityCategoryRepository;
use App\Repositories\FacilityRepository;
use App\Repositories\LocationRepository;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FacilityService
{
    public function __construct(
        protected FacilityRepository $facilityRepository,
        protected FacilityCategoryRepository $categoryRepository,
        protected LocationRepository $locationRepository,
    ) {}

    /**
     * Get paginated facilities with related data.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->facilityRepository->paginateWithRelations($filters, $perPage);
    }

    /**
     * Get facility detail with all relations.
     */
    public function getDetail(int $id): Facility
    {
        return $this->facilityRepository->findWithRelations($id);
    }

    /**
     * Create a new facility.
     */
    public function create(array $data): Facility
    {
        return $this->facilityRepository->create($data);
    }

    /**
     * Update an existing facility.
     */
    public function update(int $id, array $data): bool
    {
        return $this->facilityRepository->update($id, $data);
    }

    /**
     * Delete a facility.
     */
    public function delete(int $id): bool
    {
        return $this->facilityRepository->delete($id);
    }

    /**
     * Get all categories for form dropdowns.
     */
    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAllWithFacilityCount();
    }

    /**
     * Get all locations for form dropdowns.
     */
    public function getLocations(): Collection
    {
        return $this->locationRepository->getAllWithFacilityCount();
    }
}
