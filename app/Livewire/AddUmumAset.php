<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Option;
use Livewire\Component;
use App\Models\Kategori;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class AddUmumAset extends Component
{
    use WithFileUploads;

    #[Validate]
    public $nama;
    #[Validate]
    public $kode;
    public $kategoris;
    public $kategori;
    public $asetId;
    public $img;

    public function rules()
    {
        $kodeRule = $this->asetId ? 'required|string|max:100|unique:aset,kode,' . $this->asetId : 'required|string|max:100|unique:aset,kode';

        return [
            'nama' => 'required|string|max:255',
            'kode' => $kodeRule,
            // 'img' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'  // Assuming 'img' is the image upload field
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Aset wajib diisi!',
            'nama.string' => 'Nama Aset harus berupa teks!',
            'nama.max' => 'Nama Aset tidak boleh lebih dari 255 karakter!',
            'kode.required' => 'Kode Aset wajib diisi!',
            'kode.string' => 'Kode Aset harus berupa teks!',
            'kode.max' => 'Kode Aset tidak boleh lebih dari 100 karakter!',
            'kode.unique' => 'Kode Aset sudah digunakan!',
            'kategori.required' => 'Kategori wajib diisi!',
            'kategori.exists' => 'Kategori tidak valid!',
            'img.image' => 'File harus berupa gambar!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'img.mimes' => 'Format gambar harus jpeg, png, jpg, gif!'
        ];
    }
    public function mount()
    {
        $this->kode = $this->autoKodeaset();
        $this->kategoris = Kategori::all();
    }

    private function autoKodeaset()
    {
        $templateKodeaset = Option::find(1)->kodeaset;
        $user_id = Auth::user()->id; // Get the current logged-in user's ID

        // Get the count of Aset where 'prepublish' is 0
        $jumlahAset = Aset::where('user_id', $user_id)
            ->where('prepublish', 0)
            ->count();

        $nomor = $jumlahAset + 1;

        // Using Carbon for date manipulation
        $tanggal = Carbon::now()->format('d');
        $bulanAngka = Carbon::now()->format('m');
        $bulanRomawi = $this->bulanRomawi($bulanAngka);
        $tahun = Carbon::now()->format('Y');

        // Execute replacements
        $kodeAset = str_replace(
            ['[nomor]', '[tanggal]', '[bulan-angka]', '[bulan-romawi]', '[tahun]'],
            [$nomor, $tanggal, $bulanAngka, $bulanRomawi, $tahun],
            $templateKodeaset
        );

        return $kodeAset;
    }

    private function bulanRomawi($bulan)
    {
        $map = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];
        return $map[$bulan] ?? '';
    }

    #[On('send-props-aset')]
    public function saveAset()
    {
        $this->validate();
        $this->dispatch('send-umum', ['umum' => ['nama' => $this->nama, 'kode' => $this->kode, 'kategori' => $this->kategori]]);
        $this->resetExcept(['kategoris']);
        $this->mount();
    }


    public function render()
    {
        return view('livewire.add-umum-aset');
    }
}
