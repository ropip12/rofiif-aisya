<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('aspek');
            $table->string('sub_klasifikasi');
            $table->text('penjelasan')->nullable();
            $table->string('kode_klasifikasi')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_classifications');
    }
};
