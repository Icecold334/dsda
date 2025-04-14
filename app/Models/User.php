<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Ruang;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'unit_id',
        'lokasi_id',
        'nip',
        'ttd',
        'foto',
        'email',
        'password',
        'email_verified_at',
        'username',
        'alamat',
        'perusahaan',
        'no_wa',
        'provinsi',
        'kota',
        'hak',
        'keterangan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
    public function persetujuankontrak()
    {
        return $this->hasMany(PersetujuanKontrakStok::class, 'user_id');
    }
    public function kontrakVendor()
    {
        return $this->hasMany(KontrakVendorStok::class, 'user_id');
    }
    public function persetujuanPengiriman()
    {
        return $this->hasMany(PersetujuanPengirimanStok::class, 'user_id');
    }
    public function persetujuanPermintaan()
    {
        return $this->hasMany(PersetujuanPermintaanStok::class, 'user_id');
    }
    public function persetujuanPeminjaman()
    {
        return $this->hasMany(PersetujuanPeminjamanAset::class, 'user_id');
    }
    public function persetujuan()
    {
        return $this->hasMany(Persetujuan::class, 'user_id');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'lokasi_id', 'lokasi_id');
    }

    public function opsi()
    {
        return $this->hasMany(OpsiPersetujuan::class, 'user_penyelesai_id');
    }
    public function stokDiterima()
    {
        return $this->hasMany(StokDiterima::class, 'user_id');
    }
    public function ruang()
    {
        return $this->hasMany(Ruang::class, 'user_id');
    }
    public function penanggungjawab()
    {
        return $this->hasMany(Ruang::class, 'pj_id');
    }
}
