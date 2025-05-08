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
        Schema::create('permintaan_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_permintaan_id')->constrained('detail_permintaan_material')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rab_id')->nullable()->constrained('rab')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('img')->nullable();
            $table->foreignId('merk_id')->constrained('merk_stok')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('jumlah_approve')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_materials');
    }
};
