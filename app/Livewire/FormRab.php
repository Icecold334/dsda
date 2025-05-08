<?php

namespace App\Livewire;

use App\Models\Kegiatan;
use App\Models\Program;
use Livewire\Attributes\On;
use Livewire\Component;

class FormRab extends Component
{
    public $listCount;

    // Semua field yang dipakai di form
    public $program, $programs = [];
    public $nama, $namas = [];
    public $sub_kegiatan, $sub_kegiatans = [];
    public $aktivitas_sub_kegiatan, $aktivitas_sub_kegiatans = [];
    public $kode_rekening, $kode_rekenings = [];
    public $jenis;
    public $mulai, $Rkb, $RKB, $sudin;
    public $selesai;
    public $lokasi;
    public function mount()
    {
        $this->programs = Program::where('bidang_id', $this->unit_id)->get();
        // $this->namas = $this->programs->children;
        // $this->sub_kegiatans = $this->namas->children;
        // $this->rincian_sub_kegiatans = $this->sub_kegiatans->children;
        // $this->kode_rekenings = $this->rincian_sub_kegiatans->children;
    }

    #[On('listCount')]
    public function fillCount($count)
    {
        $this->listCount = $count;
    }

    public function updated($field)
    {
        if ($field === 'program') {
            $this->namas = \App\Models\Kegiatan::where('program_id', $this->program)->get();
            $this->nama = null;
            $this->sub_kegiatan = null;
            $this->sub_kegiatans = [];
            $this->aktivitas_sub_kegiatan = null;
            $this->aktivitas_sub_kegiatans = [];
            $this->kode_rekening = null;
            $this->kode_rekenings = [];
        }

        if ($field === 'nama') {
            $this->sub_kegiatans = \App\Models\SubKegiatan::where('kegiatan_id', $this->nama)->get();
            $this->sub_kegiatan = null;
            $this->aktivitas_sub_kegiatan = null;
            $this->aktivitas_sub_kegiatans = [];
            $this->kode_rekening = null;
            $this->kode_rekenings = [];
        }

        if ($field === 'sub_kegiatan') {
            $this->aktivitas_sub_kegiatans = \App\Models\AktivitasSubKegiatan::where('sub_kegiatan_id', $this->sub_kegiatan)->get();
            $this->aktivitas_sub_kegiatan = null;
            $this->kode_rekening = null;
            $this->kode_rekenings = [];
        }

        if ($field === 'aktivitas_sub_kegiatan') {
            $this->kode_rekenings = \App\Models\UraianRekening::where('aktivitas_sub_kegiatan_id', $this->aktivitas_sub_kegiatan)->get();
            $this->kode_rekening = null;
        }

        $this->dispatch('dataKegiatan', data: [
            'program' => $this->program,
            'nama' => $this->nama,
            'sub_kegiatan' => $this->sub_kegiatan,
            'aktivitas_sub_kegiatan' => $this->aktivitas_sub_kegiatan,
            'kode_rekening' => $this->kode_rekening,
            'jenis' => $this->jenis,
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
