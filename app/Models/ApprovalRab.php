<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persetujuan as Approval;

class ApprovalRab extends Model
{
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
