<?php

// app/Models/Harga.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;

    protected $table = 'harga';

    protected $fillable = [
        'paket',
        'bulan',
        'harga_asli',
        'harga',
        'diskon'
    ];
}
