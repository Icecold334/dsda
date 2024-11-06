<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanBesar extends Model
{
    use HasFactory;

    protected $table = 'satuan_besar';


    protected $fillable = ['nama'];

    public function barangs()
    {
        return $this->hasMany(BarangStok::class);
    }
}
