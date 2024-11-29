<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\Persetujuan as Approval;

class PersetujuanController extends Controller
{
    public function approve($id)
    {
        $approval = Approval::findOrFail($id);

        // Pastikan hanya PPK yang bisa approve pertama kali
        if (Auth::user()->role != 'ppk' && $approval->role == 'ppk') {
            return redirect()->back()->with('error', 'Hanya PPK yang bisa menyetujui pertama.');
        }

        // Jika PPK sudah approve, maka PPTK bisa approve
        if ($approval->role == 'ppk' && $approval->is_approved == true) {
            // Jika PPK sudah setujui, berikan kesempatan untuk PPTK approve
            $approval->is_approved = true;
            $approval->save();

            // Setelah keduanya approve, update status persetujuan akhir
            $this->updateStatusSetuju($approval);
        }

        // Tandai approval sebagai approved
        $approval->is_approved = true;
        $approval->save();

        return redirect()->route('approvals.index')->with('success', 'Persetujuan berhasil.');
    }

    private function updateStatusSetuju(Approval $approval)
    {
        // Cek apakah keduanya sudah approve
        $ppkApproval = Approval::where('role', 'ppk')
            ->where('user_id', $approval->user_id)
            ->first();

        $pptkApproval = Approval::where('role', 'pptk')
            ->where('user_id', $approval->user_id)
            ->first();

        if ($ppkApproval && $pptkApproval && $ppkApproval->is_approved && $pptkApproval->is_approved) {
            // Status persetujuan telah disetujui kedua pihak
            $approval->status = 'setuju';
            $approval->save();
        }
    }
}
