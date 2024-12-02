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

        // Konversi nama permission terpilih ke ID mereka
        $permissionIds = Permission::whereIn('name', $this->selectedPermissions)->pluck('id')->toArray();

        // Ambil ID permission terkait "Riwayat" jika ada
        $riwayatPermissionId = Permission::where('name', $this->selectedRiwayat)->value('id');

        if ($riwayatPermissionId) {
            // Pastikan permission untuk "Riwayat" tidak terhapus
            $permissionIds[] = $riwayatPermissionId;
        }

        // Sinkronisasi izin
        $role->permissions()->sync($permissionIds);
    }


    public function updatedSelectedRiwayat()
    {
        // Find the role by ID
        $role = Role::find(6);

        // Dapatkan ID permission yang dipilih untuk "Riwayat"
        $permissionId = Permission::where('name', $this->selectedRiwayat)->value('id');

        if ($permissionId) {
            // Ambil semua permission ID yang saat ini ada pada role
            $existingPermissionIds = $role->permissions->pluck('id')->toArray();

            // Filter untuk hanya menyimpan izin yang bukan kategori "Riwayat"
            $nonRiwayatPermissionIds = Permission::whereNotIn('name', ['riwayat_terakhir', 'riwayat_semua', 'riwayat_tidak'])
                ->whereIn('id', $existingPermissionIds)
                ->pluck('id')
                ->toArray();

            // Tambahkan izin yang baru untuk "Riwayat" (mengganti izin sebelumnya)
            $updatedPermissionIds = array_merge($nonRiwayatPermissionIds, [$permissionId]);

            // Sinkronisasi hanya izin yang diperbarui
            $role->permissions()->sync($updatedPermissionIds);
        }
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

        // Preload permissions yang sudah dipilih untuk role ini
        $this->selectedPermissions = $role->permissions
            ->whereNotIn('name', ['riwayat_terakhir', 'riwayat_semua', 'riwayat_tidak'])
            ->pluck('name')
            ->toArray();

        // Preload hanya permission terkait Riwayat
        $this->selectedRiwayat = $role->permissions
            ->whereIn('name', ['riwayat_terakhir', 'riwayat_semua', 'riwayat_tidak'])
            ->value('name');
    }


    public function render()
    {
        return view('livewire.scan-qr-code-permissions');
    }
}
