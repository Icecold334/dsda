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
        Schema::create('detail_permintaan_material', function (Blueprint $table) {
            $table->id();
            $table->integer('saluran_jenis')->nullable();
            $table->integer('saluran_id')->nullable();
            $table->string('driver')->nullable();
            $table->string('nopol')->nullable();
            $table->string('ttd_driver')->nullable();
            $table->string('ttd_security')->nullable();
            $table->string('security')->nullable();
            $table->string('kode_permintaan')->unique();
            $table->string('nama')->nullable();
            $table->string('nodin')->nullable();
            $table->string('spb_path')->nullable();
            $table->string('sppb')->nullable();
            $table->string('sppb_path')->nullable();
            $table->string('suratJalan')->nullable();
            $table->string('suratJalan_path')->nullable();
            $table->string('bast')->nullable();
            $table->string('bast_path')->nullable();
            $table->string('p')->nullable();
            $table->string('l')->nullable();
            $table->string('k')->nullable();
            $table->date('tanggal_permintaan');
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahans')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rab_id')->nullable()->constrained('rab')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_ditolak')->nullable();
            $table->boolean('status')->nullable();
            $table->text('lokasi')->nullable();
            $table->text('kontak_person')->nullable();
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
