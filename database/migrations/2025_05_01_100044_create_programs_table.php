<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('program');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
