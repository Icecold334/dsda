<?php

// app/Models/History.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'user_id',
        'aset_id',
        'tanggal',
        'person_id',
        'lokasi_id',
        'jumlah',
        'kondisi',
        'kelengkapan',
        'keterangan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
