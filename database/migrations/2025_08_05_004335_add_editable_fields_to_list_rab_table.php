<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('list_rab', function (Blueprint $table) {
            // Add override fields for custom entries
            $table->string('nama_barang_override')->nullable()->after('jumlah');
            $table->text('spesifikasi_override')->nullable()->after('nama_barang_override');
            $table->string('satuan_override')->nullable()->after('spesifikasi_override');
            $table->text('keterangan')->nullable()->after('satuan_override');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_rab', function (Blueprint $table) {
            // Drop the override fields
            $table->dropColumn(['nama_barang_override', 'spesifikasi_override', 'satuan_override', 'keterangan']);
        });
    }
};
