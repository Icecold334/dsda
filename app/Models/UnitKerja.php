<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama',
        'parent_id',
        'kode',
        'keterangan',
    ];

    /**
     * Get the parent unit.
     */
    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'parent_id');
    }

    /**
     * Get the sub-units.
     */
    public function children()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'unit_id');
    }
}
