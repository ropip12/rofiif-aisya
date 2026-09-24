<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk laporan layanan.');

        $query = Service::query()->with(['assets', 'risks']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('service_code', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('service_owner', 'like', "%{$search}%")
                  ->orWhere('service_description', 'like', "%{$search}%")
                  ->orWhere('monitoring', 'like', "%{$search}%")
                  ->orWhere('evaluation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->input('service_type'));
        }

        if ($request->filled('service_status')) {
            $query->where('service_status', $request->input('service_status'));
        }

        if ($request->filled('service_owner')) {
            $query->where('service_owner', $request->input('service_owner'));
        }

        $services = $query->orderBy('service_name')->paginate(10)->appends($request->query());

        $summary = [
            'total' => Service::count(),
            'active' => Service::where('service_status', 'Aktif')->count(),
            'monitoring' => Service::where('service_status', 'Dalam Pemantauan')->count(),
            'with_assets' => Service::has('assets')->count(),
            'with_risks' => Service::has('risks')->count(),
            'by_type' => Service::selectRaw('service_type as label, COUNT(*) as total')->groupBy('service_type')->pluck('total', 'label'),
        ];

        return view('service-report.index', [
            'services' => $services,
            'summary' => $summary,
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
            'serviceOwners' => Service::whereNotNull('service_owner')->where('service_owner', '!=', '')->distinct()->orderBy('service_owner')->pluck('service_owner'),
            'filters' => $request->only(['search', 'service_type', 'service_status', 'service_owner']),
        ]);
    }
}
