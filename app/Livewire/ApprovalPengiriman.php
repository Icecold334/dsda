<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\PengirimanStok;
use Illuminate\Support\Facades\Auth;

class ApprovalPengiriman extends Component
{

    public $pengiriman;
    public $penanggungjawab;
    public $penulis;
    public $user;
    public $date;
    public $pjList;
    public $ppkList;
    public $pptkList;
    public $listApproval;
    public $roles;

    public function mount()
    {
        $this->user = Auth::user();
        $this->roles = 'penanggungjawab|ppk|pptk';
        $this->penulis = $this->pengiriman->user;
        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);
        $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $ppk = User::role('ppk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $pj = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $this->pptkList = $pptk;
        $this->ppkList = $ppk;
        $this->pjList = $pj;
        $this->listApproval = $this->pptkList->merge($this->ppkList)->merge($this->pjList)->count();
    }

    public function approveConfirmed()
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => true,
        ]);

        $list = $this->pengiriman->persetujuan;

        $filteredList = $list->filter(function ($approval) {
            return $approval->status;
        });
        if ($filteredList->count() == $this->listApproval) {
            $this->pengiriman->status = true;
            $this->pengiriman->save();

            $pengirimanItems = $this->pengiriman->pengirimanStok;
            foreach ($pengirimanItems as $pengiriman) {
                $stok = Stok::firstOrCreate(
                    [
                        'merk_id' => $pengiriman->merk_id,
                        'lokasi_id' => $pengiriman->lokasi_id,
                        'bagian_id' => $pengiriman->bagian_id,
                        'posisi_id' => $pengiriman->posisi_id,
                    ],
                    ['jumlah' => 0]  // Atur stok awal jika belum ada
                );

                $stok->jumlah += $pengiriman->jumlah;
                $stok->save();
            }
        }


        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function rejectConfirmed($reason)
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => false,
            'keterangan' => $reason
        ]);
        $this->pengiriman->status = false;
        $this->pengiriman->save();

        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function render()
    {
        return view('livewire.approval-pengiriman');
    }
}
