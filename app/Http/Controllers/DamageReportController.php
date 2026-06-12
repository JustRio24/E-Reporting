<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateDamageReportRequest;
use App\Services\DamageReportService;
use App\Repositories\DamageReportRepository;
use App\Repositories\FacilityRepository;
use App\Repositories\DamageCategoryRepository;
use App\Models\DamageReport;
use App\Models\DamagePhoto;
use App\Enums\DamageStatus;
use App\Enums\DamageSeverity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DamageReportController extends Controller
{
    public function __construct(
        protected DamageReportService $reportService,
        protected DamageReportRepository $reportRepo,
        protected FacilityRepository $facilityRepo,
        protected DamageCategoryRepository $categoryRepo,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'severity', 'search']);
        $reports = $this->reportService->getPaginated($filters);
        
        $severities = DamageSeverity::cases();
        $statuses = DamageStatus::cases();

        return view('damage-reports.index', compact('reports', 'severities', 'statuses', 'filters'));
    }

    public function create(): View
    {
        $facilities = $this->facilityRepo->all();
        $categories = $this->categoryRepo->all();
        $severities = DamageSeverity::cases();

        return view('damage-reports.create', compact('facilities', 'categories', 'severities'));
    }

    public function store(StoreDamageReportRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Handle coordinate inheritance from facility if empty
        if (empty($data['latitude']) || empty($data['longitude'])) {
            $facility = \App\Models\Facility::find($data['facility_id']);
            if ($facility) {
                $data['latitude'] = $data['latitude'] ?: $facility->latitude;
                $data['longitude'] = $data['longitude'] ?: $facility->longitude;
            }
        }

        $photos = $request->file('photos') ?? [];

        // Create the report as draft first
        $report = $this->reportService->create($data, $photos, auth()->user());

        // Transition to reported if action is not draft
        if ($request->input('action') !== 'draft') {
            $this->reportService->submit($report, auth()->user());
            $message = 'Laporan kerusakan berhasil dikirim untuk verifikasi.';
        } else {
            $message = 'Laporan berhasil disimpan sebagai Draft.';
        }

        return redirect()->route('damage-reports.index')->with('success', $message);
    }

    public function show(int $id): View
    {
        $report = $this->reportService->getDetail($id);
        
        // Authorize viewing
        $this->authorize('view', $report);

        return view('damage-reports.show', compact('report'));
    }

    public function edit(int $id): View
    {
        $report = DamageReport::findOrFail($id);
        
        // Check policy: only reporter can edit draft
        $this->authorize('update', $report);

        $facilities = $this->facilityRepo->all();
        $categories = $this->categoryRepo->all();
        $severities = DamageSeverity::cases();

        return view('damage-reports.edit', compact('report', 'facilities', 'categories', 'severities'));
    }

    public function update(UpdateDamageReportRequest $request, int $id): RedirectResponse
    {
        $report = DamageReport::findOrFail($id);
        $this->authorize('update', $report);

        $data = $request->validated();
        
        // Coordinates fallback
        if (empty($data['latitude']) || empty($data['longitude'])) {
            $facility = \App\Models\Facility::find($data['facility_id']);
            if ($facility) {
                $data['latitude'] = $data['latitude'] ?: $facility->latitude;
                $data['longitude'] = $data['longitude'] ?: $facility->longitude;
            }
        }

        // Update properties
        $report->update($data);

        // Upload new photos if any
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('damage_reports', 'public');
                DamagePhoto::create([
                    'damage_report_id' => $report->id,
                    'photo_path' => $path,
                    'caption' => $photo->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('damage-reports.show', $report->id)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function submit(int $id): RedirectResponse
    {
        $report = DamageReport::findOrFail($id);
        
        // Authorize updating (reporter check)
        $this->authorize('update', $report);

        if ($report->status !== DamageStatus::DRAFT) {
            return redirect()->back()->with('error', 'Hanya laporan draft yang dapat dikirim.');
        }

        $this->reportService->submit($report, auth()->user());

        return redirect()->route('damage-reports.show', $id)->with('success', 'Laporan berhasil dikirim untuk verifikasi.');
    }

    public function verify(Request $request, int $id): RedirectResponse
    {
        $report = DamageReport::findOrFail($id);

        // Check policy: only supervisor or admin can verify
        $this->authorize('verify', $report);

        $action = $request->input('action'); // 'verify' or 'reject'
        $notes = $request->input('notes', 'Verifikasi oleh Pengawas.');

        if ($action === 'verify') {
            $this->reportService->verify($report, auth()->user(), $notes);
            $message = 'Laporan kerusakan berhasil diverifikasi.';
        } else {
            $this->reportService->transitionStatus($report, DamageStatus::DRAFT, auth()->user(), 'Laporan ditolak: ' . $notes);
            $message = 'Laporan kerusakan ditolak dan dikembalikan sebagai draft ke pelapor.';
        }

        return redirect()->route('damage-reports.show', $id)->with('success', $message);
    }

    public function destroy(int $id): RedirectResponse
    {
        $report = DamageReport::findOrFail($id);
        
        $this->authorize('delete', $report);

        // Delete associated files
        foreach ($report->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $report->delete();

        return redirect()->route('damage-reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
