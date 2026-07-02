<?php

namespace App\Services;

use App\Models\RepairProgress;
use App\Models\User;
use App\Models\WorkOrder;
use App\Repositories\RepairProgressRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RepairProgressService
{
    public function __construct(
        protected RepairProgressRepository $progressRepository,
        protected WorkOrderService $workOrderService,
    ) {}

    /**
     * Add a progress entry to a work order.
     */
    public function addProgress(WorkOrder $workOrder, array $data, array $photos, User $creator): RepairProgress
    {
        $data['work_order_id'] = $workOrder->id;
        $data['created_by'] = $creator->id;

        $photoPaths = [];
        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $photoPaths[] = $photo->store('repair-progress', 'public');
            }
        }
        $data['photo'] = !empty($photoPaths) ? $photoPaths : null;

        // Validate percentage bounds
        $data['progress_percentage'] = max(0, min(100, (int) $data['progress_percentage']));

        $progress = $this->progressRepository->create($data);

        // Auto-complete work order when progress hits 100%
        if ($data['progress_percentage'] >= 100 && $workOrder->status->value === 'in_progress') {
            $this->workOrderService->completeWork($workOrder, $creator);
        }

        return $progress;
    }

    /**
     * Get all progress entries for a work order.
     */
    public function getByWorkOrder(int $workOrderId): Collection
    {
        return $this->progressRepository->getByWorkOrder($workOrderId);
    }

    /**
     * Get the latest progress for a work order.
     */
    public function getLatest(int $workOrderId): ?RepairProgress
    {
        return $this->progressRepository->getLatestByWorkOrder($workOrderId);
    }
}
