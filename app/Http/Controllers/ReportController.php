<?php

namespace App\Http\Controllers;

use App\Repositories\DamageReportRepository;
use App\Repositories\FacilityRepository;
use App\Enums\DamageStatus;
use App\Enums\DamageSeverity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function __construct(
        protected DamageReportRepository $reportRepo,
        protected FacilityRepository $facilityRepo,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'severity', 'facility_id']);
        
        // Execute filtered query for preview
        $reports = $this->reportRepo->getFilteredReports($filters);
        
        $facilities = $this->facilityRepo->all();
        $statuses = DamageStatus::cases();
        $severities = DamageSeverity::cases();

        return view('reports.index', compact('reports', 'facilities', 'statuses', 'severities', 'filters'));
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'severity', 'facility_id']);
        
        // Fetch matching records
        $reports = $this->reportRepo->getFilteredReports($filters);

        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-inspeksi-fasilitas-' . now()->format('Ymd-His') . '.pdf');
    }
}
