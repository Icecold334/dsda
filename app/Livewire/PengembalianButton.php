<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use Livewire\WithFileUploads;

class PengembalianButton extends Component
{
    use WithFileUploads;

    public $permintaan;
    public $fotoPengembalian;

    public function mount($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function simpanPengembalian()
    {
        $this->validate([
            'fotoPengembalian' => 'required|image|max:2048',
        ]);

        $uploadedFile = $this->fotoPengembalian;

        // Buat nama file unik
        $fileName = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

        // Simpan file ke folder 'pengembalian' di disk 'public'
        $storedFilePath = $uploadedFile->storeAs('pengembalianUmum', $fileName, 'public');

        // Ambil hanya nama filenya
        $fileNameOnly = basename($storedFilePath);
        $this->permintaan->update([
            'img_pengembalian' => $fileNameOnly,
        ]);

        // Update data aset terkait
        foreach ($this->permintaan->peminjamanAset as $peminjaman) {
            if ($peminjaman->aset_id) {
                Aset::where('id', $peminjaman->aset_id)->update([
                    'peminjaman' => 1
                ]);
            }
        }

        $this->dispatch('success', 'Pengembalian berhasil!');
    }
    public function render()
    {
        return view('livewire.pengembalian-button');
    }
}
