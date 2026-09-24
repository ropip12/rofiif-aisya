<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('tiket_id')->unique();
            $table->date('tanggal_masuk')->nullable();
            $table->string('nama_pemohon')->nullable();
            $table->string('jenis_permintaan')->nullable();
            $table->string('tingkat_dampak')->nullable();
            $table->string('risiko_tik_terkait')->nullable();
            $table->text('solusi_tindakan_operasional')->nullable();
            $table->string('status_tiket')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
