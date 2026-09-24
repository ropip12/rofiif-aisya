<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceManagementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk pengelolaan layanan.');

        $query = Service::query()->with(['assets', 'risks']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('service_code', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('service_owner', 'like', "%{$search}%")
                  ->orWhere('service_type', 'like', "%{$search}%")
                  ->orWhere('service_status', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->input('service_type'));
        }

        if ($request->filled('service_status')) {
            $query->where('service_status', $request->input('service_status'));
        }

        $services = $query->orderBy('service_name')->paginate(10)->appends($request->query());

        return view('service-management.index', [
            'services' => $services,
            'search' => $request->input('search'),
            'service_type' => $request->input('service_type'),
            'service_status' => $request->input('service_status'),
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk pengelolaan layanan.');

        $validated = $request->validate([
            'service_owner' => ['required', 'string', 'max:255'],
            'service_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan,Nonaktif'],
            'supporting_information' => ['required', 'string'],
            'monitoring' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
        ]);

        $service->update($validated);

        return redirect()->route('layanan.pengelolaan')->with('success', 'Data pengelolaan layanan berhasil diperbarui.');
    }
}
