<?php

namespace App\Http\Controllers;

use App\Services\DamageReportService;
use App\Enums\DamageStatus;
use App\Enums\DamageSeverity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GisController extends Controller
{
    public function __construct(
        protected DamageReportService $reportService,
    ) {}

    public function index(): View
    {
        $severities = DamageSeverity::cases();
        $statuses = DamageStatus::cases();

        return view('gis.index', compact('severities', 'statuses'));
    }

    public function mapData(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'severity']);
        $mapData = $this->reportService->getMapData($filters);

        return response()->json($mapData);
    }
}
