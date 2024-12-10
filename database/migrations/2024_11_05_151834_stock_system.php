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
        });

        Schema::create('barang_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('kode_barang');
            $table->integer('konversi')->nullable();
            $table->foreignId('jenis_id')->constrained('jenis_stok');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_stok');
            $table->foreignId('satuan_besar_id')->constrained('satuan'); // Foreign key to satuan
            $table->foreignId('satuan_kecil_id')->nullable()->constrained('satuan'); // Foreign key to satuan_kecil
            $table->string('nama');
            $table->text('deskripsi')->nullable();
        });
        Schema::create('merk_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_stok');
            $table->string('nama')->nullable();
            $table->string('tipe')->nullable();
            $table->string('ukuran')->nullable();
            $table->timestamps();
        });

        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g., "Box"
            $table->timestamps();
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
            $table->foreignId('unit_id')->constrained('unit_kerja')->onDelete('cascade'); // Link to unit_kerja table
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
            $table->text('file')->nullable();
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
            $table->integer('jumlah_diterima')->nullable();
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
            $table->foreignId('vendor_id')->constrained('toko', 'id'); // Explicitly set column
            $table->date('tanggal_kontrak');
            $table->foreignId('metode_id')->nullable()->constrained('metode_pengadaan')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('super_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('type');
            $table->boolean('status')->nullable();
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
            $table->text('file')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });


        Schema::create('transaksi_stok', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->nullable();
            $table->string('keterangan_status')->nullable();

            $table->string('kode_transaksi_stok')->nullable();
            $table->string('img')->nullable();
            $table->enum('tipe', ['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']);
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->foreignId('vendor_id')->nullable()->constrained('toko');
            $table->foreignId('pj_id')->nullable()->constrained('users');
            $table->foreignId('pptk_id')->nullable()->constrained('users');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ppk_id')->nullable()->constrained('users');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak_vendor_stok');
            $table->integer('tanggal');
            $table->integer('jumlah');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_penerimaan')->nullable();
            $table->timestamps();
        });

        Schema::create('stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->integer('jumlah');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('cascade');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('cascade');
        });

        Schema::create('stok_disetujui', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('permintaan_id')->constrained('permintaan_stok')->onDelete('cascade'); // Relasi ke permintaan_barang
            $table->foreignId('merk_id')->constrained('merk_stok');
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade'); // Relasi ke lokasi
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('cascade'); // Relasi ke bagian
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('cascade'); // Relasi ke posisi
            $table->text('catatan')->nullable(); // Kolom untuk mencatat informasi tambahan
            $table->integer('jumlah_disetujui'); // Jumlah stok yang disetujui
            $table->timestamps(); // created_at dan updated_at
        });

        Schema::create('persetujuan_permintaan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('detail_permintaan_id')->constrained('detail_permintaan_stok')->onDelete('cascade');
            $table->text('file')->nullable(); // True for approved, false for rejected
            $table->boolean('status')->nullable(); // True for approved, false for rejected
            $table->text('keterangan')->nullable(); // Optional remarks
            $table->timestamps();
        });

        Schema::create('detail_permintaan_stok', function (Blueprint $table) {
            $table->id();
            $table->string('kode_permintaan')->unique();
            $table->date('tanggal_permintaan');
            // $table->foreignId('barang_id')->constrained('barang_stok')->onDelete('cascade');
            $table->foreignId('jenis_id')->constrained('jenis_stok')->onDelete('cascade'); // Link to unit_kerja table
            $table->foreignId('unit_id')->constrained('unit_kerja')->onDelete('cascade'); // Link to unit_kerja table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_stok');
            $table->foreignId('sub_unit_id')->nullable()->constrained('unit_kerja')->onDelete('set null'); // Optional sub-unit link
            $table->integer('jumlah')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('proses')->nullable();
            $table->boolean('cancel')->nullable();
            $table->boolean('status')->nullable();

            $table->timestamps();
        });
        Schema::create('permintaan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_permintaan_id')->constrained('detail_permintaan_stok')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->nullable()->constrained('aset')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerja')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('img')->nullable();
            $table->foreignId('barang_id')->constrained('barang_stok')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('jumlah_approve')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok')->onDelete('set null');
            // $table->text('tanggal_permintaan');
            $table->timestamps();
        });

        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->foreignId('parent_id')->nullable()->constrained('unit_kerja')->onDelete('cascade');
            $table->string('kode', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_kerja');

        Schema::dropIfExists('persetujuan_permintaan_stok');
        Schema::dropIfExists('detail_permintaan_stok');
        Schema::dropIfExists('permintaan_stok');
        Schema::dropIfExists('stok');
        Schema::dropIfExists('metode_pengadaan');
        Schema::dropIfExists('stok_disetujui');
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
