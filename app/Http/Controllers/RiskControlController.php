<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskControlController extends Controller
{
    public function index(Request $request): View
    {
        $query = Risk::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('risk_code', 'like', "%{$search}%")
                  ->orWhere('risk_name', 'like', "%{$search}%")
                  ->orWhere('control_measures', 'like', "%{$search}%")
                  ->orWhere('mitigation_plan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_status')) {
            $query->where('risk_status', $request->input('risk_status'));
        }

        $risks = $query->orderBy('risk_name')->paginate(10)->appends($request->query());

        return view('risk-management.control.index', [
            'risks' => $risks,
            'search' => $request->input('search'),
            'risk_status' => $request->input('risk_status'),
            'riskStatuses' => ['Aktif', 'Dalam Pemantauan', 'Ditutup', 'Dihentikan'],
        ]);
    }

    public function update(Request $request, Risk $risiko): RedirectResponse
    {
        $validated = $request->validate([
            'control_measures' => ['required', 'string'],
            'mitigation_plan' => ['required', 'string'],
            'risk_status' => ['required', 'in:Aktif,Dalam Pemantauan,Ditutup,Dihentikan'],
            'evaluation' => ['nullable', 'string'],
        ]);

        $risiko->update([
            'control_measures' => $validated['control_measures'],
            'mitigation_plan' => $validated['mitigation_plan'],
            'risk_status' => $validated['risk_status'],
            'evaluation' => $validated['evaluation'] ?? null,
        ]);

        return redirect()->route('risiko.pengendalian')->with('success', 'Pengendalian risiko berhasil diperbarui.');
    }
}
