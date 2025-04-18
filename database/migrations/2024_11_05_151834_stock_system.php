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
            $table->string('slug');
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
            $table->string('slug');
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
            $table->string('slug');
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
            $table->foreignId('approval_configuration_id')->nullable()->constrained('opsi_persetujuan')->onDelete('set null');
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
            $table->timestamp('status_diterima')->nullable();
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('cascade');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('cascade');
            $table->timestamp('status_lokasi')->nullable();
            $table->timestamps();
        });
        Schema::create('stok_diterima', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('pengiriman_id')->constrained('pengiriman_stok')->onDelete('cascade'); // Relasi ke pengiriman_barang
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_diterima'); // Jumlah stok yang diterima
            // $table->string('img')->nullable();
            $table->text('catatan')->nullable(); // Kolom untuk mencatat informasi tambahan
            $table->timestamps(); // created_at dan updated_at
        });
        Schema::create('kontrak_vendor_stok', function (Blueprint $table) {
            $table->timestamps();
            $table->id();
            $table->string('nomor_kontrak')->nullable();
            $table->integer('nominal_kontrak')->nullable();
            $table->foreignId('vendor_id')->constrained('toko', 'id'); // Explicitly set column
            $table->date('tanggal_kontrak');
            $table->foreignId('metode_id')->nullable()->constrained('metode_pengadaan')->onDelete('set null');
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_stok')->onDelete('cascade'); // Link to unit_kerja table
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
            $table->integer('harga')->nullable();
            $table->integer('ppn')->nullable();
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
            $table->foreignId('approval_configuration_id')->nullable()->constrained('opsi_persetujuan')->onDelete('set null');
            $table->foreignId('sub_unit_id')->nullable()->constrained('unit_kerja')->onDelete('set null'); // Optional sub-unit link
            $table->foreignId('rab_id')->nullable()->constrained('rab')->onDelete('set null');

            $table->integer('jumlah')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_cancel')->nullable();
            $table->text('keterangan_done')->nullable();
            $table->boolean('proses')->nullable();
            $table->boolean('cancel')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('jumlah_peserta')->nullable();
            $table->foreignId('lokasi_id')->nullable()->constrained('ruangs')->onDelete('set null');
            $table->text('lokasi_lain')->nullable();
            $table->text('alamat_lokasi')->nullable();
            $table->text('kontak_person')->nullable();
            $table->foreignId('aset_id')->nullable()->constrained('aset')->onDelete('cascade');
            $table->dateTime('tanggal_masuk')->nullable();
            $table->dateTime('tanggal_keluar')->nullable();
            $table->string('file')->nullable();
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
            $table->string('img_done')->nullable();
            $table->string('catatan_done')->nullable();
            $table->foreignId('barang_id')->constrained('barang_stok')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('jumlah_approve')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('lokasi_id')->nullable()->constrained('ruangs')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('driver_name')->nullable();
            $table->string('voucher_name')->nullable();
            $table->text('noseri')->nullable();
            $table->text('jenis_kdo')->nullable();
            $table->text('nama_kdo')->nullable();
            // $table->text('tanggal_permintaan');
            $table->timestamps();
        });









        Schema::create('persetujuan_peminjaman_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('detail_peminjaman_id')->constrained('detail_peminjaman_aset')->onDelete('cascade');
            $table->text('file')->nullable(); // True for approved, false for rejected
            $table->boolean('status')->nullable(); // True for approved, false for rejected
            $table->text('keterangan')->nullable(); // Optional remarks
            $table->timestamps();
        });
        Schema::create('detail_peminjaman_aset', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->date('tanggal_peminjaman');
            // $table->foreignId('barang_id')->constrained('barang_stok')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit_kerja')->onDelete('cascade'); // Link to unit_kerja table
            $table->foreignId('sub_unit_id')->nullable()->constrained('unit_kerja')->onDelete('set null'); // Optional sub-unit link
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori');
            $table->foreignId('approval_configuration_id')->nullable()->constrained('opsi_persetujuan')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->text('keterangan_cancel')->nullable();
            $table->boolean('proses')->nullable();
            $table->boolean('cancel')->nullable();
            $table->boolean('status')->nullable();
            $table->string('img_pengembalian')->nullable();
            $table->text('keterangan_pengembalian')->nullable();
            $table->timestamps();
        });
        Schema::create('peminjaman_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_peminjaman_id')->constrained('detail_peminjaman_aset')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->nullable()->constrained('aset')->onDelete('cascade');
            $table->foreignId('approved_aset_id')->nullable()->constrained('aset')->onDelete('cascade');
            $table->foreignId('waktu_id')->nullable()->constrained('waktu_peminjaman')->onDelete('cascade');
            $table->foreignId('approved_waktu_id')->nullable()->constrained('waktu_peminjaman')->onDelete('cascade');
            // $table->foreignId('unit_id')->nullable()->constrained('unit_kerja')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_approved')->nullable();
            $table->string('img')->nullable();
            $table->integer('jumlah_orang')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('jumlah_approve')->nullable();
            $table->boolean('status')->nullable();
            $table->string('img_pengembalian')->nullable();
            $table->text('keterangan_pengembalian')->nullable();
            $table->timestamps();
        });
        Schema::create('waktu_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('waktu');
            $table->time('mulai');
            $table->time('selesai');
            $table->timestamps();
        });







        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->foreignId('parent_id')->nullable()->constrained('unit_kerja')->onDelete('cascade');
            $table->string('kode', 50)->nullable();
            $table->integer('hak')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Schema::dropIfExists('unit_kerja');

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
