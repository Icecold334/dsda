<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdendumHistory extends Model
{
    protected $table = 'adendum_histories';

    protected $fillable = [
        'adendum_rab_id',
        'rab_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
        'keterangan',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function adendumRab(): BelongsTo
    {
        return $this->belongsTo(AdendumRab::class, 'adendum_rab_id');
    }

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
