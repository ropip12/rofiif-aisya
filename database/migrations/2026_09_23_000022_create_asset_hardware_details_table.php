<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_hardware_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->text('spesifikasi_aset')->nullable();
            $table->string('tahun_pengadaan')->nullable();
            $table->string('lokasi_keberadaan_aset')->nullable();
            $table->string('pemilik_aset')->nullable();
            $table->string('kondisi_aset')->nullable();
            $table->string('kategori')->nullable();
            $table->string('kritikalitas_aset')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_hardware_details');
    }
};
