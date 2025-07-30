# Troubleshooting: Tombol Download Excel Tidak Muncul

## Analisa Masalah

Tombol download Excel pada halaman `/stok` menggunakan 2 komponen Livewire yang berbeda:

### 1. Kondisi Routing

**File**: `resources/views/stok/index.blade.php`

```blade
@if (auth()->user()->unitKerja->hak)
    <livewire:data-stok />
@else
    <livewire:data-stok-material :all="$sudin" />
@endif
```

### 2. Komponen yang Digunakan

-   **Jika `unitKerja->hak = true`**: Menggunakan `DataStok` → `data-stok.blade.php`
-   **Jika `unitKerja->hak = false/null`**: Menggunakan `DataStokMaterial` → `data-stok-material.blade.php`

## Penyelesaian yang Telah Dilakukan

### ✅ 1. Update DataStok.php

-   Method `downloadExcel()` sudah lengkap
-   Button di `data-stok.blade.php` sudah ada

### ✅ 2. Update DataStokMaterial.php

-   Method `downloadExcel()` telah diperbaiki sesuai struktur data gudang
-   Button di `data-stok-material.blade.php` telah ditambahkan

### ✅ 3. Update Model UnitKerja

-   Field `hak` ditambahkan ke `$fillable`

## Syarat Tombol Muncul

### Permission

```blade
@can('pelayanan_xls')
    @if ($barangs->count()) // atau $gudangs->count() untuk material
        <button><!-- Tombol Excel --></button>
    @endif
@endcan
```

### Kondisi yang Harus Dipenuhi:

1. **User memiliki permission `pelayanan_xls`**
2. **Ada data untuk didownload** (`$barangs->count() > 0` atau `$gudangs->count() > 0`)

## Cara Debugging

### 1. Cek Permission User

```php
// Di console atau tinker
User::find($userId)->can('pelayanan_xls')
```

### 2. Cek Field `hak` di Unit Kerja

```php
// Di console atau tinker
auth()->user()->unitKerja->hak
```

### 3. Cek Data Tersedia

```php
// Untuk DataStok
$barangs->count()

// Untuk DataStokMaterial
$gudangs->count()
```

## Database Check

### Cek Permission di Database

```sql
SELECT * FROM permissions WHERE name = 'pelayanan_xls';
SELECT * FROM model_has_permissions WHERE permission_id = [id_permission];
```

### Cek Unit Kerja

```sql
SELECT id, nama, hak FROM unit_kerja WHERE id = [unit_id_user];
```

## Komponen yang Berbeda

### DataStok (untuk unit dengan hak)

-   **File**: `app/Livewire/DataStok.php`
-   **View**: `resources/views/livewire/data-stok.blade.php`
-   **Data**: `$barangs` dengan detail stok per barang
-   **Format Excel**: Detailed dengan spesifikasi merk/tipe/ukuran

### DataStokMaterial (untuk unit tanpa hak)

-   **File**: `app/Livewire/DataStokMaterial.php`
-   **View**: `resources/views/livewire/data-stok-material.blade.php`
-   **Data**: `$gudangs` dengan ringkasan per lokasi
-   **Format Excel**: Summary per gudang dengan total stok

## Solusi Jika Tombol Masih Tidak Muncul

1. **Pastikan user memiliki permission `pelayanan_xls`**
2. **Pastikan ada data stok/gudang**
3. **Clear cache aplikasi**: `php artisan cache:clear`
4. **Restart server**: Stop dan start ulang `php artisan serve`
5. **Hard refresh browser**: Ctrl+F5 atau Ctrl+Shift+R

## Testing

Kedua komponen telah ditest dan tidak ada syntax error:

-   ✅ DataStok.php
-   ✅ DataStokMaterial.php
-   ✅ View files sudah diperbaiki
-   ✅ Permission system terintegrasi
