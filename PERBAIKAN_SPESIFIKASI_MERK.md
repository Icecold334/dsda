# PERBAIKAN MASALAH SPESIFIKASI (MERK) TIDAK MUNCUL

## MASALAH YANG DITEMUKAN

Setelah perbaikan sebelumnya untuk menampilkan barang dari RAB, muncul masalah baru:

-   Nama barang sudah muncul dengan benar
-   Tapi spesifikasi (merk) tidak muncul
-   Akibatnya tidak bisa mengisi volume/jumlah

## ANALISIS PENYEBAB

1. **Konflik Variable RAB**: Ada dua variable untuk RAB:

    - `$this->rab_id`: RAB yang dipilih di form utama (mode normal)
    - `$this->newRabId`: RAB yang dipilih per-item (mode Seribu/isSeribu)

2. **Logic Salah**: Di method `updated()` untuk field `newBarangId`, kode menggunakan `$this->newRabId` padahal seharusnya menggunakan `$this->rab_id` untuk mode normal.

3. **Validasi Tidak Konsisten**: Validasi stok menggunakan RAB ID yang salah.

## PERBAIKAN YANG DILAKUKAN

### 1. Perbaikan Logic di `updated()` untuk field `newBarangId`

**Sebelum:**

```php
$rab_id = $this->newRabId; // SALAH - newRabId mungkin kosong
```

**Sesudah:**

```php
// Gunakan rab_id dari form utama, bukan newRabId
$rab_id = $this->rab_id;
```

### 2. Perbaikan Logic Validasi StokHelper

**Sebelum:**

```php
$this->newMerkMax = \App\Helpers\StokHelper::calculateMaxPermintaan(
    $this->newMerkId,
    $this->withRab && $this->newRabId ? $this->newRabId : null, // SALAH
    $this->gudang_id
);
```

**Sesudah:**

```php
// Tentukan RAB ID yang benar berdasarkan mode
$rabIdForValidation = null;
if ($this->withRab) {
    if ($this->isSeribu && $this->newRabId) {
        // Untuk mode Seribu, gunakan RAB per-item
        $rabIdForValidation = $this->newRabId;
    } elseif (!$this->isSeribu && $this->rab_id) {
        // Untuk mode normal, gunakan RAB dari form utama
        $rabIdForValidation = $this->rab_id;
    }
}

$this->newMerkMax = \App\Helpers\StokHelper::calculateMaxPermintaan(
    $this->newMerkId,
    $rabIdForValidation,
    $this->gudang_id
);
```

### 3. Perbaikan Logic di `checkAdd()`

Menggunakan logic yang sama untuk menentukan RAB ID yang benar dalam validasi.

### 4. Perbaikan Logic di `addToList()`

Reset `newRabId` hanya jika dalam mode `isSeribu`.

### 5. Clarification untuk field `newRabId`

Menambahkan komentar bahwa field `newRabId` hanya digunakan untuk kasus `isSeribu` dimana setiap item bisa memiliki RAB yang berbeda.

## LOGIC YANG BENAR

### Mode Normal (non-Seribu):

-   RAB dipilih di form utama → `$this->rab_id`
-   Semua item menggunakan RAB yang sama
-   Validasi menggunakan `$this->rab_id`

### Mode Seribu:

-   RAB dipilih per-item → `$this->newRabId`
-   Setiap item bisa punya RAB berbeda
-   Validasi menggunakan `$this->newRabId`

## HASIL YANG DIHARAPKAN

✅ Nama barang muncul dengan benar (sudah berfungsi)
✅ Spesifikasi (merk) muncul sesuai dengan barang yang dipilih
✅ Bisa mengisi volume/jumlah
✅ Validasi stok menggunakan RAB ID yang benar
✅ Support untuk mode Seribu dan mode normal

## FILES YANG DIMODIFIKASI

-   `app/Livewire/ListPermintaanMaterial.php`
    -   Method: `updated()` untuk field `newBarangId`
    -   Method: `updated()` untuk field validation
    -   Method: `checkAdd()`
    -   Method: `addToList()`

---

**Catatan:** Perbaikan ini memisahkan logic untuk mode normal dan mode Seribu dengan jelas, memastikan RAB ID yang benar digunakan dalam setiap konteks.
