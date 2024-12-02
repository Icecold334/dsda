<?php

// app/Models/Merk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    use HasFactory;

    protected $table = 'merk';

    protected $fillable = [
        'user_id',
        'nama',
        'nama_nospace',
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
