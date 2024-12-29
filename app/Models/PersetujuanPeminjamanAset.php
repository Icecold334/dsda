<?php

namespace App\Models;

use App\Models\User;
use App\Models\DetailPeminjamanAset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersetujuanPeminjamanAset extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_peminjaman_aset';

    protected $fillable = [
        'user_id',
        'detail_peminjaman_id',
        'file',
        'status',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjamanAset::class, 'detail_peminjaman_id');
    }
}
