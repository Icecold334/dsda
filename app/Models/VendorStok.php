<?php

// app/Models/VendorStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorStok extends Model
{
    use HasFactory;

    protected $table = 'vendor_stok';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email'
    ];

    public function kontrakVendors()
    {
        return $this->hasMany(KontrakVendor::class);
    }

    public function merekStok()
    {
        return $this->hasMany(MerekStok::class);
    }
}
