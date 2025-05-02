<?php

namespace App\Models;

use App\Models\User;
use App\Models\Program;
use App\Models\Kegiatan;

use App\Models\MerkStok;
use App\Models\LampiranRab;
use App\Models\SubKegiatan;
use App\Models\UraianRekening;
use App\Models\AktivitasSubKegiatan;
use App\Models\Persetujuan as Approval;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    protected $table = 'rab';
    protected $guarded = ['id'];
    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
    ];

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Merk (One-to-Many)
    public function list()
    {
        return $this->hasMany(ListRab::class, 'rab_id');
    }

    // Relasi ke Lampiran (One-to-Many)
    public function lampiran()
    {
        return $this->hasMany(LampiranRab::class, 'rab_id');
    }
    public function persetujuan()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    // Relasi ke Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relasi ke Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    // Relasi ke Sub Kegiatan
    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }

    // Relasi ke Aktivitas Sub Kegiatan
    public function aktivitasSubKegiatan()
    {
        return $this->belongsTo(AktivitasSubKegiatan::class, 'aktivitas_sub_kegiatan_id');
    }

    // Relasi ke Uraian Rekening
    public function uraianRekening()
    {
        return $this->belongsTo(UraianRekening::class, 'uraian_rekening_id');
    }
}
