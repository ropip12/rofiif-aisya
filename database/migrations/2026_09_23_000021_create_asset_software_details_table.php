<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_software_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('tahun_rilis')->nullable();
            $table->text('uraian_singkat_aplikasi')->nullable();
            $table->string('alamat_aplikasi_url')->nullable();
            $table->string('alamat_ip')->nullable();
            $table->string('aplikasi_ip_publik_internal')->nullable();
            $table->string('platform')->nullable();
            $table->string('sistem_operasi_server')->nullable();
            $table->string('pemilik_aset_opd')->nullable();
            $table->string('data_center')->nullable();
            $table->string('kontak_pengelola_pic')->nullable();
            $table->string('status')->nullable();
            $table->string('kategori_se')->nullable();
            $table->string('kritikalitas_aset')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_software_details');
    }
};
