<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->string('risk_code')->unique();
            $table->string('risk_name');
            $table->text('cause')->nullable();
            $table->text('impact')->nullable();
            $table->string('likelihood')->nullable();
            $table->string('risk_level')->nullable();
            $table->text('control_measures')->nullable();
            $table->text('mitigation_plan')->nullable();
            $table->string('risk_status')->nullable();
            $table->text('monitoring')->nullable();
            $table->text('evaluation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
