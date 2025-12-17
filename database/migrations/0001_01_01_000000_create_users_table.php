<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Remove FOREIGN_KEY_CHECKS — not needed in migrations

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Important: foreignId()->constrained() only works if the other tables already exist
            $table->foreignId('unit_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi_stok')->nullOnDelete();

            $table->string('name');

            $table->bigInteger('nip')->nullable()->default(1);
            $table->string('ttd', 256)->nullable();
            $table->string('foto', 256)->nullable();
            $table->string('username', 256)->nullable();
            $table->text('hak')->nullable();
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

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
