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
        Schema::create('list_kontrak_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontrak_vendor_stok')->onDelete('cascade');
            $table->foreignId('merk_id')->constrained('merk_stok')->onDelete('restrict');
            $table->integer('jumlah');
            $table->bigInteger('harga'); // harga satuan
            $table->enum('ppn', ['11', '12'])->nullable()->comment('null = harga sudah termasuk ppn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_kontrak_stoks');
    }
};
