<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetClassification;
use App\Models\Risk;
use App\Models\Service;
use App\Models\ServiceTicket;
use App\Models\ServiceEvaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExcelAlignedDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_service_and_risk_database_structure_matches_excel_requirements(): void
    {
        $this->assertTrue(Schema::hasTable('assets'));
        $this->assertTrue(Schema::hasTable('asset_classifications'));
        $this->assertTrue(Schema::hasTable('services'));
        $this->assertTrue(Schema::hasTable('service_tickets'));
        $this->assertTrue(Schema::hasTable('service_evaluations'));
        $this->assertTrue(Schema::hasTable('risks'));

        $assetColumns = [
            'asset_code',
            'sub_classification_id',
            'asset_name',
            'nomor_dokumen',
            'tahun_penyusunan',
            'status_aset',
            'lokasi_keberadaan_aset',
            'format_penyimpanan_aset',
            'pemilik_aset',
            'retensi_aset',
            'kerahasiaan',
            'integritas',
            'ketersediaan',
            'kritikalitas_aset',
            'asset_type',
            'category',
        ];

        foreach ($assetColumns as $column) {
            $this->assertTrue(Schema::hasColumn('assets', $column), "Column {$column} should exist in assets table.");
        }

        $this->assertTrue(method_exists(Asset::class, 'classification'));
        $this->assertTrue(method_exists(Service::class, 'tickets'));
        $this->assertTrue(method_exists(Service::class, 'evaluations'));
        $this->assertTrue(method_exists(Risk::class, 'assets'));
        $this->assertTrue(method_exists(Risk::class, 'services'));
        $this->assertTrue(class_exists(AssetClassification::class));
        $this->assertTrue(class_exists(ServiceTicket::class));
        $this->assertTrue(class_exists(ServiceEvaluation::class));
    }
}
