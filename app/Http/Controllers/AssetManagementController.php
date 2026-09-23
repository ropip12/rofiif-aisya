<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetEvaluation;
use App\Models\AssetMaintenance;
use App\Models\AssetProcurement;
use App\Models\AssetUsage;
use App\Models\AssetWarehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetManagementController extends Controller
{
    protected function authorizeAssetAccess(Request $request): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->management === 'aset'), 403, 'Akses ditolak untuk manajemen aset.');
    }

    protected function assetOptions(): \Illuminate\Support\Collection
    {
        return Asset::orderBy('asset_name')->get()->mapWithKeys(fn ($asset) => [$asset->id => $asset->asset_name . ' (' . $asset->asset_code . ')']);
    }

    public function procurementIndex(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $items = AssetProcurement::with('asset')->latest()->paginate(10);

        return view('asset-management.procurement.index', [
            'items' => $items,
        ]);
    }

    public function procurementCreate(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.procurement.create', ['assets' => $this->assetOptions()]);
    }

    public function procurementStore(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'procurement_date' => ['required', 'date'],
            'procurement_method' => ['required', 'string', 'max:255'],
            'source_vendor' => ['nullable', 'string', 'max:255'],
            'procurement_value' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        AssetProcurement::create($validated);

        return redirect()->route('aset.pengadaan.index')->with('success', 'Data pengadaan aset berhasil ditambahkan.');
    }

    public function procurementShow(Request $request, AssetProcurement $assetProcurement): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.procurement.show', ['item' => $assetProcurement->load('asset')]);
    }

    public function procurementEdit(Request $request, AssetProcurement $assetProcurement): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.procurement.edit', [
            'item' => $assetProcurement,
            'assets' => $this->assetOptions(),
        ]);
    }

    public function procurementUpdate(Request $request, AssetProcurement $assetProcurement): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'procurement_date' => ['required', 'date'],
            'procurement_method' => ['required', 'string', 'max:255'],
            'source_vendor' => ['nullable', 'string', 'max:255'],
            'procurement_value' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $assetProcurement->update($validated);

        return redirect()->route('aset.pengadaan.show', ['asset_procurement' => $assetProcurement->id])->with('success', 'Data pengadaan aset berhasil diperbarui.');
    }

    public function procurementDestroy(Request $request, AssetProcurement $assetProcurement): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetProcurement->delete();

        return redirect()->route('aset.pengadaan.index')->with('success', 'Data pengadaan aset berhasil dihapus.');
    }

    public function usageIndex(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $items = AssetUsage::with('asset')->latest()->paginate(10);

        return view('asset-management.usage.index', ['items' => $items]);
    }

    public function usageCreate(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.usage.create', ['assets' => $this->assetOptions()]);
    }

    public function usageStore(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'location' => ['required', 'string', 'max:255'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'usage_status' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        AssetUsage::create($validated);

        return redirect()->route('aset.penggunaan.index')->with('success', 'Data penggunaan aset berhasil ditambahkan.');
    }

    public function usageShow(Request $request, AssetUsage $assetUsage): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.usage.show', ['item' => $assetUsage->load('asset')]);
    }

    public function usageEdit(Request $request, AssetUsage $assetUsage): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.usage.edit', ['item' => $assetUsage, 'assets' => $this->assetOptions()]);
    }

    public function usageUpdate(Request $request, AssetUsage $assetUsage): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'location' => ['required', 'string', 'max:255'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'usage_status' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $assetUsage->update($validated);

        return redirect()->route('aset.penggunaan.show', ['asset_usage' => $assetUsage->id])->with('success', 'Data penggunaan aset berhasil diperbarui.');
    }

    public function usageDestroy(Request $request, AssetUsage $assetUsage): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetUsage->delete();

        return redirect()->route('aset.penggunaan.index')->with('success', 'Data penggunaan aset berhasil dihapus.');
    }

    public function warehouseIndex(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $items = AssetWarehouse::with('asset')->latest()->paginate(10);

        return view('asset-management.warehouse.index', ['items' => $items]);
    }

    public function warehouseCreate(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.warehouse.create', ['assets' => $this->assetOptions()]);
    }

    public function warehouseStore(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'warehouse_location' => ['required', 'string', 'max:255'],
            'storage_status' => ['required', 'string', 'max:255'],
            'entry_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date', 'after_or_equal:entry_date'],
            'description' => ['nullable', 'string'],
        ]);

        AssetWarehouse::create($validated);

        return redirect()->route('aset.gudang.index')->with('success', 'Data gudang aset berhasil ditambahkan.');
    }

    public function warehouseShow(Request $request, AssetWarehouse $assetWarehouse): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.warehouse.show', ['item' => $assetWarehouse->load('asset')]);
    }

    public function warehouseEdit(Request $request, AssetWarehouse $assetWarehouse): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.warehouse.edit', ['item' => $assetWarehouse, 'assets' => $this->assetOptions()]);
    }

    public function warehouseUpdate(Request $request, AssetWarehouse $assetWarehouse): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'warehouse_location' => ['required', 'string', 'max:255'],
            'storage_status' => ['required', 'string', 'max:255'],
            'entry_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date', 'after_or_equal:entry_date'],
            'description' => ['nullable', 'string'],
        ]);

        $assetWarehouse->update($validated);

        return redirect()->route('aset.gudang.show', ['asset_warehouse' => $assetWarehouse->id])->with('success', 'Data gudang aset berhasil diperbarui.');
    }

    public function warehouseDestroy(Request $request, AssetWarehouse $assetWarehouse): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetWarehouse->delete();

        return redirect()->route('aset.gudang.index')->with('success', 'Data gudang aset berhasil dihapus.');
    }

    public function maintenanceIndex(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $items = AssetMaintenance::with('asset')->latest()->paginate(10);

        return view('asset-management.maintenance.index', ['items' => $items]);
    }

    public function maintenanceCreate(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.maintenance.create', ['assets' => $this->assetOptions()]);
    }

    public function maintenanceStore(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'maintenance_date' => ['required', 'date'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric'],
            'maintenance_result' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        AssetMaintenance::create($validated);

        return redirect()->route('aset.pemeliharaan.index')->with('success', 'Data pemeliharaan aset berhasil ditambahkan.');
    }

    public function maintenanceShow(Request $request, AssetMaintenance $assetMaintenance): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.maintenance.show', ['item' => $assetMaintenance->load('asset')]);
    }

    public function maintenanceEdit(Request $request, AssetMaintenance $assetMaintenance): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.maintenance.edit', ['item' => $assetMaintenance, 'assets' => $this->assetOptions()]);
    }

    public function maintenanceUpdate(Request $request, AssetMaintenance $assetMaintenance): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'maintenance_date' => ['required', 'date'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric'],
            'maintenance_result' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $assetMaintenance->update($validated);

        return redirect()->route('aset.pemeliharaan.show', ['asset_maintenance' => $assetMaintenance->id])->with('success', 'Data pemeliharaan aset berhasil diperbarui.');
    }

    public function maintenanceDestroy(Request $request, AssetMaintenance $assetMaintenance): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetMaintenance->delete();

        return redirect()->route('aset.pemeliharaan.index')->with('success', 'Data pemeliharaan aset berhasil dihapus.');
    }

    public function evaluationIndex(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        $items = AssetEvaluation::with('asset')->latest()->paginate(10);

        return view('asset-management.evaluation.index', ['items' => $items]);
    }

    public function evaluationCreate(Request $request): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.evaluation.create', ['assets' => $this->assetOptions()]);
    }

    public function evaluationStore(Request $request): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'evaluation_date' => ['required', 'date'],
            'condition' => ['required', 'string', 'max:255'],
            'evaluation_result' => ['required', 'string', 'max:255'],
            'recommendation' => ['nullable', 'string'],
            'follow_up_status' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        AssetEvaluation::create($validated);

        return redirect()->route('aset.evaluasi.index')->with('success', 'Data evaluasi aset berhasil ditambahkan.');
    }

    public function evaluationShow(Request $request, AssetEvaluation $assetEvaluation): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.evaluation.show', ['item' => $assetEvaluation->load('asset')]);
    }

    public function evaluationEdit(Request $request, AssetEvaluation $assetEvaluation): View
    {
        $this->authorizeAssetAccess($request);

        return view('asset-management.evaluation.edit', ['item' => $assetEvaluation, 'assets' => $this->assetOptions()]);
    }

    public function evaluationUpdate(Request $request, AssetEvaluation $assetEvaluation): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'evaluation_date' => ['required', 'date'],
            'condition' => ['required', 'string', 'max:255'],
            'evaluation_result' => ['required', 'string', 'max:255'],
            'recommendation' => ['nullable', 'string'],
            'follow_up_status' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $assetEvaluation->update($validated);

        return redirect()->route('aset.evaluasi.show', ['asset_evaluation' => $assetEvaluation->id])->with('success', 'Data evaluasi aset berhasil diperbarui.');
    }

    public function evaluationDestroy(Request $request, AssetEvaluation $assetEvaluation): RedirectResponse
    {
        $this->authorizeAssetAccess($request);

        $assetEvaluation->delete();

        return redirect()->route('aset.evaluasi.index')->with('success', 'Data evaluasi aset berhasil dihapus.');
    }
}
