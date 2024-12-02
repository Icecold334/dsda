<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'user_id',
        'tanggal',
        'paket',
        'bulan',
        'harga',
        'angka_unik',
        'diskon_kode',
        'diskon_persen',
        'diskon_rp',
        'jumlah',
        'oke',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
