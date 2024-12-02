<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanKontrakStok extends Model
{
    protected $table = 'persetujuan_kontrak_stok';
    protected $guarded = ['id'];

    public function kontrakVendorStok()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
