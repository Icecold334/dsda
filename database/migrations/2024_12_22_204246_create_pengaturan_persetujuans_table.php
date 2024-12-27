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
            $table->boolean('item')->default(0); // Per Item?
            $table->text('deskripsi')->nullable(); // Deskripsi konfigurasi
            $table->integer('urutan_persetujuan')->nullable(); // Urutan untuk menentukan jumlah barang
            $table->integer('cancel_persetujuan')->nullable();
            $table->unsignedBigInteger('jabatan_penyelesai_id')->nullable(); // Jabatan penyelesaian
            $table->timestamps();

            // Relasi ke tabel roles jika menggunakan Spatie Roles
            $table->foreign('jabatan_penyelesai_id')
                ->references('id')
                ->on('roles')
                ->nullOnDelete();
        });

        // Tabel jabatan persetujuan
        Schema::create('jabatan_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opsi_persetujuan_id'); // ID opsi persetujuan
            $table->unsignedBigInteger('jabatan_id'); // ID jabatan dari tabel roles
            $table->integer('limit')->default(1); // Urutan jabatan dalam persetujuan
            // $table->integer('urutan'); // Urutan jabatan dalam persetujuan
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
