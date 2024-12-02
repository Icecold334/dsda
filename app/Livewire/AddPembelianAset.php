<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class AddPembelianAset extends Component
{
    #[Validate]
    public $tanggalPembelian, $toko, $invoice, $jumlah, $hargaSatuan, $hargaTotal;

    public function rules()
    {
        return [
            'tanggalPembelian' => 'required|date',
            'toko' => 'required|string|max:255',
            'invoice' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'hargaSatuan' => 'required|min:0',
        ];
    }
    public function messages()
    {
        return [
            'tanggalPembelian.required' => 'Tanggal pembelian wajib diisi!',
            'tanggalPembelian.date' => 'Tanggal pembelian harus berupa tanggal yang valid!',
            'toko.required' => 'Nama toko/distributor wajib diisi!',
            'toko.string' => 'Nama toko/distributor harus berupa teks!',
            'toko.max' => 'Nama toko/distributor tidak boleh lebih dari 255 karakter!',
            'invoice.string' => 'Nomor invoice harus berupa teks!',
            'invoice.max' => 'Nomor invoice tidak boleh lebih dari 255 karakter!',
            'jumlah.required' => 'Jumlah wajib diisi!',
            'jumlah.integer' => 'Jumlah harus berupa angka!',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 1!',
            'hargaSatuan.required' => 'Harga satuan wajib diisi!',
            'hargaSatuan.numeric' => 'Harga satuan harus berupa angka!',
            'hargaSatuan.min' => 'Harga satuan tidak boleh kurang dari 0!',
            'img.image' => 'File harus berupa gambar!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'img.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif!'
        ];
    }

    #[On('send-props-aset')]
    public function saveAset()
    {
        $this->validate();
        $this->dispatch('send-pembelian', ['pembelian' => [
            'tanggalPembelian' => $this->tanggalPembelian,
            'toko' => $this->toko,
            'invoice' => $this->invoice,
            'jumlah' => $this->jumlah,
            'hargaSatuan' => (int)str_replace('.', '', $this->hargaSatuan),
            'hargaTotal' => (int)str_replace('.', '', $this->hargaTotal),
        ]]);
        $this->resetExcept(['kategoris']);
        $this->mount();
    }

    public function mount() {}
    public function render()
    {
        return view('livewire.add-pembelian-aset');
    }
}
