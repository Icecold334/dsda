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
        Schema::create('file_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fileable_id'); // ID dari model yang disetujui
            $table->string('fileable_type'); // Nama model yang disetujui
            $table->foreignId('user_id')->constrained('users');
            $table->text('file')->nullable();
            $table->boolean('status')->default(true);
            $table->text('keterangan')->nullable();
            $table->enum('type',['bap','lainnya'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_sources');
    }
};
