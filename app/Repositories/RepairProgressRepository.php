<?php

namespace App\Repositories;

use App\Models\RepairProgress;
use Illuminate\Database\Eloquent\Collection;

class RepairProgressRepository extends BaseRepository
{
    public function __construct(RepairProgress $model)
    {
        parent::__construct($model);
    }

    public function getByWorkOrder(int $workOrderId): Collection
    {
        return $this->newQuery()
            ->where('work_order_id', $workOrderId)
            ->with('creator')
            ->latest()
            ->get();
    }

    public function getLatestByWorkOrder(int $workOrderId): ?RepairProgress
    {
        return $this->newQuery()
            ->where('work_order_id', $workOrderId)
            ->latest()
            ->first();
    }
}
