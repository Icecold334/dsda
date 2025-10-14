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
        Schema::table('detail_permintaan_material', function (Blueprint $table) {
            // Tambahkan kolom kecamatan_id setelah kolom kelurahan_id
            $table->unsignedBigInteger('kecamatan_id')->nullable()->after('kelurahan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_permintaan_material', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('kecamatan_id');
        });
    }
};