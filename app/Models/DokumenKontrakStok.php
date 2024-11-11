<?php

namespace App\Models;

use App\Models\KontrakVendorStok;
use Illuminate\Database\Eloquent\Model;

class DokumenKontrakStok extends Model
{

    protected $fillable = ['kontrak_id', 'file'];

    protected $table = 'dokumen_kontrak_stok';

    // Relationship to KontrakVendorStok
    public function kontrak()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }
}
