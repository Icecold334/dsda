<?php

namespace App\Livewire;

use App\Models\Persetujuan as Approval;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class ApprovalComponent extends Component
{
    public $approvableId;
    public $approvableType;
    public $approvals = [];
    public $user;

    public function mount($approvableId, $approvableType)
    {
        $this->approvableId = $approvableId;
        $this->approvableType = $approvableType;
        $this->user = Auth::user();

        // Pastikan pengguna memiliki role yang sesuai untuk proses persetujuan
        $this->authorizeApproval();

        // Muat data persetujuan
        $this->loadApprovals();
    }

    // Periksa apakah pengguna memiliki role yang tepat
    private function authorizeApproval()
    {
        if (!$this->user->hasRole('ppk') && !$this->user->hasRole('pptk')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak untuk memberikan persetujuan.');
        }

        // Jika role pengguna adalah ppk, mereka hanya bisa menyetujui PPK
        if ($this->user->hasRole('ppk') && !$this->user->can('approve.kontrak')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menyetujui proyek.');
        }

        // Jika role pengguna adalah pptk, mereka hanya bisa menyetujui PPTK
        if ($this->user->hasRole('pptk') && !$this->user->can('approve.task')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menyetujui tugas.');
        }
    }

    // Memuat data persetujuan
    public function loadApprovals()
    {
        $this->approvals = Approval::where('approvable_id', $this->approvableId)
            ->where('approvable_type', $this->approvableType)
            ->get();
    }

    // Fungsi untuk menghandle persetujuan
    public function approve($approvalId)
    {
        $approval = Approval::find($approvalId);

        // Validasi peran pengguna (PPK atau PPTK)
        if ($approval->role == 'ppk' && !$this->user->hasRole('ppk')) {
            session()->flash('error', 'Hanya PPK yang bisa memberikan persetujuan pertama.');
            return;
        }

        if ($approval->role == 'pptk' && !$this->user->hasRole('pptk')) {
            session()->flash('error', 'Hanya PPTK yang bisa memberikan persetujuan kedua.');
            return;
        }

        // Update status persetujuan
        $approval->is_approved = true;
        $approval->save();

        // Cek apakah keduanya sudah approve (PPK dan PPTK)
        if ($this->bothApproved($approval)) {
            $this->updateFinalApprovalStatus($approval);
        }

        // Reload data approvals
        $this->loadApprovals();

        session()->flash('success', 'Persetujuan berhasil.');
    }

    // Cek apakah PPK dan PPTK sudah memberikan persetujuan
    private function bothApproved(Approval $approval)
    {
        $ppkApproval = Approval::where('approvable_id', $approval->approvable_id)
            ->where('approvable_type', $approval->approvable_type)
            ->where('role', 'ppk')
            ->first();

        $pptkApproval = Approval::where('approvable_id', $approval->approvable_id)
            ->where('approvable_type', $approval->approvable_type)
            ->where('role', 'pptk')
            ->first();

        return $ppkApproval && $pptkApproval && $ppkApproval->is_approved && $pptkApproval->is_approved;
    }

    // Update status final approval jika keduanya sudah approve
    private function updateFinalApprovalStatus(Approval $approval)
    {
        // Status persetujuan final
        $approvable = $approval->approvable;
        $approvable->status = 'final_approved';
        $approvable->save();
    }

    public function render()
    {
        return view('livewire.approval-component');
    }

}
