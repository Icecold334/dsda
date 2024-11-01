<?php

// app/Models/BarangStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangStok extends Model
{
    use HasFactory;

    protected $table = 'barang_stok';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];

    public function merekStok()
    {
        return $this->hasMany(MerekStok::class);
    }
}
