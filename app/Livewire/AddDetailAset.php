<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class AddDetailAset extends Component
{

    #[Validate]
    public $merk;
    public $tipe;
    public $produsen;
    public $noseri;
    public $tahunProduksi;
    public $deskripsi;

    public function rules()
    {
        return [
            'merk' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'merk.required' => 'Merk wajib diisi!',
            'merk.string' => 'Merk harus berupa teks!',
            'merk.max' => 'Merk tidak boleh lebih dari 255 karakter!',
            'merk.unique' => 'Merk sudah terdaftar, pilih merk lain!',
        ];
    }

    #[On('send-props-aset')]
    public function saveAset()
    {
        $this->validate();
        $this->dispatch('send-detail', [
            'detail' => [
                'merk' => $this->merk,
                'tipe' => $this->tipe,
                'produsen' => $this->produsen,
                'noseri' => $this->noseri,
                'tahunProduksi' => $this->tahunProduksi,
                'deskripsi' => $this->deskripsi
            ]
        ]);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.add-detail-aset');
    }
}
