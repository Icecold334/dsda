<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('jenis_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['Material', 'Spare Part', 'Umum']);
        });

        Schema::create('barang_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('jenis_id')->constrained('jenis_stok');
            $table->foreignId('satuan_besar_id')->constrained('satuan_besar'); // Foreign key to satuan_besar
            $table->foreignId('satuan_kecil_id')->constrained('satuan_kecil'); // Foreign key to satuan_kecil
            $table->string('nama');
            $table->text('deskripsi')->nullable();
        });

        Schema::create('satuan_besar', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g., "Box"
            $table->timestamps();
        });

        Schema::create('satuan_kecil', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g., "Rim"
            $table->timestamps();
        });


        Schema::create('merk_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_stok');
            $table->string('nama');
        });

        Schema::create('vendor_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nama');
            $table->text('alamat');
            $table->string('kontak');
        });

        Schema::create('lokasi_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nama');
            $table->text('alamat');
        });

        Schema::create('bagian_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi_stok');
            $table->string('nama');
        });

        Schema::create('posisi_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('bagian_id')->constrained('bagian_stok');
            $table->string('nama');
        });

        Schema::create('list_kontrak_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok');
            $table->integer('jumlah_total');
        });
        Schema::create('kontrak_vendor_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nomor_kontrak')->nullable();
            $table->foreignId('vendor_id')->constrained('vendor_stok');
            $table->date('tanggal_kontrak');
            $table->string('penulis1');
            $table->integer('jumlah_total');
        });

        Schema::create('pengiriman_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('kode_pengiriman_stok')->nullable();
            $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok');
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->date('tanggal_pengiriman');
            $table->integer('jumlah');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok');
        });

        Schema::create('transaksi_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('kode_transaksi_stok')->nullable();
            $table->enum('tipe', ['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']);
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok');
            $table->foreignId('pengiriman_id')->nullable()->constrained('pengiriman_stok');
        });

        Schema::create('transaksi_darurat_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->foreignId('vendor_id')->constrained('vendor_stok');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kontrak_retrospektif_id')->nullable()->constrained('kontrak_retrospektif_stok');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->enum('tipe', ['Penggunaan Langsung']);
            $table->text('deskripsi');
            $table->string('lokasi_penerimaan');
        });

        Schema::create('kontrak_retrospektif_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('bukti_kontrak');
            // $table->foreignId('vendor_id')->constrained('vendor_stok');
            // $table->foreignId('merk_id')->constrained('merk_stok');
            $table->date('tanggal_kontrak');
            // $table->integer('jumlah_total');
            $table->text('deskripsi_kontrak');
        });

        Schema::create('stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->integer('jumlah');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok');
        });

        Schema::create('permintaan_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('merk_id')->constrained('merk_stok'); // Changed barang_id to merk_id
            $table->integer('jumlah');
            $table->date('tanggal_permintaan');
            $table->enum('status', ['Disetujui', 'Ditunda', 'Ditolak']);
            // $table->foreignId('lokasi_id')->constrained('lokasi_stok');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_stok');
        Schema::dropIfExists('stok');
        Schema::dropIfExists('kontrak_retrospektif_stok');
        Schema::dropIfExists('transaksi_darurat_stok');
        Schema::dropIfExists('transaksi_stok');
        Schema::dropIfExists('pengiriman_stok');
        Schema::dropIfExists('kontrak_vendor_stok');
        Schema::dropIfExists('posisi_stok');
        Schema::dropIfExists('bagian_stok');
        Schema::dropIfExists('lokasi_stok');
        Schema::dropIfExists('vendor_stok');
        Schema::dropIfExists('merk_stok');
        Schema::dropIfExists('barang_stok');
        Schema::dropIfExists('jenis_stok');
    }
};
