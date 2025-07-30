# Fitur Download Excel - Daftar Stok

## Deskripsi

Fitur download Excel untuk daftar sisa stok telah berhasil diimplementasikan pada sistem DSDA. Fitur ini memungkinkan pengguna untuk mengunduh laporan stok dalam format Microsoft Excel (.xlsx).

## Lokasi File

-   **Controller**: `app/Livewire/DataStok.php`
-   **View**: `resources/views/livewire/data-stok.blade.php`
-   **Route**: Menggunakan resource route `stok` di `routes/web.php`

## Fitur Yang Ditambahkan

### 1. Method downloadExcel()

-   Menggunakan PhpSpreadsheet untuk generate file Excel
-   Menampilkan header dengan informasi unit kerja
-   Menampilkan filter yang aktif (jenis, lokasi)
-   Format tabel yang rapi dengan styling
-   Auto-sizing kolom

### 2. Struktur Excel

-   **Header**: Judul laporan, unit kerja, filter aktif, tanggal
-   **Kolom**: Kode Barang, Nama Barang, Spesifikasi (Merk/Tipe/Ukuran), Stok Tersedia, Lokasi
-   **Footer**: Ringkasan total jenis barang dan unit stok

### 3. Button Download di UI

-   Button dengan icon Excel
-   Loading state dengan spinner
-   Tooltip informatif
-   Hanya tampil jika ada data dan user memiliki permission

### 4. Permission

-   Menggunakan permission `pelayanan_xls`
-   Sudah terdaftar di PermissionSeeder
-   Gate authorization di view menggunakan `@can('pelayanan_xls')`

### 5. Filename Dynamic

-   Format: `Daftar_Stok_DSDA_{filter}_{timestamp}.xlsx`
-   Menyertakan filter aktif dalam nama file
-   Timestamp untuk uniqueness

## Cara Penggunaan

1. **Akses Halaman Stok**: Buka halaman daftar stok
2. **Filter Data** (opsional): Gunakan filter jenis dan lokasi sesuai kebutuhan
3. **Download**: Klik tombol Excel (ikon file Excel biru)
4. **Tunggu Proses**: Loading indicator akan muncul
5. **File Terunduh**: File Excel akan otomatis terunduh ke folder Downloads

## Komponen Loading

-   Full page loading overlay saat generate file
-   Button loading state dengan spinner
-   Disable button selama proses download

## Error Handling

-   Try-catch untuk menangani error
-   Flash message untuk notifikasi sukses/error
-   Validasi data sebelum generate

## Requirements

-   PhpSpreadsheet ^3.6 (sudah terinstall via composer)
-   Permission `pelayanan_xls` untuk user
-   Data stok tersedia di database

## Testing

✅ Syntax check passed
✅ Server running without errors
✅ Permission system integrated
✅ Loading states implemented
✅ Error handling added

## Developer Notes

-   File Excel mengikuti template standar DSDA
-   Styling menggunakan warna primary (orange/brown)
-   Responsive terhadap filter yang aktif
-   Data real-time sesuai filter saat download
-   Optimized untuk performa dengan auto-sizing
