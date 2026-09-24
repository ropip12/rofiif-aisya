<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RiskController extends Controller
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
                  ->orWhere('impact', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_status')) {
            $query->where('risk_status', $request->input('risk_status'));
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->input('risk_level'));
        }

        $risks = $query->orderBy('risk_name')->paginate(10)->appends($request->query());

        return view('risk-management.data.index', [
            'risks' => $risks,
            'search' => $request->input('search'),
            'risk_status' => $request->input('risk_status'),
            'risk_level' => $request->input('risk_level'),
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
        ]);
    }

    public function create(Request $request): View
    {
        return view('risk-management.data.create', [
            'assets' => Asset::orderBy('asset_name')->get(),
            'services' => Service::orderBy('service_name')->get(),
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'risk_code' => ['required', 'string', 'max:255', 'unique:risks,risk_code'],
            'risk_name' => ['required', 'string', 'max:255'],
            'cause' => ['required', 'string'],
            'impact' => ['required', 'string'],
            'likelihood' => ['nullable', 'string', 'max:255'],
            'risk_level' => ['nullable', 'in:Rendah,Menengah,Tinggi,Sangat Tinggi'],
            'control_measures' => ['nullable', 'string'],
            'mitigation_plan' => ['nullable', 'string'],
            'risk_status' => ['nullable', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan'],
            'monitoring' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'assets' => ['nullable', 'array'],
            'assets.*' => ['integer', 'exists:assets,id'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ]);

        $risk = Risk::create([
            'risk_code' => $validated['risk_code'],
            'risk_name' => $validated['risk_name'],
            'cause' => $validated['cause'],
            'impact' => $validated['impact'],
            'likelihood' => $validated['likelihood'] ?? null,
            'risk_level' => $validated['risk_level'] ?? null,
            'control_measures' => $validated['control_measures'] ?? null,
            'mitigation_plan' => $validated['mitigation_plan'] ?? null,
            'risk_status' => $validated['risk_status'] ?? null,
            'monitoring' => $validated['monitoring'] ?? null,
            'evaluation' => $validated['evaluation'] ?? null,
        ]);

        $risk->assets()->sync($request->input('assets', []));
        $risk->services()->sync($request->input('services', []));

        return redirect()->route('risiko.index')->with('success', 'Data risiko berhasil ditambahkan.');
    }

    public function show(Request $request, Risk $risiko): View
    {
        return view('risk-management.data.show', [
            'risk' => $risiko->load(['assets', 'services']),
        ]);
    }

    public function edit(Request $request, Risk $risiko): View
    {
        return view('risk-management.data.edit', [
            'risk' => $risiko->load(['assets', 'services']),
            'assets' => Asset::orderBy('asset_name')->get(),
            'services' => Service::orderBy('service_name')->get(),
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
        ]);
    }

    public function update(Request $request, Risk $risiko): RedirectResponse
    {
        $riskCode = trim((string) $request->input('risk_code'));

        $rules = [
            'risk_code' => ['required', 'string', 'max:255'],
            'risk_name' => ['required', 'string', 'max:255'],
            'cause' => ['required', 'string'],
            'impact' => ['required', 'string'],
            'likelihood' => ['nullable', 'string', 'max:255'],
            'risk_level' => ['nullable', 'in:Rendah,Menengah,Tinggi,Sangat Tinggi'],
            'control_measures' => ['nullable', 'string'],
            'mitigation_plan' => ['nullable', 'string'],
            'risk_status' => ['nullable', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan'],
            'monitoring' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
            'assets' => ['nullable', 'array'],
            'assets.*' => ['integer', 'exists:assets,id'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];

        if ($riskCode !== $risiko->risk_code) {
            $rules['risk_code'][] = Rule::unique('risks', 'risk_code');
        }

        $validated = $request->validate($rules);

        $risiko->update([
            'risk_code' => $validated['risk_code'],
            'risk_name' => $validated['risk_name'],
            'cause' => $validated['cause'],
            'impact' => $validated['impact'],
            'likelihood' => $validated['likelihood'] ?? null,
            'risk_level' => $validated['risk_level'] ?? null,
            'control_measures' => $validated['control_measures'] ?? null,
            'mitigation_plan' => $validated['mitigation_plan'] ?? null,
            'risk_status' => $validated['risk_status'] ?? null,
            'monitoring' => $validated['monitoring'] ?? null,
            'evaluation' => $validated['evaluation'] ?? null,
        ]);

        $risiko->assets()->sync($request->input('assets', []));
        $risiko->services()->sync($request->input('services', []));

        return redirect()->route('risiko.show', ['risiko' => $risiko->id])->with('success', 'Data risiko berhasil diperbarui.');
    }

    public function destroy(Request $request, Risk $risiko): RedirectResponse
    {
        $risiko->assets()->detach();
        $risiko->services()->detach();
        $risiko->delete();

        return redirect()->route('risiko.index')->with('success', 'Data risiko berhasil dihapus.');
    }
}
