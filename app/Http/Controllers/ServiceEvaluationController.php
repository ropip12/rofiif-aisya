<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk evaluasi layanan.');

        $query = Service::query()->with(['assets', 'risks']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('service_code', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('service_owner', 'like', "%{$search}%")
                  ->orWhere('evaluation', 'like', "%{$search}%")
                  ->orWhere('service_description', 'like', "%{$search}%");
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

        return view('service-evaluation.index', [
            'services' => $services,
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
            'serviceOwners' => Service::whereNotNull('service_owner')->where('service_owner', '!=', '')->distinct()->orderBy('service_owner')->pluck('service_owner'),
            'filters' => $request->only(['search', 'service_type', 'service_status', 'service_owner']),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk evaluasi layanan.');

        $validated = $request->validate([
            'service_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan,Nonaktif'],
            'evaluation' => ['nullable', 'string'],
        ]);

        $service->update([
            'service_status' => $validated['service_status'],
            'evaluation' => $validated['evaluation'] ?? $service->evaluation,
        ]);

        return redirect()->route('layanan.evaluasi')->with('success', 'Hasil evaluasi layanan berhasil diperbarui.');
    }
}
