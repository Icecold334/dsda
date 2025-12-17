<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdendumRab extends Model
{
    protected $table = 'adendum_rabs';
    protected $fillable = [
        'rab_id',
        'user_id',
        'keterangan',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Relasi ke RAB
    public function rab()
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }

    // Relasi ke User (Kasatpel yang membuat adendum)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User (Pembuat RAB yang approve)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke Detail Material Adendum
    public function list()
    {
        return $this->hasMany(AdendumListRab::class, 'adendum_rab_id');
    }

    // Relasi ke History Adendum
    public function histories()
    {
        return $this->hasMany(AdendumHistory::class, 'adendum_rab_id');
    }
}
