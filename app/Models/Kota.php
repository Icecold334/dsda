<?php

// app/Models/Kota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';

    protected $fillable = [
        'nama',
        'prov_id'
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'prov_id');
    }
}
