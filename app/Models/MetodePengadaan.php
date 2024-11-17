<?php

namespace App\Models;

use App\Models\KontrakVendorStok;
use Illuminate\Database\Eloquent\Model;

class MetodePengadaan extends Model
{

    protected $table = 'metode_pengadaan';

    protected $fillable = ['nama', 'deskripsi'];

    public function kontrakVendorStok()
    {
        return $this->hasMany(KontrakVendorStok::class, 'metode_id');
    }
}
