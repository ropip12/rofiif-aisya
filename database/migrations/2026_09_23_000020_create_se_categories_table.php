<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('se_categories', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori')->nullable();
            $table->string('karakteristik')->nullable();
            $table->string('status')->nullable();
            $table->integer('skor')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('se_categories');
    }
};
