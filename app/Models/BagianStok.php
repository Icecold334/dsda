<?php

// app/Models/BagianStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianStok extends Model
{
    use HasFactory;

    protected $table = 'bagian_stok';

    protected $fillable = [
        'lokasi_id',
        'nama'
    ];

    public function lokasi()
    {
        return $this->belongsTo(LokasiStok::class);
    }

    public function posisiStok()
    {
        return $this->hasMany(PosisiStok::class);
    }
}
