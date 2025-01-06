<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanKecil extends Model
{
    use HasFactory;

    protected $table = 'satuan';

    protected $fillable = ['nama'];

    public function barangs()
    {
        return $this->hasMany(BarangStok::class);
    }
}
