<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepairProgressRequest;
use App\Services\RepairProgressService;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RepairProgressController extends Controller
{
    public function __construct(
        protected RepairProgressService $progressService,
    ) {}

    public function store(StoreRepairProgressRequest $request, int $workOrderId): RedirectResponse
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);

        // Authorize progress creation
        $this->authorize('createProgress', $workOrder);

        // Enforce: New progress percentage must be greater than current progress percentage
        $newPercentage = (int) $request->input('progress_percentage');
        if ($newPercentage < $workOrder->progress_percentage) {
            return redirect()->back()->with('error', 'Persentase progres baru tidak boleh lebih kecil dari progres saat ini (' . $workOrder->progress_percentage . '%).');
        }

        $photo = $request->file('photo');

        // Add progress via service
        $this->progressService->addProgress($workOrder, $request->validated(), $photo, auth()->user());

        return redirect()->route('work-orders.show', $workOrder->id)->with('success', 'Progres perbaikan berhasil diperbarui.');
    }
}
