<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class FormRab extends Component
{
    public $listCount;

    // Semua field yang dipakai di form
    public $program;
    public $nama;
    public $sub_kegiatan;
    public $rincian_sub_kegiatan;
    public $kode_rekening;
    public $mulai;
    public $selesai;
    public $lokasi;

    #[On('listCount')]
    public function fillCount($count)
    {
        $this->listCount = $count;
    }

    public function updated($field)
    {
        // Kirim semua data yang relevan saat field berubah
        $this->dispatch('dataKegiatan', data: [
            'program' => $this->program,
            'nama' => $this->nama,
            'sub_kegiatan' => $this->sub_kegiatan,
            'rincian_sub_kegiatan' => $this->rincian_sub_kegiatan,
            'kode_rekening' => $this->kode_rekening,
            'mulai' => $this->mulai,
            'selesai' => $this->selesai,
            'lokasi' => $this->lokasi,
        ]);
    }

    public function render()
    {
        return view('livewire.form-rab');
    }
}
