# FITUR HAPUS PERMINTAAN

## Deskripsi

Fitur ini memungkinkan pengguna untuk menghapus permintaan material yang mereka buat, dengan syarat-syarat tertentu untuk menjaga integritas data dan proses approval. **Menggunakan SweetAlert untuk konfirmasi yang ringan dan user-friendly.**

## Syarat dan Ketentuan

### 1. Hanya Pemohon yang Dapat Menghapus

-   Permintaan hanya dapat dihapus oleh user yang membuat permintaan tersebut
-   Validasi dilakukan dengan membandingkan `user_id` permintaan dengan `auth()->id()`

### 2. Permintaan Belum Di-approve Sama Sekali

-   Permintaan yang sudah memiliki approval (disetujui atau ditolak) **tidak dapat dihapus**
-   Validasi menggunakan query: `$permintaan->persetujuan()->whereNotNull('is_approved')->exists()`
-   Ini memastikan bahwa proses yang sudah berjalan tidak dapat dibatalkan sembarangan

### 3. Status Permintaan Masih Awal

-   Permintaan dengan status > 0 (sudah diproses) tidak dapat dihapus
-   Hanya permintaan dengan status `null` atau `0` yang dapat dihapus

## Implementasi

### 1. Backend (DataPermintaanMaterial.php)

#### Property untuk Modal

```php
public $showDeleteModal = false;
public $permintaanToDelete = null;
```

#### Method untuk Konfirmasi

```php
public function confirmDelete($permintaanId)
{
    $this->permintaanToDelete = $permintaanId;
    $this->showDeleteModal = true;
}
```

#### Method untuk Membatalkan

```php
public function cancelDelete()
{
    $this->permintaanToDelete = null;
    $this->showDeleteModal = false;
}
```

#### Method Hapus Utama

```php
public function deletePermintaan()
{
    // Validasi kepemilikan dan status
    // Hapus dalam transaksi database
    // Bersihkan file terkait
}
```

#### Logika can_delete dalam mapData()

```php
$canDelete = false;
if ($tipe === 'permintaan') {
    $isOwner = $item->user_id === auth()->id();
    $hasAnyApproval = $item->persetujuan()->whereNotNull('is_approved')->exists();
    $canDelete = $isOwner && !$hasAnyApproval;
}
```

### 2. Frontend (data-permintaan-material.blade.php)

#### Tombol Hapus

```blade
@if ($permintaan['can_delete'])
    <button wire:click="confirmDelete({{ $permintaan['id'] }})"
        class="ml-2 text-red-600 hover:text-white hover:bg-red-600 px-3 py-2 rounded border border-red-600">
        <i class="fa-solid fa-trash"></i>
    </button>
@endif
```

#### Modal Konfirmasi

-   Modal dengan peringatan yang jelas
-   Tombol Batal dan Hapus
-   Loading state saat proses hapus
-   Desain yang user-friendly

## Keamanan

### 1. Validasi Ganda

-   Validasi di frontend (tombol tidak muncul jika tidak boleh hapus)
-   Validasi di backend (double check sebelum hapus)

### 2. Transaksi Database

-   Semua operasi hapus dibungkus dalam `DB::transaction()`
-   Jika ada error, semua perubahan akan di-rollback

### 3. Penghapusan File

-   File lampiran foto dan dokumen ikut dihapus dari storage
-   Mencegah file orphan yang memenuhi storage

## Data yang Dihapus

1. **Persetujuan yang pending** (belum ada keputusan)
2. **Detail permintaan material** (permintaan_material table)
3. **Lampiran foto** (foto_permintaan_material table + file fisik)
4. **Lampiran dokumen** (lampiran_permintaan table + file fisik)
5. **Permintaan utama** (detail_permintaan_material table)

## Flash Messages

### Success

-   "Permintaan berhasil dihapus."

### Error Messages

-   "Permintaan tidak ditemukan."
-   "Anda hanya bisa menghapus permintaan yang Anda buat sendiri."
-   "Permintaan yang sudah di-proses (disetujui/ditolak) tidak dapat dihapus."
-   "Permintaan dengan status ini tidak dapat dihapus."
-   "Terjadi kesalahan saat menghapus permintaan: [error detail]"

## UI/UX

### 1. Visual Feedback

-   Tombol hapus hanya muncul jika memenuhi syarat
-   Tooltip informatif pada tombol
-   Loading spinner saat proses hapus
-   Flash message untuk feedback

### 2. Konfirmasi Berlapis

-   Modal konfirmasi dengan pesan yang jelas
-   Peringatan bahwa tindakan tidak dapat dibatalkan
-   Tombol dengan warna yang kontras (merah untuk bahaya)

## Testing

### Test Cases yang Direkomendasikan

1. **Test Positive Flow**

    - User hapus permintaan sendiri yang belum di-approve
    - Semua data terkait berhasil dihapus
    - Flash message success muncul

2. **Test Negative Flow**

    - User coba hapus permintaan orang lain → Error
    - User coba hapus permintaan yang sudah di-approve → Error
    - User coba hapus permintaan dengan status tinggi → Error

3. **Test Edge Cases**

    - Permintaan dengan lampiran banyak
    - Permintaan yang tidak ditemukan
    - Error saat hapus file

4. **Test UI**
    - Tombol hapus tidak muncul jika tidak boleh hapus
    - Modal konfirmasi berfungsi dengan baik
    - Loading state berjalan dengan baik

## Maintenance

### 1. Log Monitoring

-   Monitor error saat penghapusan
-   Track permintaan yang sering dihapus (possible issues)

### 2. Database Cleanup

-   Pastikan tidak ada orphan records
-   Monitor ukuran storage file

### 3. Performance

-   Index yang tepat untuk query persetujuan
-   Optimasi query untuk cek `can_delete`

## Catatan Tambahan

### 1. Tidak Berlaku untuk Peminjaman

-   Fitur ini khusus untuk permintaan material
-   Peminjaman aset memiliki logika yang berbeda

### 2. Role-based Access

-   Bisa ditambahkan role tertentu yang bisa hapus permintaan apa saja
-   Misalnya Admin bisa hapus semua permintaan

### 3. Soft Delete (Future Enhancement)

-   Bisa diimplementasikan soft delete untuk audit trail
-   Permintaan "dihapus" tapi masih ada di database dengan flag deleted_at
