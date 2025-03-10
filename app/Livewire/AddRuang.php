<?php

namespace App\Livewire;

use App\Models\Aset;
use App\Models\User;
use App\Models\Ruang;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AddRuang extends Component
{
    public $id;
    public $tipe;
    public $ruangs;
    public $pj;
    public $ruang;
    public $user;

    public $suggestions = [
        'user' => [],
    ];

    public function fetchSuggestions($field, $value)
    {

        $this->suggestions[$field] = [];
        if ($field === 'user') {
            $this->suggestions[$field] = User::where('name', 'like', '%' . $value . '%')->where('id', '>', 3)
                ->pluck('name')->toArray();
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'user') {
            $this->user = $value;
        }
        $this->suggestions[$field] = [];
    }

    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
        // $this->showSuggestionsMerk = false;
    }

    public function mount()
    {
        $this->ruangs = Ruang::all();

        if ($this->id) {
            $ruang = Ruang::find($this->id);
            $this->ruang = $ruang->nama;
            $this->user = $ruang->penanggungjawab->name;
        }
    }

    public function removeRuang()
    {
        Ruang::destroy($this->id);
        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil dihapus.');
    }
    public function saveRuang()
    {
        // Cari ID user berdasarkan nama yang diketik atau dipilih
        $user = User::where('name', $this->user)->first();

        if (!$user) {
            session()->flash('error', 'Penanggung Jawab tidak ditemukan.');
            return;
        }

        $ruang = Ruang::updateOrCreate(
            ['id' => $this->id ?? 0], // Jika ada ID, update, jika tidak, buat baru
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->ruang,
                'pj_id' => $user->id, // Simpan ID User ke pj_id
                'slug' => Str::slug($this->ruang),
            ]
        );

        dd($ruang);
        if ($ruang->wasRecentlyCreated && $this->ruang) {
            return redirect()->route('ruang.index')->with('success', 'Berhasil Menambah Ruang Rapat');
        } else {
            return redirect()->route('ruang.index')->with('success', 'Berhasil Mengubah Ruang Rapat');
        }
    }

    public function render()
    {
        return view('livewire.add-ruang');
    }
}
