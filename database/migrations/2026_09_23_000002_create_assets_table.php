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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->string('asset_classification');
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_value', 15, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->string('condition_status')->nullable();
            $table->string('location')->nullable();
            $table->string('responsible_person')->nullable();
            $table->string('usage_status')->nullable();
            $table->integer('useful_life_years')->nullable();
            $table->text('data_storage_information')->nullable();
            $table->string('lifecycle_status')->nullable();
            $table->text('final_handling')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
