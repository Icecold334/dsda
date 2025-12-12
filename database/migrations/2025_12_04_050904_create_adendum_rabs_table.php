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
        Schema::create('adendum_rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rab')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Kasatpel yang membuat adendum
            $table->text('keterangan')->nullable(); // Keterangan perubahan dari Kasatpel
            $table->boolean('is_approved')->default(false); // Status approval dari pembuat RAB
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Pembuat RAB yang approve
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adendum_rabs');
    }
};
