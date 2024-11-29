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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID user yang menyetujui
            $table->enum('role', ['ppk', 'pptk']); // Role (PPK atau PPTK)
            $table->boolean('is_approved')->default(false); // Status persetujuan (false = belum disetujui, true = disetujui)
            $table->unsignedBigInteger('approvable_id'); // ID dari model yang disetujui
            $table->string('approvable_type'); // Nama model yang disetujui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
