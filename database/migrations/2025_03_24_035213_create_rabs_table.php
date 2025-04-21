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
        Schema::create('rab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('draft')->default(0);

            // Data kegiatan
            $table->string('program')->nullable();
            $table->string('nama')->nullable();
            $table->string('sub_kegiatan')->nullable();
            $table->text('rincian_sub_kegiatan')->nullable();
            $table->string('kode_rekening')->nullable();
            // Lokasi dan waktu
            $table->text('lokasi')->nullable();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('selesai')->nullable();
            // Status dan catatan
            $table->integer('status')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};
