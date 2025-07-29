# PERBAIKAN MASALAH PERMINTAAN BARANG DENGAN RAB

## MASALAH AWAL

Ketika melakukan permintaan barang menggunakan RAB, kolom pilih barang kosong meskipun:

-   Di RAB sudah ada item barang
-   Di gudang ada stok untuk barang tersebut

## ANALISIS MASALAH

### Penyebab Utama:

1. **Filter Ganda yang Terlalu Ketat**: Method `fillBarangs()` menggunakan filter gabungan yang mengharuskan barang:
    - Ada di RAB (`list_rab` table)
    - Ada transaksi stok di gudang dengan jumlah > 0
2. **Logic Bermasalah**: Barang hanya muncul jika kedua kondisi terpenuhi, padahal seharusnya:
    - Jika menggunakan RAB → tampilkan semua barang dari RAB
    - Validasi stok dilakukan saat input jumlah, bukan saat pilih barang

### File yang Bermasalah:

-   `app/Livewire/ListPermintaanMaterial.php`
-   Method: `fillBarangs()`, `updated()` untuk field `newBarangId` dan `newRabId`

## SOLUSI YANG DITERAPKAN

### 1. Perbaikan Method `fillBarangs()`

**Sebelum:**

```php
// Filter ganda: stok gudang + RAB
$transaksis = TransaksiStok::with(['merkStok.barangStok'])
    ->where(function ($q) use ($gudang_id) {
        // Filter gudang
    })
    ->when($this->withRab && $rabId > 0, function ($query) use ($rabId) {
        // Filter RAB
        $query->whereHas('merkStok.listRab', function ($q) use ($rabId) {
            $q->where('rab_id', $rabId);
        });
    })
    ->get();
```

**Sesudah:**

```php
if ($this->withRab && $rabId > 0) {
    // Jika menggunakan RAB, ambil semua barang dari RAB
    $rabItems = \App\Models\ListRab::with(['merkStok.barangStok'])
        ->where('rab_id', $rabId)
        ->whereHas('merkStok.barangStok', function ($q) {
            $q->where('jenis_id', 1); // hanya material
        })
        ->get();

    $barangIds = $rabItems->pluck('merkStok.barangStok.id')->unique()->filter();
    $this->barangs = \App\Models\BarangStok::whereIn('id', $barangIds)->get();
} else {
    // Jika tidak menggunakan RAB, gunakan logika lama berdasarkan stok gudang
    // ... logika existing untuk non-RAB
}
```

### 2. Perbaikan Method `updated()` untuk field `newBarangId`

**Perubahan:**

-   Jika menggunakan RAB: ambil merk langsung dari RAB
-   Jika tidak menggunakan RAB: gunakan logika lama berdasarkan stok gudang

### 3. Perbaikan Method `updated()` untuk field `newRabId`

**Perubahan:**

-   Konsisten dengan logic `fillBarangs()`
-   Saat RAB dipilih, langsung ambil barang dari RAB

## KEUNTUNGAN SOLUSI INI

### ✅ Masalah Terpecahkan:

1. **Kolom barang tidak kosong lagi** ketika menggunakan RAB
2. **Semua barang dari RAB ditampilkan** meskipun stok gudang = 0
3. **Validasi stok tetap berjalan** saat input jumlah via `StokHelper`

### ✅ Logika yang Lebih Masuk Akal:

1. **Pemisahan concern**: Pilihan barang vs validasi stok
2. **RAB sebagai master**: Jika pakai RAB, yang ditampilkan adalah barang dari RAB
3. **Validasi di tempat yang tepat**: Stok divalidasi saat input jumlah, bukan saat pilih barang

### ✅ Tidak Merusak Fitur Existing:

1. **Non-RAB tetap berfungsi** dengan logika lama
2. **Validasi stok via StokHelper tetap berjalan**
3. **Fitur pengambilan parsial tetap berfungsi**

## TESTING YANG DIPERLUKAN

### Test Case 1: RAB dengan Barang Ada Stok

-   Buat RAB dengan barang A, B, C
-   Pastikan gudang punya stok untuk barang A, B, C
-   **Expected**: Semua barang A, B, C muncul di dropdown

### Test Case 2: RAB dengan Barang Tidak Ada Stok

-   Buat RAB dengan barang A, B, C
-   Pastikan gudang TIDAK punya stok untuk barang A, B, C
-   **Expected**: Semua barang A, B, C tetap muncul di dropdown
-   **Expected**: Validasi error muncul saat input jumlah

### Test Case 3: Non-RAB

-   Tidak pilih RAB
-   **Expected**: Hanya barang dengan stok > 0 yang muncul (logika lama)

### Test Case 4: Validasi Stok

-   Pilih barang dari RAB
-   Input jumlah melebihi stok gudang
-   **Expected**: Error message dari `StokHelper::validateJumlahPermintaan()`

## FILES YANG DIMODIFIKASI

-   `app/Livewire/ListPermintaanMaterial.php`
    -   Method: `fillBarangs()`
    -   Method: `updated()` untuk field `newBarangId`
    -   Method: `updated()` untuk field `newRabId`

## DEPENDENCIES

-   Model `ListRab` dengan relasi ke `MerkStok` dan `BarangStok`
-   `StokHelper` untuk validasi stok (sudah ada dan tidak diubah)
-   Relasi `MerkStok->listRab` (sudah ada dan benar)

---

**Catatan:** Solusi ini memisahkan logic "barang yang bisa dipilih" dari "barang yang bisa diminta". Dengan RAB, semua barang dari RAB bisa dipilih, tapi validasi stok tetap diterapkan saat input jumlah.
