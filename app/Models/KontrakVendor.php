<?php

// app/Models/KontrakVendor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakVendor extends Model
{
    use HasFactory;

    protected $table = 'kontrak_vendor';

    protected $fillable = [
        'vendor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan'
    ];

    public function vendor()
    {
        return $this->belongsTo(VendorStok::class);
    }

    public function merekStok()
    {
        return $this->hasMany(MerekStok::class, 'kontrak_id');
    }
}
