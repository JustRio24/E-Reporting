<?php

namespace App\Services;

use App\Enums\DamageStatus;
use App\Enums\WorkOrderStatus;
use App\Models\DamageReport;
use App\Models\User;
use App\Models\WorkOrder;
use App\Repositories\WorkOrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WorkOrderService
{
    public function __construct(
        protected WorkOrderRepository $workOrderRepository,
        protected DamageReportService $damageReportService,
        protected NotificationService $notificationService,
    ) {}

    /**
     * Create a work order and transition report to ASSIGNED.
     */
    public function create(array $data, DamageReport $report, User $supervisor): WorkOrder
    {
        // Business Rule: Work Order cannot be created before report verification
        if ($report->status !== DamageStatus::VERIFIED) {
            throw new \InvalidArgumentException('Work order can only be created for verified reports.');
        }

        return DB::transaction(function () use ($data, $report, $supervisor) {
            $data['damage_report_id'] = $report->id;
            $data['assigned_by'] = $supervisor->id;
            $data['assigned_date'] = now()->toDateString();
            $data['status'] = WorkOrderStatus::PENDING;

            $workOrder = $this->workOrderRepository->create($data);

            // Transition report status to ASSIGNED
            $this->damageReportService->transitionStatus(
                $report,
                DamageStatus::ASSIGNED,
                $supervisor,
                "Assigned to maintenance team. Due: {$data['due_date']}."
            );

            // Notify the assigned maintenance team member
            $this->notificationService->notify(
                $data['assigned_to'],
                'New Work Order Assigned',
                "You have been assigned to repair: {$report->title} (Report: {$report->report_number}). Due date: {$data['due_date']}."
            );

            return $workOrder;
        });
    }

    /**
     * Start working on a work order (PENDING → IN_PROGRESS).
     */
    public function startWork(WorkOrder $workOrder, User $maintenanceUser): WorkOrder
    {
        if ($workOrder->status !== WorkOrderStatus::PENDING) {
            throw new \InvalidArgumentException('Can only start work on pending work orders.');
        }

        return DB::transaction(function () use ($workOrder, $maintenanceUser) {
            $this->workOrderRepository->update($workOrder->id, [
                'status' => WorkOrderStatus::IN_PROGRESS,
            ]);

            // Transition damage report to IN_PROGRESS
            $this->damageReportService->transitionStatus(
                $workOrder->damageReport,
                DamageStatus::IN_PROGRESS,
                $maintenanceUser,
                'Repair work started.'
            );

            return $workOrder->fresh();
        });
    }

    /**
     * Mark work order as completed (IN_PROGRESS → COMPLETED).
     */
    public function completeWork(WorkOrder $workOrder, User $maintenanceUser): WorkOrder
    {
        if ($workOrder->status !== WorkOrderStatus::IN_PROGRESS) {
            throw new \InvalidArgumentException('Can only complete work orders that are in progress.');
        }

        return DB::transaction(function () use ($workOrder, $maintenanceUser) {
            $this->workOrderRepository->update($workOrder->id, [
                'status' => WorkOrderStatus::COMPLETED,
            ]);

            // Transition damage report to WAITING_VERIFICATION
            $this->damageReportService->transitionStatus(
                $workOrder->damageReport,
                DamageStatus::WAITING_VERIFICATION,
                $maintenanceUser,
                'Repair completed. Awaiting supervisor verification.'
            );

            // Notify the supervisor who created the work order
            $this->notificationService->notify(
                $workOrder->assigned_by,
                'Repair Completed — Verification Needed',
                "Repair for {$workOrder->damageReport->report_number} has been completed and requires your verification."
            );

            return $workOrder->fresh();
        });
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->workOrderRepository->paginateWithRelations($filters, $perPage);
    }

    public function getDetail(int $id): WorkOrder
    {
        return $this->workOrderRepository->findWithRelations($id);
    }
}
