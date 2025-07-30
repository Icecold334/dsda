<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persetujuan as Approval;


class TransaksiStok extends Model
{
    protected $table = 'transaksi_stok';
    protected $guarded = ['id'];

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function vendorStok()
    {
        return $this->belongsTo(Toko::class, 'vendor_id');
    }
    public function kontrakStok()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }
    public function bagianStok()
    {
        return $this->belongsTo(BagianStok::class, 'bagian_id');
    }
    public function posisiStok()
    {
        return $this->belongsTo(PosisiStok::class, 'posisi_id');
    }

    public function pengirimanStok()
    {
        return $this->belongsTo(PengirimanStok::class, 'pengiriman_id');
    }
    public function permintaanMaterial()
    {
        return $this->belongsTo(PermintaanMaterial::class, 'permintaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function fileAttachments()
    {
        return $this->morphMany(FileSource::class, 'fileable');
    }
}
