<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubKegiatansTable extends Migration
{
    public function up()
    {
        Schema::create('sub_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained()->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('sub_kegiatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_kegiatans');
    }
}
