<?php

namespace App\Models;

use App\Models\Aset;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\DetailPeminjamanAset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanAset extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_aset';

    protected $fillable = [
        'detail_peminjaman_id',
        'user_id',
        'aset_id',
        'unit_id',
        'deskripsi',
        'catatan',
        'img',
        'barang_id',
        'waktu_id',
        'jumlah',
        'jumlah_approve',
        'status',
    ];

    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjamanAset::class, 'detail_peminjaman_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function barang()
    {
        return $this->belongsTo(BarangStok::class, 'barang_id');
    }
}
