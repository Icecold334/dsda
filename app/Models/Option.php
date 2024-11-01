<?php

// app/Models/Option.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'option';

    protected $fillable = [
        'user_id',
        'kodeaset',
        'qr_judul',
        'qr_judul_other',
        'qr_baris1',
        'qr_baris1_other',
        'qr_baris2',
        'qr_baris2_other',
        'scan_qr',
        'scan_qr_history'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
