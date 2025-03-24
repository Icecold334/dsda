<?php

namespace App\Models;

use App\Models\User;
use App\Models\MerkStok;
use App\Models\LampiranRab;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    protected $table = 'rab';
    protected $guarded = ['id'];
    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
    ];

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Merk (One-to-Many)
    public function list()
    {
        return $this->hasMany(ListRab::class, 'rab_id');
    }

    // Relasi ke Lampiran (One-to-Many)
    public function lampiran()
    {
        return $this->hasMany(LampiranRab::class, 'rab_id');
    }
}
