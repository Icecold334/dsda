<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kategori;
use App\Models\UnitKerja;
use App\Models\PeminjamanAset;
use App\Models\OpsiPersetujuan;
use Illuminate\Database\Eloquent\Model;
use App\Models\PersetujuanPeminjamanAset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPeminjamanAset extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman_aset';

    protected $guarded = [
        'id'
    ];

    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    public function subUnit()
    {
        return $this->belongsTo(UnitKerja::class, 'sub_unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanPeminjamanAset::class, 'detail_peminjaman_id');
    }

    public function peminjamanAset()
    {
        return $this->hasMany(PeminjamanAset::class, 'detail_peminjaman_id');
    }

    public function opsiPersetujuan()
    {
        return $this->belongsTo(OpsiPersetujuan::class, 'approval_configuration_id');
    }
}
