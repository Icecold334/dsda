<?php

// app/Models/Konfirmasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfirmasi extends Model
{
    use HasFactory;

    protected $table = 'konfirmasi';

    protected $fillable = [
        'user_id',
        'order_id',
        'paket',
        'bulan',
        'tanggal',
        'bank',
        'nama',
        'metode',
        'nominal',
        'oke',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
