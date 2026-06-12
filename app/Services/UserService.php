<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user.
     */
    public function update(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(int $id): bool
    {
        $user = $this->userRepository->findOrFail($id);

        return $this->userRepository->update($id, [
            'is_active' => !$user->is_active,
        ]);
    }

    /**
     * Get paginated users with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginateWithFilters($filters, $perPage);
    }

    /**
     * Get all active maintenance team members (for work order assignment dropdown).
     */
    public function getMaintenanceTeam(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userRepository->getMaintenanceTeam();
    }
}
