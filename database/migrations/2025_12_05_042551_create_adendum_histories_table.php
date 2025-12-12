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
        Schema::create('adendum_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adendum_rab_id')->nullable()->constrained('adendum_rabs')->onDelete('set null');
            $table->foreignId('rab_id')->constrained('rab')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['create', 'approve', 'reject']);
            $table->json('old_data')->nullable(); // Data sebelum perubahan
            $table->json('new_data')->nullable(); // Data setelah perubahan
            $table->text('keterangan')->nullable(); // Keterangan dari pembuat adendum atau penolakan
            $table->timestamps();

            $table->index(['adendum_rab_id', 'created_at']);
            $table->index(['rab_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adendum_histories');
    }
};
