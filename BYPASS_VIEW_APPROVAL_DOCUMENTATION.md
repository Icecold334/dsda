# DOKUMENTASI BYPASS VIEW APPROVAL TIMELINE DAN DOKUMEN

## Deskripsi Masalah

Setelah transfer approval dari Citrin (ID 245) ke Yusuf (ID 252) untuk periode 12-19 Agustus 2025, tampilan di view masih menunjukkan role berdasarkan role asli user, bukan berdasarkan konteks approval yang sudah ditransfer.

## File yang Dimodifikasi

### 1. DataPermintaanMaterial.php

**Lokasi**: `app/Livewire/DataPermintaanMaterial.php`  
**Method**: `openApprovalTimeline()`  
**Baris**: 571-607

**Perubahan**:

-   Ditambahkan bypass logic untuk periode 12-19 Agustus 2025
-   Jika approval dilakukan oleh user 252 (Yusuf) dalam periode tersebut, role akan ditampilkan sebagai "Kepala Seksi Pemeliharaan"
-   Logic normal tetap berlaku untuk approval di luar periode transfer

```php
// BYPASS: Untuk periode 12-19 Agustus 2025, user 252 (Yusuf) yang approval
// akan ditampilkan sebagai "Kepala Seksi Pemeliharaan"
// meskipun role aslinya berbeda
$isTransferPeriod = $item->created_at->between('2025-08-12', '2025-08-19 23:59:59');
$isYusufTransfer = $item->user_id == 252;

if ($isTransferPeriod && $isYusufTransfer) {
    // Override role untuk Yusuf selama periode transfer
    $role = 'Kepala Seksi Pemeliharaan';
    $desc = '';
} else {
    // Logic normal berdasarkan role user
    switch ($item->user->roles->first()->name) {
        // ... existing logic
    }
}
```

### 3. approval-material.blade.php

**Lokasi**: `resources/views/livewire/approval-material.blade.php`  
**Section**: Approval flow display table  
**Baris**: ~30-60

**Perubahan**:

-   Override nama dan status approval untuk role "Kepala Seksi" selama periode transfer
-   Jika ada approval dari user 252 dalam periode 12-19 Agustus 2025, tampilkan nama "Yusuf Saut Pangibulan, ST, MPSDA"
-   Status approval diambil dari record transfer, bukan dari user asli

```php
// BYPASS: Untuk periode 12-19 Agustus 2025, jika ada approval dari Yusuf (252)
// untuk role Kepala Seksi, tampilkan nama Yusuf
$transferApproval = \App\Models\Persetujuan::where('approvable_id', $permintaan->id ?? 0)
    ->where('approvable_type', App\Models\DetailPermintaanMaterial::class)
    ->where('user_id', 252)
    ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
    ->first();

if ($transferApproval) {
    $displayName = 'Yusuf Saut Pangibulan, ST, MPSDA';
    $status = $transferApproval->is_approved;
}
```

### 4. ShowPermintaanMaterial.php

**Lokasi**: `app/Livewire/ShowPermintaanMaterial.php`

#### 4.1 Method nodin() - SPB/Nodin

**Baris**: 610-625  
**Perubahan**: Override variabel `$pemel` untuk periode transfer

#### 4.2 Method sppb() - SPPB

**Baris**: 533-548  
**Perubahan**: Override variabel `$kepalaSeksiPemeliharaan` untuk periode transfer

#### 4.3 Method spb() - SPB

**Baris**: 620-635  
**Perubahan**: Override variabel `$pemel` untuk periode transfer

```php
// BYPASS: Untuk periode 12-19 Agustus 2025, jika ada approval dari Yusuf (252),
// maka dia yang akan ditampilkan sebagai pemel (kepala seksi pemeliharaan)
$transferPeriodApproval = $this->permintaan->persetujuan()
    ->where('user_id', 252)
    ->where('is_approved', 1)
    ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
    ->first();

if ($transferPeriodApproval) {
    $pemel = User::find(252); // Override dengan Yusuf
}
```

## Dokumen yang Terpengaruh

### 1. Timeline Approval

-   **File View**: `data-permintaan-material.blade.php`
-   **Fungsi**: Menampilkan history approval dengan nama dan role yang benar
-   **Perbaikan**: Yusuf akan ditampilkan sebagai "Kepala Seksi Pemeliharaan" untuk approval periode 12-19 Agustus 2025

### 2. Detail Permintaan - Approval Flow

-   **File View**: `approval-material.blade.php`
-   **Fungsi**: Menampilkan progress approval dengan nama dan status yang benar
-   **Perbaikan**: Nama Yusuf dan status approval-nya ditampilkan untuk role Kepala Seksi periode 12-19 Agustus 2025

### 3. SPB (Surat Permintaan Barang) / Nodin

-   **Template**: `pdf/nodin.blade.php`, `pdf/spb.blade.php`, `pdf/spb1000.blade.php`
-   **Variabel**: `$pemel`
-   **Perbaikan**: TTD dan nama Yusuf akan muncul sebagai Kepala Seksi Pemeliharaan

### 4. SPPB (Surat Perintah Penyaluran Barang)

-   **Template**: `pdf/sppb.blade.php`
-   **Variabel**: `$kepalaSeksiPemeliharaan`
-   **Perbaikan**: TTD dan nama Yusuf akan muncul di posisi yang sesuai

### 5. Surat Jalan

-   **Template**: `pdf/surat-jalan.blade.php`
-   **Status**: Tidak memerlukan modifikasi karena tidak menggunakan variabel pemel

## Periode Bypass

-   **Tanggal Mulai**: 12 Agustus 2025 00:00:00
-   **Tanggal Selesai**: 19 Agustus 2025 23:59:59
-   **User Transfer**: Yusuf Saut Pangibulan (ID: 252)
-   **Role Override**: Kepala Seksi Pemeliharaan

## Dampak Perubahan

### Positif

1. **Konsistensi Data**: Timeline approval dan dokumen sekarang menampilkan nama yang konsisten
2. **Audit Trail Akurat**: Approval ditampilkan atas nama Yusuf sebagai acting kepala seksi pemeliharaan
3. **Dokumen Legal**: SPB, SPPB, dan dokumen lainnya menampilkan TTD dan nama yang benar
4. **User Experience**: Interface lebih konsisten dan tidak membingungkan

### Teknis

1. **Performance**: Minimal impact, hanya 1-2 query tambahan per request
2. **Maintainability**: Logic terisolasi dengan komentar yang jelas
3. **Scalability**: Pattern ini dapat direplikasi untuk kasus serupa di masa depan

## Testing yang Diperlukan

### 1. Timeline Approval

-   [ ] Buka halaman data permintaan material
-   [ ] Klik timeline approval untuk permintaan periode 12-19 Agustus 2025
-   [ ] Verifikasi nama "Yusuf Saut Pangibulan" muncul sebagai "Kepala Seksi Pemeliharaan"

### 2. Dokumen PDF

-   [ ] Download SPB untuk permintaan yang diapproval Yusuf periode 12-19 Agustus
-   [ ] Verifikasi nama dan TTD Yusuf muncul di posisi Kepala Seksi Pemeliharaan
-   [ ] Download SPPB dan verifikasi hal yang sama
-   [ ] Test dengan dan tanpa tanda tangan digital

### 3. Regression Testing

-   [ ] Verifikasi approval di luar periode 12-19 Agustus tetap normal
-   [ ] Verifikasi approval oleh user lain tidak terpengaruh
-   [ ] Test create permintaan baru dan approval flow normal

## Rollback Plan

Jika diperlukan rollback, hapus blok code berikut dari setiap file:

```php
// BYPASS: Untuk periode 12-19 Agustus 2025...
$isTransferPeriod = ...;
$isYusufTransfer = ...;

if ($isTransferPeriod && $isYusufTransfer) {
    // Override logic
}
```

## Maintenance Future

1. **Kode bypass bisa dihapus setelah periode** (misal setelah 31 Desember 2025)
2. **Pattern ini bisa digunakan untuk kasus cuti/delegation serupa**
3. **Pertimbangkan implementasi sistem delegation yang lebih robust**

## File Locations

```
app/Livewire/DataPermintaanMaterial.php (lines ~575-610)
app/Livewire/ShowPermintaanMaterial.php (lines ~533, ~620, ~635)
resources/views/livewire/data-permintaan-material.blade.php
resources/views/pdf/nodin.blade.php
resources/views/pdf/spb.blade.php
resources/views/pdf/sppb.blade.php
```

## Related Documentation

-   `TRANSFER_APPROVAL_CUTI_DOCUMENTATION.md` - Dokumentasi transfer approval database
-   `app/Console/Commands/TransferApprovalCommand.php` - Command untuk transfer approval
