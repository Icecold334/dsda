<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabel opsi persetujuan
        Schema::create('opsi_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->string('uuid'); // Nama konfigurasi persetujuan
            $table->foreignId('unit_id')->constrained('unit_kerja')->onDelete('cascade'); // Link to unit_kerja table
            $table->string('jenis')->nullable(); // Nama konfigurasi persetujuan
            $table->string('tipe')->nullable(); // Nama konfigurasi persetujuan
            $table->text('deskripsi')->nullable(); // Deskripsi konfigurasi
            $table->bigInteger('urutan_persetujuan')->nullable(); // Urutan untuk menentukan jumlah barang
            $table->bigInteger('cancel_persetujuan')->nullable();
            $table->unsignedBigInteger('jabatan_penyelesai_id')->nullable(); // Jabatan penyelesaian
            $table->unsignedBigInteger('user_penyelesai_id')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_stok')->onDelete('set null');
            $table->timestamps();

            $table->foreign('jabatan_penyelesai_id')
                ->references('id')
                ->on('roles')
                ->nullOnDelete();
            $table->foreign('user_penyelesai_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        // Tabel jabatan persetujuan
        Schema::create('jabatan_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opsi_persetujuan_id'); // ID opsi persetujuan
            $table->unsignedBigInteger('jabatan_id'); // ID jabatan dari tabel roles
            $table->bigInteger('urutan'); // Urutan jabatan dalam persetujuan
            $table->bigInteger('approval')->nullable();
            $table->timestamps();

            // Relasi ke tabel opsi_persetujuan
            $table->foreign('opsi_persetujuan_id')
                ->references('id')
                ->on('opsi_persetujuan')
                ->cascadeOnDelete();

            // Relasi ke tabel roles jika menggunakan Spatie Roles
            $table->foreign('jabatan_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_persetujuan');
        Schema::dropIfExists('opsi_persetujuan');
    }
};
