<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetFinalHandling;
use App\Models\AssetHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AssetFinalHandlingController extends Controller
{
    protected function authorizeAssetAccess(Request $request): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk penanganan akhir aset.');
    }

    protected function assetOptions(): Collection
    {
        return Asset::orderBy('asset_name')->get()->mapWithKeys(fn ($asset) => [$asset->id => $asset->asset_name . ' (' . $asset->asset_code . ')']);
    }

    protected function validationRules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'final_handling_type' => ['required', 'string', 'in:' . implode(',', AssetFinalHandling::VALID_TYPES)],
            'handling_date' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function index(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $query = AssetFinalHandling::with('asset')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function ($q) use ($search) {
                $q->where('asset_name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            })->orWhere('reason', 'like', "%{$search}%");
        }

        if ($request->filled('final_handling_type')) {
            $query->where('final_handling_type', $request->final_handling_type);
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        $items = $query->paginate(10)->appends($request->query());

        return view('asset-management.final-handling.index', [
            'items' => $items,
            'assets' => $this->assetOptions(),
            'finalHandlingTypes' => AssetFinalHandling::VALID_TYPES,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.final-handling.create', ['assets' => $this->assetOptions(), 'finalHandlingTypes' => AssetFinalHandling::VALID_TYPES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate($this->validationRules());

        $item = AssetFinalHandling::create($validated);

        AssetHistory::record(
            Asset::findOrFail($validated['asset_id']),
            'Penanganan akhir dibuat',
            'Jenis penanganan: ' . $validated['final_handling_type'],
            $validated['description'] ?? $validated['reason'],
            $validated['handling_date']
        );

        return redirect()->route('aset.penanganan-akhir.index')->with('success', 'Data penanganan akhir aset berhasil ditambahkan.');
    }

    public function show(Request $request, AssetFinalHandling $assetFinalHandling): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.final-handling.show', ['item' => $assetFinalHandling->load('asset')]);
    }

    public function edit(Request $request, AssetFinalHandling $assetFinalHandling): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.final-handling.edit', [
            'item' => $assetFinalHandling,
            'assets' => $this->assetOptions(),
            'finalHandlingTypes' => AssetFinalHandling::VALID_TYPES,
        ]);
    }

    public function update(Request $request, AssetFinalHandling $assetFinalHandling): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate($this->validationRules());

        $assetFinalHandling->update($validated);

        AssetHistory::record(
            Asset::findOrFail($validated['asset_id']),
            'Penanganan akhir diubah',
            'Jenis penanganan: ' . $validated['final_handling_type'],
            $validated['description'] ?? $validated['reason'],
            $validated['handling_date']
        );

        return redirect()->route('aset.penanganan-akhir.show', ['asset_final_handling' => $assetFinalHandling->id])->with('success', 'Data penanganan akhir aset berhasil diperbarui.');
    }

    public function destroy(Request $request, AssetFinalHandling $assetFinalHandling): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetFinalHandling->delete();

        return redirect()->route('aset.penanganan-akhir.index')->with('success', 'Data penanganan akhir aset berhasil dihapus.');
    }
}
