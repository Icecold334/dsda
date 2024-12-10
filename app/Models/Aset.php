<?php

// app/Models/Aset.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'aset';

    protected $fillable = [
        'user_id',
        'foto',
        'systemcode',
        'kode',
        'nama',
        'kategori_id',
        'merk_id',
        'tipe',
        'produsen',
        'noseri',
        'thproduksi',
        'deskripsi',
        'tanggalbeli',
        'toko_id',
        'invoice',
        'jumlah',
        'hargasatuan',
        'hargatotal',
        'umur',
        'penyusutan',
        'keterangan',
        'tanggalhistory',
        'person',
        'lokasi',
        'keuangan_id',
        'keuangan_tgl',
        'prepublish',
        'aktif',
        'tglnonaktif',
        'alasannonaktif',
        'ketnonaktif',
        'lama_garansi',
        'kartu_garansi',
        'keterangan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class);
    }

    public function lampirans()
    {
        return $this->hasMany(Lampiran::class);
    }
    public function garansis()
    {
        return $this->hasMany(Garansi::class);
    }
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class);
    }
}
