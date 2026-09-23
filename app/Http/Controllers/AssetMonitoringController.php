<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetMonitoringController extends Controller
{
    protected function authorizeAssetAccess(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        abort_unless($user->isAdmin() || $user->management === 'aset', 403, 'Akses ditolak untuk monitoring aset.');

        return null;
    }

    protected function applyFilters(Request $request, $query)
    {
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('responsible_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classification')) {
            $query->where('asset_classification', $request->input('classification'));
        }

        if ($request->filled('condition')) {
            $query->where('condition_status', $request->input('condition'));
        }

        if ($request->filled('usage_status')) {
            $query->where('usage_status', $request->input('usage_status'));
        }

        if ($request->filled('lifecycle')) {
            $query->where('lifecycle_status', $request->input('lifecycle'));
        }

        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $redirect = $this->authorizeAssetAccess($request);

        if ($redirect) {
            return $redirect;
        }

        $query = Asset::query()->with(['procurements', 'usages', 'warehouses', 'maintenances', 'evaluations', 'finalHandlings']);
        $query = $this->applyFilters($request, $query);

        $assets = $query->orderBy('asset_name')->paginate(10)->appends($request->query());

        $summary = [
            'total' => Asset::count(),
            'by_condition' => Asset::selectRaw('condition_status as label, COUNT(*) as total')->groupBy('condition_status')->pluck('total', 'label'),
            'by_usage' => Asset::selectRaw('usage_status as label, COUNT(*) as total')->groupBy('usage_status')->pluck('total', 'label'),
            'by_lifecycle' => Asset::selectRaw('lifecycle_status as label, COUNT(*) as total')->groupBy('lifecycle_status')->pluck('total', 'label'),
            'by_location' => Asset::selectRaw('location as label, COUNT(*) as total')->groupBy('location')->pluck('total', 'label'),
            'with_maintenance' => Asset::whereHas('maintenances')->count(),
            'with_evaluation' => Asset::whereHas('evaluations')->count(),
            'by_final_handling' => Asset::selectRaw('final_handling as label, COUNT(*) as total')->whereNotNull('final_handling')->groupBy('final_handling')->pluck('total', 'label'),
        ];

        $filterOptions = [
            'classifications' => Asset::select('asset_classification')->distinct()->orderBy('asset_classification')->pluck('asset_classification'),
            'conditions' => Asset::select('condition_status')->distinct()->orderBy('condition_status')->pluck('condition_status'),
            'usageStatus' => Asset::select('usage_status')->distinct()->orderBy('usage_status')->pluck('usage_status'),
            'lifecycle' => Asset::select('lifecycle_status')->distinct()->orderBy('lifecycle_status')->pluck('lifecycle_status'),
            'locations' => Asset::select('location')->distinct()->orderBy('location')->pluck('location'),
        ];

        return view('asset-management.monitoring.index', [
            'assets' => $assets,
            'summary' => $summary,
            'filters' => $request->only(['search', 'classification', 'condition', 'usage_status', 'lifecycle', 'location']),
            'filterOptions' => $filterOptions,
        ]);
    }
}
