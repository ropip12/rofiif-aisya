<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Risk::query()->with(['assets', 'services']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('risk_code', 'like', "%{$search}%")
                    ->orWhere('risk_name', 'like', "%{$search}%")
                    ->orWhere('cause', 'like', "%{$search}%")
                    ->orWhere('impact', 'like', "%{$search}%")
                    ->orWhere('monitoring', 'like', "%{$search}%")
                    ->orWhere('evaluation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->input('risk_level'));
        }

        if ($request->filled('risk_status')) {
            $query->where('risk_status', $request->input('risk_status'));
        }

        if ($request->filled('likelihood')) {
            $query->where('likelihood', $request->input('likelihood'));
        }

        if ($request->filled('asset_id')) {
            $query->whereHas('assets', function ($q) use ($request) {
                $q->where('assets.id', (int) $request->input('asset_id'));
            });
        }

        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', (int) $request->input('service_id'));
            });
        }

        $risks = $query->orderBy('risk_name')->paginate(10)->appends($request->query());

        $summary = [
            'total' => Risk::count(),
            'by_level' => Risk::selectRaw('risk_level, COUNT(*) as total')->groupBy('risk_level')->pluck('total', 'risk_level')->all(),
            'by_status' => Risk::selectRaw('risk_status, COUNT(*) as total')->groupBy('risk_status')->pluck('total', 'risk_status')->all(),
            'with_control' => Risk::whereNotNull('control_measures')->where('control_measures', '!=', '')->count(),
            'with_mitigation' => Risk::whereNotNull('mitigation_plan')->where('mitigation_plan', '!=', '')->count(),
            'with_assets' => Risk::has('assets')->count(),
            'with_services' => Risk::has('services')->count(),
        ];

        return view('risk-management.report.index', [
            'risks' => $risks,
            'summary' => $summary,
            'assets' => Asset::orderBy('asset_name')->get(),
            'services' => Service::orderBy('service_name')->get(),
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'likelihoods' => ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'],
            'filters' => $request->only(['search', 'risk_level', 'risk_status', 'likelihood', 'asset_id', 'service_id']),
        ]);
    }
}
