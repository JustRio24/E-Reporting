<?php

namespace App\Repositories;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkOrderRepository extends BaseRepository
{
    public function __construct(WorkOrder $model)
    {
        parent::__construct($model);
    }

    public function paginateWithRelations(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with(['damageReport', 'assignee', 'assigner']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findWithRelations(int $id): WorkOrder
    {
        return $this->newQuery()
            ->with(['damageReport.facility', 'damageReport.photos', 'assignee', 'assigner', 'progressEntries.creator'])
            ->findOrFail($id);
    }

    public function getByAssignee(int $userId): Collection
    {
        return $this->newQuery()
            ->where('assigned_to', $userId)
            ->with(['damageReport.facility'])
            ->latest()
            ->get();
    }

    public function getOverdue(): Collection
    {
        return $this->newQuery()->overdue()->with(['damageReport', 'assignee'])->get();
    }

    public function findByDamageReport(int $damageReportId): ?WorkOrder
    {
        return $this->newQuery()->where('damage_report_id', $damageReportId)->first();
    }
}
