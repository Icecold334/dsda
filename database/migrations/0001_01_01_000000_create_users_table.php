<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerja')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok')->onDelete('cascade');
            $table->string('name');
            $table->bigInteger('nip')->nullable()
                ->default('1');
            $table->string('ttd', 256)
                ->nullable();
            // ->default('1');
            $table->string('foto', 256)
                ->nullable();
            // ->default('1');
            $table->string('username', 256)->nullable();
            $table->text('hak')->nullable(); // Hak akses spesifik yang bisa dikelola dengan role Spatie
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('alamat')->nullable();
            $table->string('perusahaan')->nullable();
            $table->bigInteger('provinsi')->nullable();
            $table->bigInteger('kota')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('keterangan')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->bigInteger('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
