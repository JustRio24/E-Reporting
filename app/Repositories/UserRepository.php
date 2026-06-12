<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getByRole(UserRole $role): Collection
    {
        return $this->newQuery()->where('role', $role)->where('is_active', true)->get();
    }

    public function getMaintenanceTeam(): Collection
    {
        return $this->getByRole(UserRole::MAINTENANCE);
    }

    public function getActiveUsers(): Collection
    {
        return $this->newQuery()->where('is_active', true)->get();
    }
}
