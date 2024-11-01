<?php

// app/Models/LokasiStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiStok extends Model
{
    use HasFactory;

    protected $table = 'lokasi_stok';

    protected $fillable = [
        'nama'
    ];

    public function bagianStok()
    {
        return $this->hasMany(BagianStok::class);
    }

    public function merekStok()
    {
        return $this->hasMany(MerekStok::class);
    }
}
