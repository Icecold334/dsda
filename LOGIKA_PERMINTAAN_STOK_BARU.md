# Logika Permintaan Stok yang Diperbaharui

## Overview

Dokumen ini menjelaskan perubahan logika permintaan stok yang telah diimplementasikan berdasarkan requirement:

> **Requirement:** Jika pada RAB misal ada stok 3000 barang, dan di gudang ada stok 5000 barang, maka limit permintaan barang sesuai limit pada RAB (3000), namun jika di RAB ada stok 2000 tapi di gudang ada 1000, maka limit permintaan adalah 1000 (stok gudang). Berlaku juga untuk pengambilan secara parsial/bertahap.

## Perubahan Utama

### 1. **StokHelper.php** - Helper Class yang Diperbaharui

#### Method `calculateMaxPermintaan()`

**Logika Baru:**

```php
// 1. Ambil stok tersedia di gudang
$stokGudang = self::getStokTersedia($merkId, $gudangId);

// 2. Jika tidak ada RAB, return stok gudang
if (!$rabId) {
    return max($stokGudang, 0);
}

// 3. Hitung limit dari RAB dan yang sudah digunakan
$limitRab = self::getLimitRab($merkId, $rabId);
$sudahDigunakan = self::getJumlahSudahDigunakan($merkId, $rabId);
$sisaLimitRab = max($limitRab - $sudahDigunakan, 0);

// 4. Return yang terkecil antara sisa limit RAB dan stok gudang
return min($sisaLimitRab, max($stokGudang, 0));
```

**Contoh Kasus:**

-   **Kasus 1:** RAB: 3000, Gudang: 5000 → Limit = 3000 ✅
-   **Kasus 2:** RAB: 2000, Gudang: 1000 → Limit = 1000 ✅
-   **Kasus 3:** RAB: 1500, Sudah digunakan: 500, Gudang: 2000 → Limit = 1000 (1500-500) ✅

#### Method `getJumlahSudahDigunakan()`

**Diperbaharui untuk menghitung:**

-   Permintaan stok yang sudah disetujui (dari `stok_disetujui`)
-   Permintaan material yang sudah disetujui (dari `stok_disetujui`)
-   Permintaan yang sedang dalam proses (transaksi tipe 'Pengajuan')

#### Method `validateJumlahPermintaan()`

**Fitur Baru:**

-   Pesan error yang lebih informatif
-   Indikator apakah pengambilan parsial dimungkinkan
-   Informasi detail tentang limiting factor (RAB vs stok gudang)

### 2. **ListPermintaanMaterial.php** - Livewire Component

#### Perubahan pada `updated()` method:

```php
// Menggunakan StokHelper untuk menghitung maksimal yang bisa diminta
$this->newMerkMax = \App\Helpers\StokHelper::calculateMaxPermintaan(
    $this->newMerkId,
    $this->withRab && $this->newRabId ? $this->newRabId : null,
    $this->gudang_id
);
```

#### Perubahan pada `checkAdd()` method:

```php
// Validasi menggunakan StokHelper
$validation = \App\Helpers\StokHelper::validateJumlahPermintaan(
    $this->newMerkId,
    $this->newJumlah,
    $this->withRab && $this->newRabId ? $this->newRabId : null,
    $this->gudang_id
);

$this->ruleAdd = $validation['valid'];

// Set error message jika tidak valid
if (!$validation['valid']) {
    $this->addError('newJumlah', $validation['error_message']);
}
```

### 3. **ListPermintaanForm.php** - Livewire Component

#### Perubahan pada `validateJumlahPermintaan()` method:

```php
// Menggunakan error message yang lebih informatif dari StokHelper
if (!$validation['valid']) {
    $this->addError('newJumlah', $validation['error_message']);
}
```

### 4. **View Template** - UI Improvements

#### File: `list-permintaan-material.blade.php`

**Perubahan pada placeholder input:**

```blade
placeholder="{{ !$newMerkId ? 'Jumlah' : 'Maksimal: '.$newMerkMax.' (berdasarkan '.($withRab && $newRabId ? 'RAB & stok gudang' : 'stok gudang').')' }}"
```

## Fitur Pengambilan Parsial/Bertahap

### Cara Kerja:

1. **Tracking Penggunaan:** Setiap permintaan dicatat dalam `transaksi_stok` dengan tipe 'Pengajuan'
2. **Perhitungan Sisa:** Method `getJumlahSudahDigunakan()` menghitung total yang sudah digunakan
3. **Limit Dinamis:** Limit permintaan selalu dikurangi dengan yang sudah digunakan

### Contoh Skenario Pengambilan Parsial:

1. **RAB:** 1000 unit, **Gudang:** 800 unit
2. **Permintaan 1:** 300 unit → **Sisa limit:** 500 unit (min(700, 500))
3. **Permintaan 2:** 200 unit → **Sisa limit:** 300 unit (min(500, 300))
4. **Permintaan 3:** 300 unit → **Sisa limit:** 200 unit (min(200, 200))

## Methods Helper Tambahan

### `getSisaKuotaPermintaan()`

Menghitung sisa kuota yang bisa diminta untuk pengambilan parsial.

### `catatPenggunaanStok()`

Mencatat penggunaan stok untuk tracking pengambilan parsial.

### `getDetailInfo()`

Memberikan informasi detail untuk debugging:

-   Stok tersedia
-   Limit RAB
-   Sudah digunakan
-   Sisa limit RAB
-   Max permintaan
-   Limiting factor

## Validasi dan Error Handling

### Pesan Error yang Informatif:

-   **Stok gudang tidak mencukupi:** "Stok gudang tidak mencukupi. Tersedia: X, diminta: Y"
-   **Melebihi sisa limit RAB:** "Melebihi sisa limit RAB. Sisa limit: X, diminta: Y"
-   **Melebihi stok gudang:** "Melebihi stok gudang. Tersedia: X, diminta: Y"

### Return Values yang Diperluas:

```php
[
    'valid' => boolean,
    'max_allowed' => integer,
    'stok_gudang' => integer,
    'limit_rab' => integer|null,
    'sudah_digunakan' => integer,
    'sisa_limit_rab' => integer|null,
    'error_message' => string,
    'can_partial' => boolean,
]
```

## Testing Scenarios

Untuk memastikan logika bekerja dengan benar, test skenario berikut:

1. **Tanpa RAB:** Limit = stok gudang
2. **RAB > Stok Gudang:** Limit = stok gudang
3. **RAB < Stok Gudang:** Limit = RAB
4. **Pengambilan Parsial:** Limit berkurang setiap pengambilan
5. **RAB Habis:** Limit = 0 meskipun ada stok gudang
6. **Stok Gudang Habis:** Limit = 0 meskipun ada sisa RAB

## Backward Compatibility

Semua perubahan dibuat dengan mempertahankan backward compatibility:

-   Method signatures tidak berubah
-   Return formats diperluas (tidak menghilangkan yang lama)
-   Database schema tidak berubah
-   Existing functionality tetap bekerja

## Files yang Dimodifikasi

1. `app/Helpers/StokHelper.php` - Logic utama
2. `app/Livewire/ListPermintaanMaterial.php` - Implementasi pada form material
3. `app/Livewire/ListPermintaanForm.php` - Implementasi pada form stok
4. `resources/views/livewire/list-permintaan-material.blade.php` - UI improvements

---

**Author:** GitHub Copilot  
**Date:** 29 July 2025  
**Version:** 1.0
