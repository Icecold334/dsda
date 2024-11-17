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
            $table->string('kode_barang');
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
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->string('nama');
        });

        Schema::create('posisi_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('bagian_id')->constrained('bagian_stok')->onDelete('cascade');
            $table->string('nama');
        });

        Schema::create('persetujuan_pengiriman_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_pengiriman_id')->constrained('detail_pengiriman_stok');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('status')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_pengiriman_stok', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengiriman_stok')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('penerima')->nullable();
            $table->string('pj1')->nullable();
            $table->string('pj2')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('super_id')->nullable()->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('pengiriman_stok', function (Blueprint $table) {
            $table->id();
            $table->string('img')->nullable();
            $table->foreignId('detail_pengiriman_id')->constrained('detail_pengiriman_stok');
            $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok');
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->date('tanggal_pengiriman');
            $table->integer('jumlah');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('cascade');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('cascade');
            $table->timestamps();
        });

        // Schema::create('list_kontrak_stok', function (Blueprint $table) {
        //     $table->timestamps();
        //     $table->id();
        //     $table->foreignId('merk_id')->constrained('merk_stok');
        //     $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok');
        //     $table->integer('jumlah_total');
        // });
        Schema::create('kontrak_vendor_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nomor_kontrak')->nullable();
            // $table->string('img')->nullable();
            $table->foreignId('vendor_id')->constrained('toko');
            $table->date('tanggal_kontrak');
            $table->string('penulis')->nullable();
            $table->string('pj1')->nullable();
            $table->string('pj2')->nullable();
            $table->foreignId('metode_id')->nullable()->constrained('metode_pengadaan')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('super_id')->nullable()->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->boolean('type');
            $table->boolean('status')->nullable();
            // $table->integer('jumlah_total');
        });

        Schema::create('metode_pengadaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('dokumen_kontrak_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak_vendor_stok');
            $table->string('file')->nullable();
            $table->timestamps();
        });

        Schema::create('persetujuan_kontrak_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak_vendor_stok');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('status')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });


        Schema::create('transaksi_stok', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi_stok')->nullable();
            $table->string('img')->nullable();
            $table->enum('tipe', ['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']);
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->foreignId('vendor_id')->nullable()->constrained('toko');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak_vendor_stok');
            $table->integer('tanggal');
            $table->integer('jumlah');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_penerimaan')->nullable();
            $table->timestamps();
        });

        // Schema::create('transaksi_darurat_stok', function (Blueprint $table) {
        //     $table->timestamps();
        //     $table->id();
        //     $table->foreignId('merk_id')->constrained('merk_stok');
        //     $table->foreignId('vendor_id')->constrained('vendor_stok');
        //     $table->foreignId('user_id')->constrained('users');
        //     $table->foreignId('kontrak_retrospektif_id')->nullable()->constrained('kontrak_retrospektif_stok');
        //     $table->date('tanggal');
        //     $table->integer('jumlah');
        //     $table->enum('tipe', ['Penggunaan Langsung']);
        //     $table->text('deskripsi');
        //     $table->string('lokasi_penerimaan');
        // });

        // Schema::create('kontrak_retrospektif_stok', function (Blueprint $table) {
        //     $table->timestamps();
        //     $table->id();
        //     $table->string('bukti_kontrak');
        //     // $table->foreignId('vendor_id')->constrained('vendor_stok');
        //     // $table->foreignId('merk_id')->constrained('merk_stok');
        //     $table->date('tanggal_kontrak');
        //     // $table->integer('jumlah_total');
        //     $table->text('deskripsi_kontrak');
        // });

        Schema::create('stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->integer('jumlah');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('cascade');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('cascade');
        });



        Schema::create('permintaan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('merk_id')->constrained('merk_stok'); // Changed barang_id to merk_id
            $table->integer('jumlah');
            $table->date('tanggal_permintaan');
            $table->enum('status', ['Disetujui', 'Ditunda', 'Ditolak']);
            $table->timestamps();
            // $table->foreignId('lokasi_id')->constrained('lokasi_stok');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_stok');
        Schema::dropIfExists('stok');
        Schema::dropIfExists('metode_pengadaan');
        // Schema::dropIfExists('kontrak_retrospektif_stok');
        // Schema::dropIfExists('transaksi_darurat_stok');
        Schema::dropIfExists('transaksi_stok');
        Schema::dropIfExists('detail_pengiriman_stok');
        Schema::dropIfExists('pengiriman_stok');
        Schema::dropIfExists('persetujuan_kontrak_stok');
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
