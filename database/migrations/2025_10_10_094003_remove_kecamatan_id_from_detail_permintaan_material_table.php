<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_permintaan_material', function (Blueprint $table) {
            $table->dropColumn('kecamatan_id');
        });
    }

    public function down(): void
    {
        Schema::table('detail_permintaan_material', function (Blueprint $table) {
            $table->unsignedBigInteger('kecamatan_id')->nullable()->after('kelurahan_id');
        });
    }
};
