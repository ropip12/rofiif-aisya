<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Risk::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('risk_code', 'like', "%{$search}%")
                  ->orWhere('risk_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->input('risk_level'));
        }

        $risks = $query->orderBy('risk_name')->paginate(10)->appends($request->query());

        return view('risk-management.assessment.index', [
            'risks' => $risks,
            'search' => $request->input('search'),
            'risk_level' => $request->input('risk_level'),
            'riskLevels' => ['Rendah', 'Menengah', 'Tinggi', 'Sangat Tinggi'],
        ]);
    }

    public function update(Request $request, Risk $risiko): RedirectResponse
    {
        $validated = $request->validate([
            'likelihood' => ['required', 'string', 'max:255'],
            'risk_level' => ['required', 'in:Rendah,Menengah,Tinggi,Sangat Tinggi'],
        ]);

        $risiko->update([
            'likelihood' => $validated['likelihood'],
            'risk_level' => $validated['risk_level'],
        ]);

        return redirect()->route('risiko.penilaian')->with('success', 'Penilaian risiko berhasil diperbarui.');
    }
}
