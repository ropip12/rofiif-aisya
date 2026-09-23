<?php

namespace App\Http\Controllers;

use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetHistoryController extends Controller
{
    protected function authorizeAssetAccess(Request $request): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk riwayat aset.');
    }

    public function index(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $query = AssetHistory::with('asset')->latest('activity_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function ($q) use ($search) {
                $q->where('asset_name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            })->orWhere('activity_type', 'like', "%{$search}%")
              ->orWhere('change_info', 'like', "%{$search}%");
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        $items = $query->paginate(10)->appends($request->query());

        return view('asset-management.history.index', [
            'items' => $items,
            'assets' => \App\Models\Asset::orderBy('asset_name')->get()->mapWithKeys(fn ($asset) => [$asset->id => $asset->asset_name . ' (' . $asset->asset_code . ')']),
        ]);
    }
}
