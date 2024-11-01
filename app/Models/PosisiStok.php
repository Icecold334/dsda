<?php

// app/Models/PosisiStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosisiStok extends Model
{
    use HasFactory;

    protected $table = 'posisi_stok';

    protected $fillable = [
        'bagian_id',
        'nama'
    ];

    public function bagian()
    {
        return $this->belongsTo(BagianStok::class);
    }

    public function merekStok()
    {
        return $this->hasMany(MerekStok::class, 'posisi_id');
    }
}
