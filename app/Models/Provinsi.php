<?php

// app/Models/Provinsi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';

    protected $fillable = [
        'nama'
    ];

    public function kotas()
    {
        return $this->hasMany(Kota::class, 'prov_id');
    }
}
