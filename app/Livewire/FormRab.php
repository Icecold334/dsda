<?php

namespace App\Livewire;

use App\Models\Program;
use Livewire\Component;
use App\Models\Kegiatan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FormRab extends Component
{
    public $listCount;

    public $kecamatan_id, $kelurahan_id;
    public $kecamatans = [], $kelurahans = [];
    public $saluran;
    // Semua field yang dipakai di form
    public $program, $programs = [];
    public $nama, $namas = [];
    public $sub_kegiatan, $sub_kegiatans = [];
    public $aktivitas_sub_kegiatan, $aktivitas_sub_kegiatans = [];
    public $kode_rekening, $kode_rekenings = [];
    public $jenis;
    public $mulai, $Rkb, $RKB, $sudin, $withSaluran = null, $saluran_id, $saluranSelected = [];
    public $selesai;
    public $lokasi;
    public function mount()
    {
        $saluran = collect(app('JakartaDataset'));
        $mapping = [
            'tersier' => 'namaPhb',
            'sekunder' => 'namaSungai',
            'primer' => 'namaSungai',
        ];

        $hasil = collect($mapping)->mapWithKeys(function ($uniqueKey, $tipe) use ($saluran) {
            return [$tipe => collect($saluran[$tipe])->unique($uniqueKey)];
        });
        $this->saluran = $hasil;
        $this->programs = Program::where('bidang_id', $this->unit_id)->get();
        $this->kecamatans = Kecamatan::where('unit_id', $this->unit_id)->get();
        // $this->namas = $this->programs->children;
        // $this->sub_kegiatans = $this->namas->children;
        // $this->rincian_sub_kegiatans = $this->sub_kegiatans->children;
        // $this->kode_rekenings = $this->rincian_sub_kegiatans->children;
    }
    public function updatedKecamatanId()
    {
        $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        $this->kelurahan_id = null;
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
        if ($field === 'withSaluran') {
            if ($this->withSaluran) {
                $this->saluranSelected = $this->saluran[$this->withSaluran];
            } else {
                $this->withSaluran = null;
                $this->saluranSelected = [];
            }
            $this->saluran_id = null;
            // $this->kode_rekening = null;
        }

        $this->dispatch('dataKegiatan', data: [
            'program' => $this->program,
            'nama' => $this->nama,
            'sub_kegiatan' => $this->sub_kegiatan,
            'aktivitas_sub_kegiatan' => $this->aktivitas_sub_kegiatan,
            'kode_rekening' => $this->kode_rekening,
            'kelurahan_id' => $this->kelurahan_id,
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
