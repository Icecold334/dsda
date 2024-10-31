<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel-tabel lain sesuai SQL dump dalam bahasa Indonesia
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->string('tipe', 16)->default('mingguan');
            $table->integer('hari')->default(0);
            $table->integer('tanggal')->default(0);
            $table->integer('bulan')->default(0);
            $table->integer('tahun')->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('foto', 256)->nullable();
            $table->string('systemcode', 32)->nullable();
            $table->string('kode', 128)->nullable();
            $table->string('nama', 128)->nullable();
            $table->foreignId('kategori')->constrained('kategori')->onDelete('set null');
            $table->foreignId('merk_id')->constrained('merk')->onDelete('set null');
            $table->string('tipe', 256)->nullable();
            $table->string('produsen', 256)->nullable();
            $table->string('noseri', 128)->nullable();
            $table->string('thproduksi', 32)->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('tanggalbeli')->default(0);
            $table->foreignId('toko_id')->constrained('toko')->onDelete('set null');
            $table->string('invoice', 128)->nullable();
            $table->decimal('jumlah', 16, 2)->default(1.00);
            $table->decimal('hargasatuan', 28, 2)->default(0.00);
            $table->decimal('hargatotal', 28, 2)->default(0.00);
            $table->integer('umur')->default(0);
            $table->decimal('penyusutan', 16, 2)->default(0.00);
            $table->text('keterangan')->nullable();
            $table->integer('tanggalhistory')->default(0);
            $table->foreignId('person')->constrained('person')->onDelete('set null');
            $table->foreignId('lokasi')->constrained('lokasi')->onDelete('set null');
            $table->integer('keuangan_id')->default(0);
            $table->integer('keuangan_tgl')->default(0);
            $table->boolean('prepublish')->default(0);
            $table->boolean('aktif')->default(1);
            $table->integer('tglnonaktif')->default(0);
            $table->string('alasannonaktif', 16)->nullable();
            $table->text('ketnonaktif')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->integer('tanggal')->default(0);
            $table->foreignId('person_id')->constrained('person')->onDelete('set null');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('set null');
            $table->decimal('jumlah', 16, 2)->default(1.00);
            $table->integer('kondisi')->default(100);
            $table->integer('kelengkapan')->default(100);
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->integer('tanggal')->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 256)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('parent')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->boolean('status')->default(1);
        });

        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->integer('tanggal')->default(0);
            $table->string('tipe', 8)->default('out');
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 16, 2)->default(0.00);
            $table->boolean('status')->default(1);
        });

        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('aset_id')->constrained('aset')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->string('file', 256)->nullable();
        });

        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama', 256)->nullable();
            $table->string('nama_nospace', 256)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('merk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 256)->nullable();
            $table->string('nama_nospace', 256)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama', 256)->nullable();
            $table->string('nama_nospace', 256)->nullable();
            $table->string('jabatan', 256)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 128)->nullable();
            $table->string('email', 128)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama', 256)->nullable();
            $table->string('nama_nospace', 256)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 128)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('petugas', 256)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
        });

        Schema::create('bagian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('cascade');
            $table->string('nama', 255);
        });

        Schema::create('barang_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('kode', 100);
            $table->text('deskripsi')->nullable();
        });

        Schema::create('kontrak_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendor_stok')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keterangan')->nullable();
        });

        Schema::create('lokasi_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
        });

        Schema::create('merek_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_stok')->onDelete('cascade');
            $table->string('nama', 255);
            $table->integer('jumlah');
            $table->string('satuan', 100);
            $table->foreignId('lokasi_id')->constrained('lokasi_stok')->onDelete('restrict');
            $table->foreignId('bagian_id')->nullable()->constrained('bagian_stok')->onDelete('set null');
            $table->foreignId('posisi_id')->nullable()->constrained('posisi_stok')->onDelete('set null');
            $table->foreignId('vendor_id')->nullable()->constrained('vendor_stok')->onDelete('set null');
            $table->foreignId('kontrak_id')->nullable()->constrained('kontrak_vendor')->onDelete('set null');
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_sisa')->default(0);
        });

        Schema::create('posisi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bagian_id')->constrained('bagian_stok')->onDelete('cascade');
            $table->string('nama', 255);
        });

        Schema::create('vendor_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 100)->nullable();
        });

        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 128)->nullable();
            $table->string('rekening', 128)->nullable();
            $table->string('atasnama', 256)->nullable();
            $table->boolean('status')->default(1);
        });

        // Schema::create('child', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama', 256)->nullable();
        //     $table->text('keterangan')->nullable();
        //     $table->string('username', 256)->nullable();
        //     $table->string('kunci', 256)->nullable();
        //     $table->text('hak');
        //     $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        //     $table->boolean('status')->default(1);
        // });

        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 64)->nullable();
            $table->integer('persen')->default(0);
            $table->text('keterangan')->nullable();
            $table->integer('dipakai')->default(0);
            $table->integer('expired')->default(0);
            $table->boolean('aktif')->default(1);
        });

        Schema::create('harga', function (Blueprint $table) {
            $table->id();
            $table->integer('paket')->default(0);
            $table->integer('bulan')->default(0);
            $table->integer('harga_asli')->default(0);
            $table->integer('harga')->default(0);
            $table->integer('diskon')->default(0);
        });

        Schema::create('konfirmasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('order')->onDelete('cascade');
            $table->integer('paket')->default(0);
            $table->integer('bulan')->default(12);
            $table->integer('tanggal')->default(0);
            $table->string('bank', 128)->nullable();
            $table->string('nama', 128)->nullable();
            $table->string('metode', 128)->nullable();
            $table->decimal('nominal', 16, 2)->default(0.00);
            $table->boolean('oke')->default(0);
            $table->boolean('status')->default(1);
        });

        Schema::create('kota', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->nullable();
            $table->foreignId('prov_id')->nullable()->constrained('provinsi')->onDelete('set null');
        });

        Schema::create('option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kodeaset', 256)->default('[nomor]/INV/[tahun]');
            $table->string('qr_judul', 128)->default('perusahaan');
            $table->string('qr_judul_other', 128)->nullable();
            $table->string('qr_baris1', 128)->default('nama');
            $table->string('qr_baris1_other', 128)->nullable();
            $table->string('qr_baris2', 128)->default('kode');
            $table->string('qr_baris2_other', 128)->nullable();
            $table->text('scan_qr')->nullable();
            $table->text('scan_qr_history')->nullable();
        });

        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('tanggal')->default(0);
            $table->integer('paket')->default(0);
            $table->integer('bulan')->default(0);
            $table->integer('harga')->default(0);
            $table->integer('angka_unik')->default(0);
            $table->string('diskon_kode', 128)->nullable();
            $table->integer('diskon_persen')->default(0);
            $table->integer('diskon_rp')->default(0);
            $table->integer('jumlah')->default(0);
            $table->boolean('oke')->default(0);
            $table->boolean('status')->default(1);
        });

        Schema::create('provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posisi_stok');
        Schema::dropIfExists('merek_stok');
        Schema::dropIfExists('kontrak_vendor');
        Schema::dropIfExists('barang_stok');
        Schema::dropIfExists('bagian_stok');
        Schema::dropIfExists('vendor_stok');
        Schema::dropIfExists('jurnal');
        Schema::dropIfExists('history');
        Schema::dropIfExists('aset');
        Schema::dropIfExists('agenda');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('keuangan');
        Schema::dropIfExists('lampiran');
        Schema::dropIfExists('lokasi');
        Schema::dropIfExists('merk');
        Schema::dropIfExists('person');
        Schema::dropIfExists('toko');
        Schema::dropIfExists('bank');
        // Schema::dropIfExists('child');
        Schema::dropIfExists('diskon');
        Schema::dropIfExists('harga');
        Schema::dropIfExists('konfirmasi');
        Schema::dropIfExists('kota');
        Schema::dropIfExists('option');
        Schema::dropIfExists('order');
        Schema::dropIfExists('provinsi');
    }
};
