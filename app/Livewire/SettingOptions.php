<?php

namespace App\Livewire;

use App\Models\Option;
use Livewire\Component;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class SettingOptions extends Component
{
    public $kode_aset;
    public $qr_judul;
    public $qr_judul_other;
    public $qr_baris1;
    public $qr_baris1_other;
    public $qr_baris2;
    public $qr_baris2_other;
    public $roles;
    public $search = ''; // Variabel pencarian
    public $suggestions = []; // Menyimpan daftar suggestions yang muncul

    // public function loadSuggestions()
    // {
    //     // Jika pencarian kosong, tampilkan semua suggestions (atau sesuai batasan Anda)
    //     if (empty($this->search)) {
    //         $this->suggestions = Role::where('id', '>', 2)
    //             // ->limit(5) // Batasi jumlah suggestions
    //             ->pluck('name') // Hanya ambil nama role
    //             ->toArray();
    //     }
    // }

    // public function updatedSearch()
    // {
    //     // Update suggestions berdasarkan input pencarian
    //     if ($this->search) {
    //         $this->suggestions = Role::where('id', '>', 2)
    //             ->where('name', 'like', '%' . $this->search . '%') // Filter berdasarkan input
    //             // ->limit(5) // Batasi jumlah suggestions
    //             ->pluck('name') // Hanya ambil nama role
    //             ->toArray();
    //     } else {
    //         $this->suggestions = []; // Kosongkan suggestions jika input kosong
    //     }
    // }

    // public function selectSuggestion($value)
    // {
    //     $this->search = $value; // Set input pencarian dengan suggestion yang dipilih

    //     // Update daftar roles berdasarkan pilihan suggestion
    //     $this->roles = Role::where('id', '>', 2)
    //         ->where('name', 'like', '%' . $this->search . '%')
    //         ->get();

    //     $this->suggestions = []; // Kosongkan suggestions setelah dipilih
    // }

    // public function clearSuggestions()
    // {
    //     // Bersihkan saran ketika input kehilangan fokus
    //     $this->suggestions = [];
    // }


    // public function resetSearch()
    // {
    //     $this->search = ''; // Kosongkan pencarian
    //     $this->suggestions = []; // Kosongkan suggestions
    //     $this->roles = Role::where('id', '>', 2)->get(); // Kembalikan daftar roles awal
    // }

    public function updatedSearch()
    {
        // Filter roles berdasarkan input pencarian
        $this->roles = Role::where('id', '>', 2)
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function resetSearch()
    {
        // Kosongkan pencarian dan tampilkan semua roles
        $this->search = '';
        $this->roles = Role::where('id', '>', 2)->get();
    }


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
        $this->qr_judul = $option->qr_judul;
        $this->qr_judul_other = $option->qr_judul_other;
        $this->qr_baris1 = $option->qr_baris1;
        $this->qr_baris1_other = $option->qr_baris1_other;
        $this->qr_baris2 = $option->qr_baris2;
        $this->qr_baris2_other = $option->qr_baris2_other;
        // Load all roles
        // $roles = Role::where('id', '>', 1)->get();
        // $this->roles = $roles->filter(function ($role) {
        //     return $role->name != 'guest';
        // })->map(function ($role) {
        //     return [
        //         'id' => $role->id,
        //         'name' => formatRole($role->name),
        //         'guard_name' => $role->guard_name,
        //     ];
        // });
        $this->roles = Role::where('id', '>', 2)->get();
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
