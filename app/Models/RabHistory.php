<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RabHistory extends Model
{
    protected $table = 'rab_histories';

    protected $fillable = [
        'rab_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
        'keterangan',
        'is_admin_action',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'is_admin_action' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedChangesAttribute()
    {
        if ($this->action === 'delete') {
            return 'RAB dihapus';
        }

        if (!$this->old_data || !$this->new_data) {
            return 'Data tidak tersedia';
        }

        $changes = [];
        $oldData = $this->old_data;
        $newData = $this->new_data;

        $fieldLabels = [
            'jenis_pekerjaan' => 'Jenis Pekerjaan',
            'lokasi' => 'Lokasi',
            'mulai' => 'Tanggal Mulai',
            'selesai' => 'Tanggal Selesai',
            'p' => 'Panjang',
            'l' => 'Lebar',
            'k' => 'Kedalaman',
        ];

        foreach ($fieldLabels as $field => $label) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] !== $newData[$field]) {
                $changes[] = "{$label}: '{$oldData[$field]}' → '{$newData[$field]}'";
            }
        }

        return empty($changes) ? 'Tidak ada perubahan field utama' : implode(', ', $changes);
    }
}
