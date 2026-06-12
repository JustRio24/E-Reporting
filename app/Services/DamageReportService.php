<?php

namespace App\Services;

use App\Enums\DamageSeverity;
use App\Enums\DamageStatus;
use App\Models\DamageReport;
use App\Models\User;
use App\Repositories\DamagePhotoRepository;
use App\Repositories\DamageReportRepository;
use App\Repositories\StatusHistoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DamageReportService
{
    public function __construct(
        protected DamageReportRepository $reportRepository,
        protected DamagePhotoRepository $photoRepository,
        protected StatusHistoryRepository $historyRepository,
        protected NotificationService $notificationService,
    ) {}

    /**
     * Create a new damage report with photos.
     */
    public function create(array $data, array $photos, User $reporter): DamageReport
    {
        return DB::transaction(function () use ($data, $photos, $reporter) {
            // Generate report number
            $data['report_number'] = $this->generateReportNumber();
            $data['reporter_id'] = $reporter->id;
            $data['status'] = DamageStatus::DRAFT;

            $report = $this->reportRepository->create($data);

            // Upload photos
            foreach ($photos as $photo) {
                $path = $this->storePhoto($photo);
                $this->photoRepository->create([
                    'damage_report_id' => $report->id,
                    'photo_path' => $path,
                    'caption' => $photo->getClientOriginalName(),
                ]);
            }

            // Record initial status
            $this->historyRepository->create([
                'damage_report_id' => $report->id,
                'old_status' => null,
                'new_status' => DamageStatus::DRAFT,
                'changed_by' => $reporter->id,
                'remarks' => 'Report created.',
            ]);

            return $report;
        });
    }

    /**
     * Submit a draft report (DRAFT → REPORTED).
     */
    public function submit(DamageReport $report, User $user): DamageReport
    {
        return $this->transitionStatus($report, DamageStatus::REPORTED, $user, 'Report submitted for review.');
    }

    /**
     * Verify a reported report (REPORTED → VERIFIED).
     */
    public function verify(DamageReport $report, User $supervisor, ?string $remarks = null): DamageReport
    {
        $report = $this->transitionStatus($report, DamageStatus::VERIFIED, $supervisor, $remarks ?? 'Report verified.');

        // Notify the reporter
        $this->notificationService->notify(
            $report->reporter_id,
            'Report Verified',
            "Your report {$report->report_number} has been verified by {$supervisor->name}."
        );

        return $report;
    }

    /**
     * Transition damage report status with audit trail.
     */
    public function transitionStatus(DamageReport $report, DamageStatus $newStatus, User $changedBy, ?string $remarks = null): DamageReport
    {
        $oldStatus = $report->status;

        // Validate transition
        if (!in_array($newStatus, $oldStatus->allowedTransitions())) {
            throw new \InvalidArgumentException(
                "Cannot transition from {$oldStatus->value} to {$newStatus->value}."
            );
        }

        return DB::transaction(function () use ($report, $oldStatus, $newStatus, $changedBy, $remarks) {
            $this->reportRepository->update($report->id, [
                'status' => $newStatus,
                'reported_at' => $newStatus === DamageStatus::REPORTED ? now() : $report->reported_at,
            ]);

            $this->historyRepository->create([
                'damage_report_id' => $report->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy->id,
                'remarks' => $remarks,
            ]);

            return $report->fresh();
        });
    }

    /**
     * Get paginated reports with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->paginateWithRelations($filters, $perPage);
    }

    /**
     * Get full report details with all relations.
     */
    public function getDetail(int $id): DamageReport
    {
        return $this->reportRepository->findWithFullRelations($id);
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        return [
            'total' => $this->reportRepository->count(),
            'active' => $this->reportRepository->countActive(),
            'completed' => $this->reportRepository->countCompleted(),
            'critical' => $this->reportRepository->countCritical(),
        ];
    }

    /**
     * Get reports formatted for GIS map display.
     */
    public function getMapData(array $filters = []): array
    {
        return $this->reportRepository->getReportsForMap($filters)->map(function ($report) {
            return [
                'id' => $report->id,
                'report_number' => $report->report_number,
                'title' => $report->title,
                'severity' => $report->severity->value,
                'status' => $report->status->value,
                'map_color' => $report->status->mapColor(),
                'lat' => (float) $report->latitude,
                'lng' => (float) $report->longitude,
                'facility' => $report->facility?->facility_name,
            ];
        })->toArray();
    }

    /**
     * Generate a unique report number (e.g., DR-202606-0001).
     */
    protected function generateReportNumber(): string
    {
        $prefix = 'DR-' . now()->format('Ym');
        $lastReport = DamageReport::where('report_number', 'like', "{$prefix}%")
            ->orderBy('report_number', 'desc')
            ->first();

        if ($lastReport) {
            $lastSequence = (int) substr($lastReport->report_number, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . '-' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store a photo to the public disk.
     */
    protected function storePhoto(UploadedFile $file): string
    {
        return $file->store('damage-reports', 'public');
    }
}
