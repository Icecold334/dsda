<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DetailPermintaanStok;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class TanggalKDOForm extends Component
{
    public $permintaanId;
    public $tanggal_masuk;
    public $tanggal_keluar;

    public function mount($permintaanId)
    {
        $permintaan = DetailPermintaanStok::findOrFail($permintaanId);
        $this->tanggal_masuk = $permintaan->tanggal_masuk;
        $this->tanggal_keluar = $permintaan->tanggal_keluar;
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date|after_or_equal:tanggal_masuk',
        ]);
    }

    public function simpan()
    {
        $this->validate([
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        $permintaan = DetailPermintaanStok::findOrFail($this->permintaanId);
        $permintaan->update([
            'tanggal_masuk' => $this->tanggal_masuk,
            'tanggal_keluar' => $this->tanggal_keluar,
        ]);

        $mess = "Permintaan dengan kode <span class=\"font-bold\">{$permintaan->kode_permintaan}</span> 
        Tanggal Masuk <span class=\"font-bold\">{$this->tanggal_masuk}</span> dan 
        Tanggal Keluar <span class=\"font-bold\">{$this->tanggal_keluar}</span> Berubah.";
        $user = $permintaan->user;
        Notification::send($user, new UserNotification(
            $mess,
            "/permintaan/permintaan/{$permintaan->id}"
        ));

        return redirect()->to('permintaan/permintaan/' . $permintaan->id)->with('success', 'Update Tanggal!');
    }

    public function render()
    {
        return view('livewire.tanggal-k-d-o-form');
    }
}
