<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AssetReportController extends Controller
{
    protected function authorizeAssetAccess(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        abort_unless($user->isAdmin() || $user->management === 'aset', 403, 'Akses ditolak untuk laporan aset.');

        return null;
    }

    protected function validateFilters(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d'],
            'classification' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'usage_status' => ['nullable', 'string', 'max:255'],
            'lifecycle' => ['nullable', 'string', 'max:255'],
        ], [
            'from_date.date_format' => 'Tanggal awal tidak valid.',
            'to_date.date_format' => 'Tanggal akhir tidak valid.',
        ]);

        if ($request->filled('from_date') && $request->filled('to_date') && $request->input('from_date') > $request->input('to_date')) {
            throw ValidationException::withMessages([
                'from_date' => ['Tanggal awal tidak boleh setelah tanggal akhir.'],
            ]);
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function applyFilters(Request $request, $query)
    {
        if ($request->filled('from_date')) {
            $query->whereDate('acquisition_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('acquisition_date', '<=', $request->input('to_date'));
        }

        if ($request->filled('classification')) {
            $query->where('asset_classification', $request->input('classification'));
        }

        if ($request->filled('condition')) {
            $query->where('condition_status', $request->input('condition'));
        }

        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->filled('usage_status')) {
            $query->where('usage_status', $request->input('usage_status'));
        }

        if ($request->filled('lifecycle')) {
            $query->where('lifecycle_status', $request->input('lifecycle'));
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $redirect = $this->authorizeAssetAccess($request);

        if ($redirect) {
            return $redirect;
        }

        $this->validateFilters($request);

        $query = Asset::query()
            ->with(['procurements', 'maintenances', 'evaluations', 'finalHandlings']);

        $query = $this->applyFilters($request, $query);

        $assets = $query->orderBy('asset_name')->paginate(10)->appends($request->query());

        $summary = [
            'total' => $query->count(),
            'condition' => $query->selectRaw('condition_status as label, COUNT(*) as total')->groupBy('condition_status')->pluck('total', 'label'),
            'usage' => $query->selectRaw('usage_status as label, COUNT(*) as total')->groupBy('usage_status')->pluck('total', 'label'),
            'location' => $query->selectRaw('location as label, COUNT(*) as total')->groupBy('location')->pluck('total', 'label'),
            'lifecycle' => $query->selectRaw('lifecycle_status as label, COUNT(*) as total')->groupBy('lifecycle_status')->pluck('total', 'label'),
            'procurement_value' => $query->sum('acquisition_value'),
            'with_maintenance' => $query->whereHas('maintenances')->count(),
            'with_evaluation' => $query->whereHas('evaluations')->count(),
            'with_final_handling' => $query->whereNotNull('final_handling')->count(),
        ];

        $filterOptions = [
            'classifications' => Asset::select('asset_classification')->distinct()->orderBy('asset_classification')->pluck('asset_classification'),
            'conditions' => Asset::select('condition_status')->distinct()->whereNotNull('condition_status')->orderBy('condition_status')->pluck('condition_status'),
            'locations' => Asset::select('location')->distinct()->whereNotNull('location')->orderBy('location')->pluck('location'),
            'usageStatus' => Asset::select('usage_status')->distinct()->whereNotNull('usage_status')->orderBy('usage_status')->pluck('usage_status'),
            'lifecycle' => Asset::select('lifecycle_status')->distinct()->whereNotNull('lifecycle_status')->orderBy('lifecycle_status')->pluck('lifecycle_status'),
        ];

        return view('asset-management.report.index', [
            'assets' => $assets,
            'summary' => $summary,
            'filters' => $request->only(['from_date', 'to_date', 'classification', 'condition', 'location', 'usage_status', 'lifecycle']),
            'filterOptions' => $filterOptions,
        ]);
    }

    public function export(Request $request)
    {
        $redirect = $this->authorizeAssetAccess($request);

        if ($redirect) {
            return $redirect;
        }

        $this->validateFilters($request);

        $query = Asset::query()->with(['procurements', 'maintenances', 'evaluations', 'finalHandlings']);
        $query = $this->applyFilters($request, $query);
        $assets = $query->orderBy('asset_name')->get();

        $csv = fopen('php://temp', 'w+');
        fputcsv($csv, ['Kode Aset', 'Nama Aset', 'Klasifikasi', 'Kondisi', 'Lokasi', 'Penanggung Jawab', 'Status Penggunaan', 'Lifecycle', 'Nilai Perolehan', 'Penanganan Akhir']);

        foreach ($assets as $asset) {
            fputcsv($csv, [
                $asset->asset_code,
                $asset->asset_name,
                $asset->asset_classification,
                $asset->condition_status,
                $asset->location,
                $asset->responsible_person,
                $asset->usage_status,
                $asset->lifecycle_status,
                $asset->acquisition_value ? (string) $asset->acquisition_value : '',
                $asset->final_handling,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-aset.csv"',
        ]);
    }
}
