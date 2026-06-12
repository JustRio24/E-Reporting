<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Services\WorkOrderService;
use App\Repositories\WorkOrderRepository;
use App\Repositories\DamageReportRepository;
use App\Models\WorkOrder;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\WorkOrderStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkOrderController extends Controller
{
    public function __construct(
        protected WorkOrderService $workOrderService,
        protected WorkOrderRepository $workOrderRepo,
        protected DamageReportRepository $reportRepo,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'search']);
        
        // If user is maintenance, filter only assigned to them
        if (auth()->user()->isMaintenance()) {
            $filters['assigned_to'] = auth()->id();
        }

        $workOrders = $this->workOrderRepo->paginateWithRelations($filters);
        $statuses = WorkOrderStatus::cases();

        return view('work-orders.index', compact('workOrders', 'statuses', 'filters'));
    }

    public function create(Request $request): View
    {
        $damageReportId = $request->input('damage_report_id');
        $damageReport = \App\Models\DamageReport::findOrFail($damageReportId);

        // Enforce 1:1 check: ensure this report does not already have a work order
        if ($damageReport->workOrder) {
            return redirect()->route('damage-reports.show', $damageReportId)
                ->with('error', 'Laporan kerusakan ini sudah memiliki Surat Perintah Kerja (WO).');
        }

        // Get maintenance users
        $maintenanceUsers = User::where('role', UserRole::MAINTENANCE)
            ->where('is_active', true)
            ->get();

        return view('work-orders.create', compact('damageReport', 'maintenanceUsers'));
    }

    public function store(StoreWorkOrderRequest $request): RedirectResponse
    {
        $damageReportId = $request->input('damage_report_id');
        $damageReport = \App\Models\DamageReport::findOrFail($damageReportId);

        // Enforce 1:1 check
        if ($damageReport->workOrder) {
            return redirect()->route('damage-reports.show', $damageReportId)
                ->with('error', 'Laporan kerusakan ini sudah memiliki Surat Perintah Kerja (WO).');
        }

        $data = $request->validated();
        
        // Create WO via service
        $workOrder = $this->workOrderService->create($data, $damageReport, auth()->user());

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Surat Perintah Kerja (WO) berhasil dibuat dan ditugaskan.');
    }

    public function show(int $id): View
    {
        $workOrder = WorkOrder::with(['damageReport.facility.location', 'damageReport.photos', 'assignee', 'creator', 'progressEntries.creator'])->findOrFail($id);
        
        // Check policy
        $this->authorize('view', $workOrder);

        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(int $id): View
    {
        $workOrder = WorkOrder::findOrFail($id);
        $this->authorize('update', $workOrder);

        $maintenanceUsers = User::where('role', UserRole::MAINTENANCE)
            ->where('is_active', true)
            ->get();

        return view('work-orders.edit', compact('workOrder', 'maintenanceUsers'));
    }

    public function update(UpdateWorkOrderRequest $request, int $id): RedirectResponse
    {
        $workOrder = WorkOrder::findOrFail($id);
        $this->authorize('update', $workOrder);

        $this->workOrderRepo->update($id, $request->validated());

        return redirect()->route('work-orders.show', $id)->with('success', 'Perintah kerja berhasil diperbarui.');
    }

    public function startWork(int $id): RedirectResponse
    {
        $workOrder = WorkOrder::findOrFail($id);
        $this->authorize('updateStatus', $workOrder);

        $this->workOrderService->startWork($workOrder, auth()->user());

        return redirect()->route('work-orders.show', $id)->with('success', 'Pekerjaan perbaikan telah dimulai.');
    }

    public function completeWork(int $id): RedirectResponse
    {
        $workOrder = WorkOrder::findOrFail($id);
        $this->authorize('updateStatus', $workOrder);

        // Validation rule: progress percentage must be 100 before closing/completing
        if ($workOrder->progress_percentage < 100) {
            return redirect()->back()->with('error', 'Pekerjaan tidak dapat diselesaikan karena persentase progres perbaikan belum mencapai 100% (RULE-005).');
        }

        $this->workOrderService->completeWork($workOrder, auth()->user());

        return redirect()->route('work-orders.show', $id)->with('success', 'Pekerjaan ditandai selesai. Menunggu verifikasi Pengawas.');
    }
}
