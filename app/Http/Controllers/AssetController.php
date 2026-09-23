<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        $query = Asset::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('asset_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classification')) {
            $query->where('asset_classification', $request->input('classification'));
        }

        if ($request->filled('condition')) {
            $query->where('condition_status', $request->input('condition'));
        }

        if ($request->filled('usage')) {
            $query->where('usage_status', $request->input('usage'));
        }

        $assets = $query->orderBy('asset_name')->paginate(10);

        return view('assets.index', [
            'assets' => $assets,
            'search' => $request->input('search'),
            'classification' => $request->input('classification'),
            'condition' => $request->input('condition'),
            'usage' => $request->input('usage'),
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        return view('assets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        $validated = $request->validate([
            'asset_code' => ['required', 'string', 'max:255', 'unique:assets,asset_code'],
            'asset_name' => ['required', 'string', 'max:255'],
            'asset_classification' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['nullable', 'date'],
            'acquisition_value' => ['nullable', 'numeric'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'condition_status' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'usage_status' => ['nullable', 'string', 'max:255'],
            'useful_life_years' => ['nullable', 'integer', 'min:0'],
            'data_storage_information' => ['nullable', 'string'],
            'lifecycle_status' => ['nullable', 'string', 'max:255'],
            'final_handling' => ['nullable', 'string'],
        ]);

        Asset::create($validated);

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function show(Request $request, Asset $asset): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        return view('assets.show', compact('asset'));
    }

    public function edit(Request $request, Asset $asset): View
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        $validated = $request->validate([
            'asset_code' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($asset) {
                if (Asset::where('asset_code', $value)->whereKeyNot($asset->id)->exists()) {
                    $fail('The asset code has already been taken.');
                }
            }],
            'asset_name' => ['required', 'string', 'max:255'],
            'asset_classification' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['nullable', 'date'],
            'acquisition_value' => ['nullable', 'numeric'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'condition_status' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'usage_status' => ['nullable', 'string', 'max:255'],
            'useful_life_years' => ['nullable', 'integer', 'min:0'],
            'data_storage_information' => ['nullable', 'string'],
            'lifecycle_status' => ['nullable', 'string', 'max:255'],
            'final_handling' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        return redirect()->route('aset.show', ['aset' => $asset->id])->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Request $request, Asset $asset): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk data aset.');

        $asset->delete();

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil dihapus.');
    }
}
