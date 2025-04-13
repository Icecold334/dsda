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
        Schema::create('foto_permintaan_material', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('detail_permintaan_id')->constrained('detail_permintaan_material');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_permintaan_materials');
    }
};
