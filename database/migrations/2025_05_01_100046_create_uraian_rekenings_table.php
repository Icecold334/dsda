<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUraianRekeningsTable extends Migration
{
    public function up()
    {
        Schema::create('uraian_rekenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aktivitas_sub_kegiatan_id')->constrained('aktivitas_sub_kegiatans')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('uraian');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uraian_rekenings');
    }
}
