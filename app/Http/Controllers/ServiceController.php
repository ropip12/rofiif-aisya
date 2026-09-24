<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $query = Service::query()->with(['assets', 'risks']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('service_code', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('service_description', 'like', "%{$search}%")
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

        return view('services.index', [
            'services' => $services,
            'search' => $request->input('search'),
            'service_type' => $request->input('service_type'),
            'service_status' => $request->input('service_status'),
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        return view('services.create', [
            'assets' => Asset::orderBy('asset_name')->get(),
            'risks' => Risk::orderBy('risk_name')->get(),
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $validated = $request->validate([
            'service_code' => ['required', 'string', 'max:255', 'unique:services,service_code'],
            'service_name' => ['required', 'string', 'max:255'],
            'service_description' => ['nullable', 'string'],
            'service_type' => ['required', 'string', 'max:255'],
            'service_owner' => ['required', 'string', 'max:255'],
            'service_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan,Nonaktif'],
            'supporting_information' => ['nullable', 'string'],
            'monitoring' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'assets' => ['nullable', 'array'],
            'assets.*' => ['integer', 'exists:assets,id'],
            'risks' => ['nullable', 'array'],
            'risks.*' => ['integer', 'exists:risks,id'],
        ]);

        $serviceData = $validated;
        unset($serviceData['assets'], $serviceData['risks']);

        $service = Service::create($serviceData);
        $service->assets()->sync($request->input('assets', []));
        $service->risks()->sync($request->input('risks', []));

        return redirect()->route('layanan.index')->with('success', 'Data layanan berhasil ditambahkan.');
    }

    public function show(Request $request, Service $service): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $service->load(['assets', 'risks']);

        return view('services.show', [
            'service' => $service,
        ]);
    }

    public function edit(Request $request, Service $service): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $service->load(['assets', 'risks']);

        return view('services.edit', [
            'service' => $service,
            'assets' => Asset::orderBy('asset_name')->get(),
            'risks' => Risk::orderBy('risk_name')->get(),
            'serviceTypes' => ['Internal', 'Eksternal', 'Campuran'],
            'serviceStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan', 'Nonaktif'],
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $validated = $request->validate([
            'service_code' => ['required', 'string', 'max:255', Rule::unique('services', 'service_code')->ignore($service->id)],
            'service_name' => ['required', 'string', 'max:255'],
            'service_description' => ['nullable', 'string'],
            'service_type' => ['required', 'string', 'max:255'],
            'service_owner' => ['required', 'string', 'max:255'],
            'service_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan,Nonaktif'],
            'supporting_information' => ['nullable', 'string'],
            'monitoring' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'assets' => ['nullable', 'array'],
            'assets.*' => ['integer', 'exists:assets,id'],
            'risks' => ['nullable', 'array'],
            'risks.*' => ['integer', 'exists:risks,id'],
        ]);

        $serviceData = $validated;
        unset($serviceData['assets'], $serviceData['risks']);

        $service->update($serviceData);
        $service->assets()->sync($request->input('assets', []));
        $service->risks()->sync($request->input('risks', []));

        return redirect()->route('layanan.show', ['service' => $service->id])->with('success', 'Data layanan berhasil diperbarui.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'layanan'), 403, 'Akses ditolak untuk data layanan.');

        $service->assets()->detach();
        $service->risks()->detach();
        $service->delete();

        return redirect()->route('layanan.index')->with('success', 'Data layanan berhasil dihapus.');
    }
}
