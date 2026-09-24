<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Risk::query()->with(['assets', 'services']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('risk_code', 'like', "%{$search}%")
                    ->orWhere('risk_name', 'like', "%{$search}%")
                    ->orWhere('evaluation', 'like', "%{$search}%")
                    ->orWhere('cause', 'like', "%{$search}%")
                    ->orWhere('impact', 'like', "%{$search}%");
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

        return view('risk-management.evaluation.index', [
            'risks' => $risks,
            'assets' => Asset::orderBy('asset_name')->get(),
            'services' => Service::orderBy('service_name')->get(),
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'likelihoods' => ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'],
            'filters' => $request->only(['search', 'risk_level', 'risk_status', 'likelihood', 'asset_id', 'service_id']),
        ]);
    }

    public function update(Request $request, Risk $risiko): RedirectResponse
    {
        $validated = $request->validate([
            'risk_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan'],
            'evaluation' => ['nullable', 'string'],
        ]);

        $risiko->update([
            'risk_status' => $validated['risk_status'],
            'evaluation' => $validated['evaluation'] ?? $risiko->evaluation,
        ]);

        return redirect()->route('risiko.evaluasi')->with('success', 'Hasil evaluasi risiko berhasil diperbarui.');
    }
}
