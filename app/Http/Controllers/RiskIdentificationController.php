<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\Asset;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskIdentificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Risk::query()->with(['assets', 'services']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('risk_code', 'like', "%{$search}%")
                  ->orWhere('risk_name', 'like', "%{$search}%")
                  ->orWhere('cause', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_status')) {
            $query->where('risk_status', $request->input('risk_status'));
        }

        $risks = $query->orderBy('risk_name')->paginate(10)->appends($request->query());

        return view('risk-management.identification.index', [
            'risks' => $risks,
            'search' => $request->input('search'),
            'risk_status' => $request->input('risk_status'),
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'assets' => Asset::orderBy('asset_name')->get(),
            'services' => Service::orderBy('service_name')->get(),
        ]);
    }

    public function update(Request $request, Risk $risiko): RedirectResponse
    {
        $validated = $request->validate([
            'cause' => ['required', 'string'],
            'impact' => ['required', 'string'],
            'monitoring' => ['nullable', 'string'],
            'assets' => ['nullable', 'array'],
            'assets.*' => ['integer', 'exists:assets,id'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ]);

        $risiko->update([
            'cause' => $validated['cause'],
            'impact' => $validated['impact'],
            'monitoring' => $validated['monitoring'] ?? null,
        ]);

        $risiko->assets()->sync($request->input('assets', []));
        $risiko->services()->sync($request->input('services', []));

        return redirect()->route('risiko.identifikasi')->with('success', 'Identifikasi risiko berhasil diperbarui.');
    }
}
