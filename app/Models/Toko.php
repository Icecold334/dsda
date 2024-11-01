<?php

// app/Models/Toko.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'user_id',
        'nama',
        'nama_nospace',
        'alamat',
        'telepon',
        'email',
        'petugas',
        'keterangan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aset()
    {
        return $this->hasMany(Aset::class);
    }
}
