<?php

namespace App\Livewire;

use App\Models\Aset;
use App\Models\User;
use App\Models\Ruang;
use Livewire\Component;
use Illuminate\Support\Str;
use Termwind\Components\Dd;
use Livewire\WithFileUploads;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class PengembalianButton extends Component
{
    use WithFileUploads;

    public $permintaan;
    public $fotoPengembalian;
    public $keteranganPengembalian;


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

        $kategori = $this->permintaan->kategori;

        $this->permintaan->update([
            'img_pengembalian' => $fileNameOnly,
            'keterangan_pengembalian' => $this->keteranganPengembalian,
            'proses' => 1,
        ]);
        // Update data aset terkait
        foreach ($this->permintaan->peminjamanAset as $peminjaman) {
            if ($peminjaman->aset_id) {
                if ($kategori->id == 2) {
                    // Jika kategori == 2, gunakan model Ruang
                    Ruang::where('id', $peminjaman->aset_id)->update([
                        'peminjaman' => 1
                    ]);
                } else {
                    // Jika kategori bukan 2, gunakan model Aset
                    Aset::where('id', $peminjaman->aset_id)->update([
                        'peminjaman' => 1
                    ]);
                }
            }
        }
        $unitId = $this->permintaan->sub_unit_id;
        $users = User::role('Customer Services')
            ->where('unit_id', $unitId)
            ->get();
        $alert = 'Peminjaman dengan kode <span class="font-bold">' .  $this->permintaan->kode_peminjaman .
            '</span> Sudah Mengembalikan Peminjaman <span class="font-bold">' .  $kategori->nama .
            '</span> dengan Keterangan <span class="font-bold">' . $this->keteranganPengembalian . '</span>';

        Notification::send($users, new UserNotification($alert, "/permintaan/peminjaman/{$this->permintaan->id}"));

        // $this->dispatch('success', 'Pengembalian berhasil!');
        return redirect()->to('permintaan/peminjaman/' . $this->permintaan->id)->with('success', 'Pengembalian berhasil');
    }
    public function render()
    {
        return view('livewire.pengembalian-button');
    }
}
