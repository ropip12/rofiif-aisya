<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_personnel_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('nama_personil')->nullable();
            $table->string('kategori_aset')->nullable();
            $table->string('nip_nib')->nullable();
            $table->string('fungsi')->nullable();
            $table->string('unit')->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_personnel_details');
    }
};
