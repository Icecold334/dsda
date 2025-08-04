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

            // Keep merk_id as required (not nullable) to maintain data integrity
            $table->foreignId('merk_id')->nullable(false)->change();

            // Re-add foreign key constraint
            $table->foreign('merk_id')->references('id')->on('merk_stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_rab', function (Blueprint $table) {
            // No changes needed in down method since we're keeping it as required
        });
    }
};
