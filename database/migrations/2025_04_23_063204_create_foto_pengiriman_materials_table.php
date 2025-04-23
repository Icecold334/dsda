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
        Schema::create('foto_pengiriman_material', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('detail_pengiriman_id')->constrained('detail_pengiriman_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pengiriman_materials');
    }
};
