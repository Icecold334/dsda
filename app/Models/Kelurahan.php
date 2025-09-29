<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function Livewire\Volt\protect;

class Kelurahan extends Model
{
    protected $table = 'kelurahans';

    protected $guarded = ['id',];

    protected $fillable = [
        'nama',
        'kecamatan_id',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
