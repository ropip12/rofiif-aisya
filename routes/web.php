<?php

use App\Http\Controllers\AssetFinalHandlingController;
use App\Http\Controllers\AssetHistoryController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [PageController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('management:dashboard');

    Route::prefix('aset')->name('aset.')->middleware('management:aset')->group(function () {
        Route::get('/pengadaan', [AssetManagementController::class, 'procurementIndex'])->name('pengadaan.index');
        Route::get('/pengadaan/create', [AssetManagementController::class, 'procurementCreate'])->name('pengadaan.create');
        Route::post('/pengadaan', [AssetManagementController::class, 'procurementStore'])->name('pengadaan.store');
        Route::get('/pengadaan/{asset_procurement}', [AssetManagementController::class, 'procurementShow'])->name('pengadaan.show');
        Route::get('/pengadaan/{asset_procurement}/edit', [AssetManagementController::class, 'procurementEdit'])->name('pengadaan.edit');
        Route::put('/pengadaan/{asset_procurement}', [AssetManagementController::class, 'procurementUpdate'])->name('pengadaan.update');
        Route::delete('/pengadaan/{asset_procurement}', [AssetManagementController::class, 'procurementDestroy'])->name('pengadaan.destroy');

        Route::get('/penggunaan', [AssetManagementController::class, 'usageIndex'])->name('penggunaan.index');
        Route::get('/penggunaan/create', [AssetManagementController::class, 'usageCreate'])->name('penggunaan.create');
        Route::post('/penggunaan', [AssetManagementController::class, 'usageStore'])->name('penggunaan.store');
        Route::get('/penggunaan/{asset_usage}', [AssetManagementController::class, 'usageShow'])->name('penggunaan.show');
        Route::get('/penggunaan/{asset_usage}/edit', [AssetManagementController::class, 'usageEdit'])->name('penggunaan.edit');
        Route::put('/penggunaan/{asset_usage}', [AssetManagementController::class, 'usageUpdate'])->name('penggunaan.update');
        Route::delete('/penggunaan/{asset_usage}', [AssetManagementController::class, 'usageDestroy'])->name('penggunaan.destroy');

        Route::get('/gudang', [AssetManagementController::class, 'warehouseIndex'])->name('gudang.index');
        Route::get('/gudang/create', [AssetManagementController::class, 'warehouseCreate'])->name('gudang.create');
        Route::post('/gudang', [AssetManagementController::class, 'warehouseStore'])->name('gudang.store');
        Route::get('/gudang/{asset_warehouse}', [AssetManagementController::class, 'warehouseShow'])->name('gudang.show');
        Route::get('/gudang/{asset_warehouse}/edit', [AssetManagementController::class, 'warehouseEdit'])->name('gudang.edit');
        Route::put('/gudang/{asset_warehouse}', [AssetManagementController::class, 'warehouseUpdate'])->name('gudang.update');
        Route::delete('/gudang/{asset_warehouse}', [AssetManagementController::class, 'warehouseDestroy'])->name('gudang.destroy');

        Route::get('/pemeliharaan', [AssetManagementController::class, 'maintenanceIndex'])->name('pemeliharaan.index');
        Route::get('/pemeliharaan/create', [AssetManagementController::class, 'maintenanceCreate'])->name('pemeliharaan.create');
        Route::post('/pemeliharaan', [AssetManagementController::class, 'maintenanceStore'])->name('pemeliharaan.store');
        Route::get('/pemeliharaan/{asset_maintenance}', [AssetManagementController::class, 'maintenanceShow'])->name('pemeliharaan.show');
        Route::get('/pemeliharaan/{asset_maintenance}/edit', [AssetManagementController::class, 'maintenanceEdit'])->name('pemeliharaan.edit');
        Route::put('/pemeliharaan/{asset_maintenance}', [AssetManagementController::class, 'maintenanceUpdate'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/{asset_maintenance}', [AssetManagementController::class, 'maintenanceDestroy'])->name('pemeliharaan.destroy');

        Route::get('/evaluasi', [AssetManagementController::class, 'evaluationIndex'])->name('evaluasi.index');
        Route::get('/evaluasi/create', [AssetManagementController::class, 'evaluationCreate'])->name('evaluasi.create');
        Route::post('/evaluasi', [AssetManagementController::class, 'evaluationStore'])->name('evaluasi.store');
        Route::get('/evaluasi/{asset_evaluation}', [AssetManagementController::class, 'evaluationShow'])->name('evaluasi.show');
        Route::get('/evaluasi/{asset_evaluation}/edit', [AssetManagementController::class, 'evaluationEdit'])->name('evaluasi.edit');
        Route::put('/evaluasi/{asset_evaluation}', [AssetManagementController::class, 'evaluationUpdate'])->name('evaluasi.update');
        Route::delete('/evaluasi/{asset_evaluation}', [AssetManagementController::class, 'evaluationDestroy'])->name('evaluasi.destroy');

        Route::get('/penanganan-akhir', [AssetFinalHandlingController::class, 'index'])->name('penanganan-akhir.index');
        Route::get('/penanganan-akhir/create', [AssetFinalHandlingController::class, 'create'])->name('penanganan-akhir.create');
        Route::post('/penanganan-akhir', [AssetFinalHandlingController::class, 'store'])->name('penanganan-akhir.store');
        Route::get('/penanganan-akhir/{asset_final_handling}', [AssetFinalHandlingController::class, 'show'])->name('penanganan-akhir.show');
        Route::get('/penanganan-akhir/{asset_final_handling}/edit', [AssetFinalHandlingController::class, 'edit'])->name('penanganan-akhir.edit');
        Route::put('/penanganan-akhir/{asset_final_handling}', [AssetFinalHandlingController::class, 'update'])->name('penanganan-akhir.update');
        Route::delete('/penanganan-akhir/{asset_final_handling}', [AssetFinalHandlingController::class, 'destroy'])->name('penanganan-akhir.destroy');

        Route::get('/monitoring', [\App\Http\Controllers\AssetMonitoringController::class, 'index'])->name('monitoring');
        Route::get('/laporan', [\App\Http\Controllers\AssetReportController::class, 'index'])->name('laporan');
        Route::get('/laporan/export', [\App\Http\Controllers\AssetReportController::class, 'export'])->name('laporan.export');

        Route::get('/riwayat', [AssetHistoryController::class, 'index'])->name('riwayat.index');
    });

    Route::resource('aset', \App\Http\Controllers\AssetController::class)
        ->middleware('management:aset');

    Route::get('/aset-page', [PageController::class, 'aset'])
        ->name('management.aset')
        ->middleware('management:aset');

    Route::get('/risiko', [PageController::class, 'risiko'])
        ->name('management.risiko')
        ->middleware('management:risiko');

    Route::get('/layanan', [PageController::class, 'layanan'])
        ->name('management.layanan')
        ->middleware('management:layanan');
});
