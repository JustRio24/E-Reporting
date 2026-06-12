<?php

namespace App\Repositories;

use App\Enums\DamageSeverity;
use App\Enums\DamageStatus;
use App\Models\DamageReport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DamageReportRepository extends BaseRepository
{
    public function __construct(DamageReport $model)
    {
        parent::__construct($model);
    }

    public function paginateWithRelations(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with(['facility', 'reporter', 'damageCategory']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if (!empty($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        if (!empty($filters['reporter_id'])) {
            $query->where('reporter_id', $filters['reporter_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('report_number', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findWithFullRelations(int $id): DamageReport
    {
        return $this->newQuery()
            ->with(['facility.category', 'facility.location', 'reporter', 'damageCategory', 'photos', 'workOrder.assignee', 'statusHistories.changedBy'])
            ->findOrFail($id);
    }

    public function getActiveReports(): Collection
    {
        return $this->newQuery()->active()->with(['facility', 'reporter'])->latest()->get();
    }

    public function getFilteredReports(array $filters = []): Collection
    {
        $query = $this->newQuery()->with(['facility', 'reporter', 'damageCategory']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if (!empty($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('reported_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('reported_at', '<=', $filters['end_date']);
        }

        return $query->latest()->get();
    }

    public function getReportsForMap(array $filters = []): Collection
    {
        $query = $this->newQuery()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['facility']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        return $query->get(['id', 'report_number', 'title', 'severity', 'status', 'latitude', 'longitude', 'facility_id']);
    }

    public function getByReporter(int $userId): Collection
    {
        return $this->newQuery()
            ->where('reporter_id', $userId)
            ->with(['facility', 'damageCategory'])
            ->latest()
            ->get();
    }

    // ─── Dashboard Statistics ───────────────────────────────

    public function countByStatus(DamageStatus $status): int
    {
        return $this->newQuery()->where('status', $status)->count();
    }

    public function countActive(): int
    {
        return $this->newQuery()->active()->count();
    }

    public function countCritical(): int
    {
        return $this->newQuery()->critical()->active()->count();
    }

    public function countCompleted(): int
    {
        return $this->countByStatus(DamageStatus::COMPLETED);
    }

    public function getMonthlyStats(int $year): Collection
    {
        $driver = $this->newQuery()->getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return $this->newQuery()
                ->selectRaw('CAST(strftime("%m", reported_at) AS INTEGER) as month, COUNT(*) as total')
                ->whereYear('reported_at', $year)
                ->groupByRaw('strftime("%m", reported_at)')
                ->orderByRaw('strftime("%m", reported_at)')
                ->get();
        }

        return $this->newQuery()
            ->selectRaw('MONTH(reported_at) as month, COUNT(*) as total')
            ->whereYear('reported_at', $year)
            ->groupByRaw('MONTH(reported_at)')
            ->orderByRaw('MONTH(reported_at)')
            ->get();
    }

    public function getStatsByCategory(): Collection
    {
        return $this->newQuery()
            ->selectRaw('damage_category_id, COUNT(*) as total')
            ->with('damageCategory')
            ->groupBy('damage_category_id')
            ->get();
    }

    public function getStatsByFacility(): Collection
    {
        return $this->newQuery()
            ->selectRaw('facility_id, COUNT(*) as total')
            ->with('facility')
            ->groupBy('facility_id')
            ->get();
    }
}
