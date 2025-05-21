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
            $table->foreignId('program_id')->nullable()->constrained('programs');
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans');
            $table->foreignId('sub_kegiatan_id')->nullable()->constrained('sub_kegiatans');
            $table->foreignId('aktivitas_sub_kegiatan_id')->nullable()->constrained('aktivitas_sub_kegiatans');
            $table->foreignId('uraian_rekening_id')->nullable()->constrained('uraian_rekenings');
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahans');
            $table->boolean('draft')->default(0);
            $table->string('jenis_pekerjaan')->nullable();

            // Data kegiatan
            // $table->string('nama')->nullable();
            // $table->string('sub_kegiatan')->nullable();
            // $table->text('rincian_sub_kegiatan')->nullable();
            // $table->string('kode_rekening')->nullable();
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
