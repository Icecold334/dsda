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
            // Drop foreign key constraint first
            $table->dropForeign(['merk_id']);

            // Modify merk_id to be nullable
            $table->foreignId('merk_id')->nullable()->change();

            // Re-add foreign key constraint with nullable
            $table->foreign('merk_id')->references('id')->on('merk_stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_rab', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['merk_id']);

            // Modify merk_id to be not nullable
            $table->foreignId('merk_id')->nullable(false)->change();

            // Re-add foreign key constraint without nullable
            $table->foreign('merk_id')->references('id')->on('merk_stok');
        });
    }
};
