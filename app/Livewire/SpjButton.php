<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Termwind\Components\Dd;
use Livewire\WithFileUploads;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class SpjButton extends Component
{
    use WithFileUploads;
    public $permintaan;
    public $file;
    public $keterangan;

    public function mount($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function simpan()
    {
        $this->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $uploadedFile = $this->file;

        // Ambil nama pemohon
        $namaPemohon = Str::slug($this->permintaan->user->name ?? 'pemohon', '_');

        // Ambil tanggal upload atau bisa pakai tanggal permintaan
        $tanggal = now()->format('Y-m-d');

        // Buat nama file: SPJ_namapemohon_tanggal.pdf
        $fileName = "SPJ_{$namaPemohon}_{$tanggal}." . $uploadedFile->getClientOriginalExtension();

        // Simpan file ke folder 'pengembalianUmum' di disk 'public'
        $storedFilePath = $uploadedFile->storeAs('pengembalianUmum', $fileName, 'public');

        // Ambil hanya nama file-nya
        $fileNameOnly = basename($storedFilePath);

        // Update ke permintaan
        $this->permintaan->update([
            'file' => $fileNameOnly,
            'keterangan_done' => $this->keterangan,
            'cancel' => 0,
            'proses' => 1,
        ]);
        $unitId = $this->permintaan->sub_unit_id;
        $users = User::role('Customer Services')
            ->where('unit_id', $unitId)
            ->where('name', 'like', '%Nisya%')
            ->get();
        $alert = 'Permintaan dengan kode <span class="font-bold">' .  $this->permintaan->kode_permintaan .
            '</span> Sudah Menggunggah Bukti SPJ Permintaan Konsumsi';

        Notification::send($users, new UserNotification($alert, "/permintaan/permintaan/{$this->permintaan->id}"));


        // $this->dispatch('success', 'Pengembalian berhasil!');
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('success', 'File berhasil diupload');
    }

    public function render()
    {
        return view('livewire.spj-button');
    }
}
