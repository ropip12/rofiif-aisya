<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->decimal('realisasi_uptime', 5, 2)->nullable();
            $table->decimal('target_sla', 5, 2)->nullable();
            $table->string('status_capaian')->nullable();
            $table->integer('total_gangguan_bulan_ini')->nullable();
            $table->string('rata_rata_waktu_resolusi_mttr')->nullable();
            $table->text('rekomendasi_peningkatan_kontrol_mitigasi')->nullable();
            $table->string('periode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_evaluations');
    }
};
