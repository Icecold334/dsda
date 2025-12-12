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
        Schema::create('adendum_list_rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adendum_rab_id')->constrained('adendum_rabs')->onDelete('cascade');
            $table->foreignId('list_rab_id')->nullable()->constrained('list_rab')->onDelete('set null'); // Link ke list_rab asli (jika edit)
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->bigInteger('jumlah_lama')->nullable(); // Jumlah lama (jika edit)
            $table->bigInteger('jumlah_baru'); // Jumlah baru
            $table->string('action')->default('edit'); // 'add', 'edit', 'delete'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adendum_list_rabs');
    }
};
