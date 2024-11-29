<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ScanQrCodePermissions extends Component
{
    public $allPermissions = []; // All permissions grouped by category
    public $permissionLabels = []; // All permissions grouped by category
    public $permissions = []; // All permissions grouped by category
    public $selectedPermissions = []; // Permissions selected by the user
    public $categories = []; // Permissions selected by the user
    public $selectedRiwayat;

    public function updatedSelectedPermissions()
    {
        // Find the role by ID
        $role = Role::find(6);

        // Convert selected permission names to their IDs
        $permissionIds = Permission::whereIn('name', $this->selectedPermissions)->pluck('id')->toArray();

        // Sync the permissions to the role
        $role->permissions()->sync($permissionIds);
    }

    public function updatedSelectedRiwayat()
    {
        // Find the role by ID
        $role = Role::find(6);

        // dd($this->selectedRiwayat);
        // Convert selected permission names to their IDs
        $permissionIds = Permission::where('name', $this->selectedRiwayat)->value('id');


        // Sync the permissions to the role
        $role->permissions()->sync($permissionIds);
    }

    public function mount()
    {
        $role = Role::find(6); // Example role ID
        $categories = [
            'Informasi Umum' => ['nama', 'kategori', 'kode', 'systemcode', 'aset_keterangan', 'status', 'foto', 'lampiran'],
            'Jika Status Aset adalah Non-Aktif, apa saja item yang muncul?' => ['nonaktif_tanggal', 'nonaktif_alasan', 'nonaktif_keterangan'],
            'Detail Aset' => ['detil_merk', 'detil_tipe', 'detil_produsen', 'detil_noseri', 'detil_thnproduksi', 'detil_deskripsi'],
            'Pembelian' => ['tanggalbeli', 'toko', 'invoice', 'jumlah', 'hargasatuan', 'hargatotal'],
            'Umur & Penyusutan' => ['umur', 'penyusutan', 'usia', 'nilaisekarang'],
            'Keuangan, Agenda, dan Jurnal' => ['keuangan', 'agenda', 'jurnal'],
            'Riwayat' => ['riwayat_terakhir', 'riwayat_semua', 'riwayat_tidak'],
            'Jika Riwayat Aset ditampilkan, apa saja item yang muncul?' => ['riwayat_tanggal', 'riwayat_person', 'riwayat_lokasi', 'riwayat_jumlah', 'riwayat_kondisi', 'riwayat_kelengkapan', 'riwayat_keterangan'],
        ];

        // Permission labels mapping
        $this->permissionLabels = [
            'nama' => 'Nama Aset',
            'kategori' => 'Kategori',
            'kode' => 'Kode Aset',
            'systemcode' => 'Kode Sistem',
            'aset_keterangan' => 'Keterangan Tambahan',
            'status' => 'Status Aset (Aktif/Non-Aktif)',
            'foto' => 'Foto',
            'lampiran' => 'Lampiran',
            'nonaktif_tanggal' => 'Tanggal Non-Aktif',
            'nonaktif_alasan' => 'Sebab',
            'nonaktif_keterangan' => 'Keterangan',
            'detil_merk' => 'Merk',
            'detil_tipe' => 'Tipe',
            'detil_produsen' => 'Produsen',
            'detil_noseri' => 'No Seri / Kode Produksi',
            'detil_thnproduksi' => 'Tahun Produksi',
            'detil_deskripsi' => 'Deskripsi',
            'tanggalbeli' => 'Tanggal Pembelian',
            'toko' => 'Toko / Distributor',
            'invoice' => 'No. Invoice',
            'jumlah' => 'Jumlah Unit',
            'hargasatuan' => 'Harga Satuan',
            'hargatotal' => 'Harga Total',
            'umur' => 'Umur Ekonomi',
            'penyusutan' => 'Nilai Penyusutan per Bulan (Rp)',
            'usia' => 'Usia Aset',
            'nilaisekarang' => 'Nilai Sekarang (Rp)',
            'keuangan' => 'Transaksi Pengeluaran / Pemasukan Aset',
            'agenda' => 'Agenda Aset',
            'jurnal' => 'Jurnal Aset',
            'riwayat_terakhir' => 'Riwayat Terakhir',
            'riwayat_semua' => 'Semua Riwayat',
            'riwayat_tidak' => 'Tidak Ditampilkan',
            'riwayat_tanggal' => 'Tanggal',
            'riwayat_person' => 'Penanggung Jawab',
            'riwayat_lokasi' => 'Lokasi',
            'riwayat_jumlah' => 'Jumlah',
            'riwayat_kondisi' => 'Kondisi',
            'riwayat_kelengkapan' => 'Kelengkapan',
            'riwayat_keterangan' => 'Keterangan',
        ];

        $permissions = Permission::where('type', 0)->pluck('name')->toArray();

        foreach ($categories as $category => $permissionNames) {
            $this->permissions[$category] = array_intersect($permissions, $permissionNames);
        }

        // Preload selected permissions for the role
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->selectedRiwayat = $role->permissions->value('name');
    }


    public function render()
    {
        return view('livewire.scan-qr-code-permissions');
    }
}
