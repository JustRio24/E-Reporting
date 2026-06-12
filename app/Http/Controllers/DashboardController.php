<?php

namespace App\Http\Controllers;

use App\Services\DamageReportService;
use App\Repositories\DamageReportRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DamageReportService $reportService,
        protected DamageReportRepository $reportRepository,
    ) {}

    public function index(Request $request): View
    {
        $stats = $this->reportService->getDashboardStats();
        
        // Get charts data
        $year = now()->year;
        $monthlyRaw = $this->reportRepository->getMonthlyStats($year);
        
        $months = [];
        $monthlyTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1));
            $found = $monthlyRaw->firstWhere('month', $m);
            $monthlyTotals[] = $found ? $found->total : 0;
        }

        $categoryRaw = $this->reportRepository->getStatsByCategory();
        $categoryLabels = [];
        $categoryTotals = [];
        foreach ($categoryRaw as $cat) {
            $categoryLabels[] = $cat->damageCategory?->name ?? 'Unknown';
            $categoryTotals[] = $cat->total;
        }

        $facilityRaw = $this->reportRepository->getStatsByFacility();
        $facilityLabels = [];
        $facilityTotals = [];
        foreach ($facilityRaw as $fac) {
            $facilityLabels[] = $fac->facility?->facility_name ?? 'Unknown';
            $facilityTotals[] = $fac->total;
        }

        // Get map data pins
        $mapData = $this->reportService->getMapData();

        return view('dashboard', compact(
            'stats',
            'months',
            'monthlyTotals',
            'categoryLabels',
            'categoryTotals',
            'facilityLabels',
            'facilityTotals',
            'mapData'
        ));
    }
}
