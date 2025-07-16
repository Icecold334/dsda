<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class NavItem extends Component
{
    public $href = null;
    public $title, $active;
    public $child = [];
    public $showNav = true; // Flag untuk menentukan apakah item ditampilkan

    public function mount()
    {
        if (!count($this->child) > 0) {
            $this->active = request()->getPathInfo() == $this->href;
        } else {
            foreach ($this->child as $key => $value) {
                // dump($value['href']);
                if (request()->getPathInfo() == $value['href']) {
                    $this->active = request()->getPathInfo() == $value['href'];
                    break;
                }
            }
        }
        if ($this->title == 'Form') {
            if (Auth::user()->cannot('inventaris_tambah_barang_datang')) {
                $this->child =  collect($this->child)->filter(function ($child) {
                    return $child['title'] !== 'Form barang datang';
                })->toArray();
            }
            $this->filterChild('inventaris_tambah_barang_datang', 'Form barang datang');
        }

        // if ($this->title == 'data') {
        // $user = Auth::user();
        // $listcountpermission = $user->getAllPermissions()->pluck('name')->filter(function ($name) {
        //     return Str::contains($name, 'data_');
        // })->count();

        // // Jika tidak ada permission, sembunyikan nav item
        // if ($listcountpermission === 0 && 0) {
        //     $this->showNav = false;
        //     return;
        // }

        // $this->filterChild('data_kategori', 'kategori');
        // $this->filterChild('data_merk', 'Merk');
        // $this->filterChild('data_barang', 'Barang Inventaris');
        // $this->filterChild('data_toko', 'Toko / distributor');
        // $this->filterChild('data_penanggung_jawab', 'Penanggung jawab');
        // $this->filterChild('data_lokasi', 'lokasi');
        // $this->filterChild('data_lokasi_gudang', 'lokasi gudang');
        // $this->filterChild('data_unit_kerja', 'Unit Kerja');
        // }

        if ($this->href == '/qrprint') {
            $this->showNav = !$this->filterChild('qr_print', '/qrprint');
        }
        if ($this->href == '/option') {
            $this->showNav = !$this->filterChild('pengaturan', '/option');
        }
    }
    private function filterChild($permission, $title)
    {
        if (Auth::user()->cannot($permission)) {
            $this->child = collect($this->child)->filter(function ($child) use ($title) {
                return $child['title'] !== $title || $child['href'] !== $title;
            })->toArray();
        }
        return Auth::user()->cannot($permission);
    }

    public function render()
    {
        return view('livewire.nav-item', ['showNav' => $this->showNav]);
    }
}
