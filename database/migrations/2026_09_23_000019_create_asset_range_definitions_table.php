<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_range_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_range')->nullable();
            $table->string('nama_range')->nullable();
            $table->string('kategori')->nullable();
            $table->text('kriteria')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('skor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_range_definitions');
    }
};
