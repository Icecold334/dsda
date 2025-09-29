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
        Schema::create('rab_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rab_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['create', 'update', 'delete']);
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_admin_action')->default(false);
            $table->timestamps();

            $table->foreign('rab_id')->references('id')->on('rab')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');

            $table->index(['rab_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_histories');
    }
};
