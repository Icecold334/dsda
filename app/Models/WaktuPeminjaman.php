<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'waktu_peminjaman';

    protected $fillable = [
        'waktu',
        'mulai',
        'selesai',
    ];
}
