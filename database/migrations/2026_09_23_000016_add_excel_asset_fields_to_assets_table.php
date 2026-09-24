<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('sub_classification_id')->nullable()->after('asset_code')->constrained('asset_classifications')->nullOnDelete();
            $table->string('nomor_dokumen')->nullable()->after('asset_name');
            $table->string('tahun_penyusunan')->nullable()->after('nomor_dokumen');
            $table->string('status_aset')->nullable()->after('tahun_penyusunan');
            $table->string('lokasi_keberadaan_aset')->nullable()->after('status_aset');
            $table->string('format_penyimpanan_aset')->nullable()->after('lokasi_keberadaan_aset');
            $table->string('pemilik_aset')->nullable()->after('format_penyimpanan_aset');
            $table->string('retensi_aset')->nullable()->after('pemilik_aset');
            $table->string('kerahasiaan')->nullable()->after('retensi_aset');
            $table->string('integritas')->nullable()->after('kerahasiaan');
            $table->string('ketersediaan')->nullable()->after('integritas');
            $table->string('kritikalitas_aset')->nullable()->after('ketersediaan');
            $table->string('asset_type')->nullable()->after('kritikalitas_aset');
            $table->string('category')->nullable()->after('asset_type');
            $table->text('spesifikasi_aset')->nullable()->after('category');
            $table->string('tahun_pengadaan')->nullable()->after('spesifikasi_aset');
            $table->string('kondisi_aset')->nullable()->after('tahun_pengadaan');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sub_classification_id');
            $table->dropColumn([
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
                'spesifikasi_aset',
                'tahun_pengadaan',
                'kondisi_aset',
            ]);
        });
    }
};
