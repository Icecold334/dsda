<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsiPersetujuan extends Model
{
    use HasFactory;

    protected $table = 'opsi_persetujuan'; // Nama tabel
    protected $guarded = ['id'];
    /**
     * Relasi ke model JabatanPersetujuan
     */
    public function jabatanPersetujuan()
    {
        return $this->hasMany(JabatanPersetujuan::class, 'opsi_persetujuan_id');
    }

    /**
     * Relasi ke model Role (Spatie Roles)
     */
    public function jabatanPenyelesai()
    {
        return $this->belongsTo(Role::class, 'jabatan_penyelesai_id');
    }
}
