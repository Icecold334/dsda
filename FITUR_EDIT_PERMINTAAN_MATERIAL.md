# Fitur Edit Permintaan Material

## Deskripsi

Fitur ini memungkinkan pengguna untuk mengedit permintaan material yang sudah dibuat, dengan pembatasan tertentu berdasarkan status permintaan.

## Files yang Dibuat/Dimodifikasi

### 1. View Files

-   `resources/views/permintaan/edit-material.blade.php` - View utama untuk edit permintaan material
-   `resources/views/livewire/edit-form-permintaan-material.blade.php` - View komponen Livewire untuk form edit

### 2. Livewire Component

-   `app/Livewire/EditFormPermintaanMaterial.php` - Komponen Livewire yang menangani logic edit permintaan material

### 3. Controller Method

-   `app/Http/Controllers/PermintaanStokController.php` - Method `editMaterial()` ditambahkan

### 4. Routes

-   Route baru: `GET /permintaan/material/edit/{id}` dengan name `editPermintaanMaterial`

### 5. Notification

-   `app/Notifications/PermintaanMaterialNotification.php` - Notifikasi untuk approval

### 6. View Updates

-   `resources/views/livewire/data-permintaan-material.blade.php` - Tombol edit ditambahkan dengan link yang benar

## Fitur Utama

### 1. Pembatasan Edit Berdasarkan Status

-   **Draft (status = 4)**: Dapat diedit sepenuhnya
-   **Status lainnya**: Hanya bisa dilihat, tidak bisa diedit
-   Owner permintaan: Hanya pemilik yang bisa edit
-   Admin: Dapat mengakses semua permintaan

### 2. Form Edit Mencakup

-   Data umum permintaan (tanggal, keterangan, gudang, dll.)
-   Pilihan menggunakan RAB atau tidak
-   Lokasi kegiatan (kecamatan/kelurahan)
-   Daftar item permintaan material

### 3. Validasi

-   Tanggal permintaan wajib diisi
-   Gudang wajib dipilih
-   Minimal harus ada satu item permintaan
-   Validasi jumlah item harus > 0

### 4. Actions

-   **Simpan Draft**: Menyimpan perubahan tanpa mengirim untuk approval
-   **Submit Permintaan**: Mengirim permintaan untuk proses approval

### 5. Status Badge

-   Menampilkan status permintaan dengan warna yang berbeda
-   Alert khusus jika permintaan tidak bisa diedit

## Cara Menggunakan

### 1. Akses Menu Edit

-   Dari halaman daftar permintaan material, klik tombol edit (ikon pensil kuning) pada permintaan dengan status "Draft"
-   Atau akses langsung via URL: `/permintaan/material/edit/{id}`

### 2. Edit Data Umum

-   Ubah tanggal permintaan, keterangan, gudang sesuai kebutuhan
-   Pilih apakah menggunakan RAB atau tidak
-   Isi lokasi kegiatan jika diperlukan

### 3. Kelola Item Permintaan

-   Tambah item baru menggunakan form di bagian atas tabel
-   Hapus item yang tidak diperlukan dengan tombol "Hapus"
-   Ubah jumlah item sesuai kebutuhan

### 4. Simpan Perubahan

-   **Simpan Draft**: Untuk menyimpan perubahan tanpa mengirim approval
-   **Submit Permintaan**: Untuk mengirim ke proses approval

## Authorization & Security

### 1. Permission Check

-   User hanya bisa edit permintaan milik sendiri
-   Admin (superadmin) bisa mengakses semua permintaan
-   Status draft diperlukan untuk editing

### 2. Validation Rules

-   Semua input divalidasi sesuai aturan bisnis
-   CSRF protection melalui Livewire
-   SQL injection prevention melalui Eloquent ORM

## User Experience

### 1. Visual Indicators

-   Status badge menunjukkan status saat ini
-   Alert warning untuk permintaan yang tidak bisa diedit
-   Loading states untuk operasi async

### 2. Responsive Design

-   Form responsif untuk berbagai ukuran layar
-   Grid layout yang adaptive

### 3. Real-time Updates

-   Form update real-time menggunakan Livewire
-   Validasi instant feedback

## Technical Notes

### 1. Data Flow

1. User mengakses halaman edit
2. Component load data existing permintaan
3. User melakukan perubahan
4. Validasi client-side dan server-side
5. Update database jika valid
6. Redirect atau feedback ke user

### 2. Dependencies

-   Laravel Livewire untuk reactive components
-   TailwindCSS untuk styling
-   FontAwesome untuk icons

### 3. Performance Considerations

-   Eager loading untuk relasi yang diperlukan
-   Pagination untuk list items jika diperlukan
-   Efficient queries untuk dropdown options

## Error Handling

-   Try-catch blocks untuk database operations
-   User-friendly error messages
-   Session flash messages untuk feedback
-   Validation error display inline dengan form fields

## Testing

Untuk testing fitur ini:

1. Login sebagai user biasa
2. Buat permintaan material dan simpan sebagai draft
3. Akses menu edit dari daftar permintaan
4. Test berbagai skenario edit dan validasi
5. Test permission dengan user yang berbeda
