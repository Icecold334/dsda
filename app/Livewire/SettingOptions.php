<?php

namespace App\Http\Livewire;

use App\Models\Option;
use Livewire\Component;


class SettingOptions extends Component
{
    public $kode_aset;
    public $qr_judul;
    public $qr_judul_other;
    public $qr_baris1;
    public $qr_baris1_other;
    public $qr_baris2;
    public $qr_baris2_other;

    protected $rules = [
        'kode_aset' => 'required|string|max:255',
        'qr_judul' => 'required|string|max:255',
        'qr_judul_other' => 'nullable|string|max:25',
        'qr_baris1' => 'required|string|max:255',
        'qr_baris1_other' => 'nullable|string|max:25',
        'qr_baris2' => 'required|string|max:255',
        'qr_baris2_other' => 'nullable|string|max:25',
    ];

    public function mount()
    {
        $option = Option::find(1); // Ambil data berdasarkan ID 1
        $this->kode_aset = $option->kodeaset;
        dd($this->kode_aset);
        $this->qr_judul = $option->qr_judul;
        $this->qr_judul_other = $option->qr_judul_other;
        $this->qr_baris1 = $option->qr_baris1;
        $this->qr_baris1_other = $option->qr_baris1_other;
        $this->qr_baris2 = $option->qr_baris2;
        $this->qr_baris2_other = $option->qr_baris2_other;
    }

    public function save()
    {
        $this->validate();
        // Cari data berdasarkan ID 1
        $option = Option::find(1);
        $option->update([
            'kodeaset' => $this->kode_aset,
            'qr_judul' => $this->qr_judul,
            'qr_judul_other' => $this->qr_judul === 'other' ? $this->qr_judul_other : null,
            'qr_baris1' => $this->qr_baris1,
            'qr_baris1_other' => $this->qr_baris1 === 'other' ? $this->qr_baris1_other : null,
            'qr_baris2' => $this->qr_baris2,
            'qr_baris2_other' => $this->qr_baris2 === 'other' ? $this->qr_baris2_other : null,
        ]);

        // Flash success message
        session()->flash('message', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.setting-options');
    }
}
