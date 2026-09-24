<?php

use App\Http\Controllers\AssetFinalHandlingController;
use App\Http\Controllers\AssetHistoryController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RiskAssessmentController;
use App\Http\Controllers\RiskControlController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\RiskIdentificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
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

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/import', [\App\Http\Controllers\AdminImportController::class, 'index'])
            ->name('import.index')
            ->middleware('management:admin');

        Route::post('/import', [\App\Http\Controllers\AdminImportController::class, 'store'])
            ->name('import.store')
            ->middleware('management:admin');

        Route::get('/pengguna', [\App\Http\Controllers\UserManagementController::class, 'index'])
            ->name('pengguna.index')
            ->middleware('management:admin');

        Route::get('/pengguna/create', [\App\Http\Controllers\UserManagementController::class, 'create'])
            ->name('pengguna.create')
            ->middleware('management:admin');

        Route::post('/pengguna', [\App\Http\Controllers\UserManagementController::class, 'store'])
            ->name('pengguna.store')
            ->middleware('management:admin');

        Route::get('/pengguna/{user}', [\App\Http\Controllers\UserManagementController::class, 'show'])
            ->name('pengguna.show')
            ->middleware('management:admin');

        Route::get('/pengguna/{user}/edit', [\App\Http\Controllers\UserManagementController::class, 'edit'])
            ->name('pengguna.edit')
            ->middleware('management:admin');

        Route::put('/pengguna/{user}', [\App\Http\Controllers\UserManagementController::class, 'update'])
            ->name('pengguna.update')
            ->middleware('management:admin');

        Route::delete('/pengguna/{user}', [\App\Http\Controllers\UserManagementController::class, 'destroy'])
            ->name('pengguna.destroy')
            ->middleware('management:admin');
    });

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

    Route::get('/risiko', [RiskController::class, 'index'])
        ->name('risiko.index')
        ->middleware('management:risiko');

    Route::get('/risiko/create', [RiskController::class, 'create'])
        ->name('risiko.create')
        ->middleware('management:risiko');

    Route::post('/risiko', [RiskController::class, 'store'])
        ->name('risiko.store')
        ->middleware('management:risiko');

    Route::get('/risiko/identifikasi', [RiskIdentificationController::class, 'index'])
        ->name('risiko.identifikasi')
        ->middleware('management:risiko');

    Route::put('/risiko/identifikasi/{risiko}', [RiskIdentificationController::class, 'update'])
        ->name('risiko.identifikasi.update')
        ->middleware('management:risiko');

    Route::get('/risiko/penilaian', [RiskAssessmentController::class, 'index'])
        ->name('risiko.penilaian')
        ->middleware('management:risiko');

    Route::put('/risiko/penilaian/{risiko}', [RiskAssessmentController::class, 'update'])
        ->name('risiko.penilaian.update')
        ->middleware('management:risiko');

    Route::get('/risiko/pengendalian', [RiskControlController::class, 'index'])
        ->name('risiko.pengendalian')
        ->middleware('management:risiko');

    Route::put('/risiko/pengendalian/{risiko}', [RiskControlController::class, 'update'])
        ->name('risiko.pengendalian.update')
        ->middleware('management:risiko');

    Route::get('/risiko/monitoring', [\App\Http\Controllers\RiskMonitoringController::class, 'index'])
        ->name('risiko.monitoring')
        ->middleware('management:risiko');

    Route::put('/risiko/monitoring/{risiko}', [\App\Http\Controllers\RiskMonitoringController::class, 'update'])
        ->name('risiko.monitoring.update')
        ->middleware('management:risiko');

    Route::get('/risiko/evaluasi', [\App\Http\Controllers\RiskEvaluationController::class, 'index'])
        ->name('risiko.evaluasi')
        ->middleware('management:risiko');

    Route::put('/risiko/evaluasi/{risiko}', [\App\Http\Controllers\RiskEvaluationController::class, 'update'])
        ->name('risiko.evaluasi.update')
        ->middleware('management:risiko');

    Route::get('/risiko/laporan', [\App\Http\Controllers\RiskReportController::class, 'index'])
        ->name('risiko.laporan')
        ->middleware('management:risiko');

    Route::get('/risiko/{risiko}', [RiskController::class, 'show'])
        ->name('risiko.show')
        ->middleware('management:risiko');

    Route::get('/risiko/{risiko}/edit', [RiskController::class, 'edit'])
        ->name('risiko.edit')
        ->middleware('management:risiko');

    Route::put('/risiko/{risiko}', [RiskController::class, 'update'])
        ->name('risiko.update')
        ->middleware('management:risiko');

    Route::delete('/risiko/{risiko}', [RiskController::class, 'destroy'])
        ->name('risiko.destroy')
        ->middleware('management:risiko');

    Route::get('/risiko-page', [PageController::class, 'risiko'])
        ->name('management.risiko')
        ->middleware('management:risiko');

    Route::get('/layanan', [\App\Http\Controllers\ServiceController::class, 'index'])
        ->name('layanan.index')
        ->middleware('management:layanan');

    Route::get('/layanan/create', [\App\Http\Controllers\ServiceController::class, 'create'])
        ->name('layanan.create')
        ->middleware('management:layanan');

    Route::post('/layanan', [\App\Http\Controllers\ServiceController::class, 'store'])
        ->name('layanan.store')
        ->middleware('management:layanan');

    Route::get('/layanan/pengelolaan', [\App\Http\Controllers\ServiceManagementController::class, 'index'])
        ->name('layanan.pengelolaan')
        ->middleware('management:layanan');

    Route::put('/layanan/pengelolaan/{service}', [\App\Http\Controllers\ServiceManagementController::class, 'update'])
        ->name('layanan.pengelolaan.update')
        ->middleware('management:layanan');

    Route::get('/layanan/monitoring', [\App\Http\Controllers\ServiceMonitoringController::class, 'index'])
        ->name('layanan.monitoring')
        ->middleware('management:layanan');

    Route::put('/layanan/monitoring/{service}', [\App\Http\Controllers\ServiceMonitoringController::class, 'update'])
        ->name('layanan.monitoring.update')
        ->middleware('management:layanan');

    Route::get('/layanan/evaluasi', [\App\Http\Controllers\ServiceEvaluationController::class, 'index'])
        ->name('layanan.evaluasi')
        ->middleware('management:layanan');

    Route::put('/layanan/evaluasi/{service}', [\App\Http\Controllers\ServiceEvaluationController::class, 'update'])
        ->name('layanan.evaluasi.update')
        ->middleware('management:layanan');

    Route::get('/layanan/laporan', [\App\Http\Controllers\ServiceReportController::class, 'index'])
        ->name('layanan.laporan')
        ->middleware('management:layanan');

    Route::get('/layanan/{service}', [\App\Http\Controllers\ServiceController::class, 'show'])
        ->name('layanan.show')
        ->middleware('management:layanan');

    Route::get('/layanan/{service}/edit', [\App\Http\Controllers\ServiceController::class, 'edit'])
        ->name('layanan.edit')
        ->middleware('management:layanan');

    Route::put('/layanan/{service}', [\App\Http\Controllers\ServiceController::class, 'update'])
        ->name('layanan.update')
        ->middleware('management:layanan');

    Route::delete('/layanan/{service}', [\App\Http\Controllers\ServiceController::class, 'destroy'])
        ->name('layanan.destroy')
        ->middleware('management:layanan');

    Route::get('/layanan-page', [PageController::class, 'layanan'])
        ->name('management.layanan')
        ->middleware('management:layanan');
});
