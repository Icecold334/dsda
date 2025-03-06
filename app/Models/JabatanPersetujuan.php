<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JabatanPersetujuan extends Model
{
    use HasFactory;

    protected $table = 'jabatan_persetujuan'; // Nama tabel
    protected $fillable = [
        'opsi_persetujuan_id',
        'jabatan_id',
        'urutan',
        'approval',
    ];

    /**
     * Relasi ke model OpsiPersetujuan
     */
    public function opsiPersetujuan()
    {
        return $this->belongsTo(OpsiPersetujuan::class, 'opsi_persetujuan_id');
    }

    /**
     * Relasi ke model Role (Spatie Roles)
     */
    public function jabatan()
    {
        return $this->belongsTo(Role::class, 'jabatan_id');
    }
}
