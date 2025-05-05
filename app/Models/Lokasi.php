<?php

// app/Models/Lokasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'user_id',
        'nama',
        'nama_nospace',
        'keterangan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aset()
    {
        return $this->hasMany(Aset::class);
    }

    public function history()
    {
        return $this->hasMany(History::class, 'lokasi_id');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'lokasi_id');
    }
}
