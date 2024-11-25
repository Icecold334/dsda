<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Aset;

class NonAktifAset extends Component
{
    public $nonaktifaset;
    public $isEditing = false;

    // Form fields
    public $status;
    public $tglnonaktif;
    public $alasannonaktif;
    public $ketnonaktif;

    protected $rules = [
        // 'status' => 'required|in:0,1',
        'tglnonaktif' => 'required_if:status,0|date',
        'alasannonaktif' => 'required_if:status,0|string',
        'ketnonaktif' => 'nullable|string|max:255',
    ];

    public function mount($nonaktifaset)
    {
        $this->nonaktifaset = $nonaktifaset;

        // Initialize fields
        $this->status = $nonaktifaset->status;
        $this->tglnonaktif = $nonaktifaset->tglnonaktif ? date('d F Y', $nonaktifaset->tglnonaktif) : ''; // Format tanggal menjadi d-m-Y
        $this->alasannonaktif = $nonaktifaset->alasannonaktif;
        $this->ketnonaktif = $nonaktifaset->ketnonaktif;
    }


    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function save()
    {
        $this->validate();

        $this->nonaktifaset->update([
            'tglnonaktif' => $this->tglnonaktif ? strtotime($this->tglnonaktif) : null,
            'alasannonaktif' => $this->alasannonaktif,
            'ketnonaktif' => $this->ketnonaktif,
        ]);

        $this->isEditing = false; // Keluar dari mode edit
        session()->flash('message', 'Data berhasil diperbarui.');
    }



    public function activateAsset()
    {
        $this->nonaktifaset->update(['status' => 1]);
        session()->flash('message', 'Aset berhasil diaktifkan.');
        return redirect()->route('nonaktifaset.index'); // Redirect ke halaman utama
    }

    public function render()
    {
        return view('livewire.non-aktif-aset');
    }
}
