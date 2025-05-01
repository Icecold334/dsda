<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivitasSubKegiatansTable extends Migration
{
    public function up()
    {
        Schema::create('aktivitas_sub_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_kegiatan_id')->constrained('sub_kegiatans')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('aktivitas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aktivitas_sub_kegiatans');
    }
}
