# Dokumentasi Migrasi Database: SQLite ke MySQL (Proyek Laravel)

Dokumen ini menjelaskan proses lengkap migrasi database dari SQLite ke MySQL untuk aplikasi ini. Proses ini mencakup konversi skema (struktur tabel), migrasi data, dan perbaikan kode aplikasi (Laravel) agar kompatibel dengan aturan MySQL yang lebih ketat.

## Ringkasan Alur Kerja

Proses migrasi dibagi menjadi tiga fase utama:
1.  **Persiapan Skema:** Mengekspor struktur (`CREATE TABLE`) dari SQLite dan "membersihkannya" agar kompatibel dengan sintaks MySQL.
2.  **Migrasi Data:** Menggunakan skrip Python untuk memindahkan data baris-per-baris dari file `.sqlite` ke tabel-tabel kosong di MySQL.
3.  **Perbaikan Kode:** Memperbaiki *query* di kode Laravel yang error karena aturan MySQL lebih ketat daripada SQLite.



---

## Fase 1 & 2: Skrip Python dan Proses Migrasi

Kita menggunakan dua skrip Python kustom.

### 1. Script Konversi Skema (`konversi.py`)

* **Tujuan:** Membaca file dump skema `.sql` dari SQLite dan mengubahnya agar kompatibel dengan MySQL.
* **Fitur:**
    * Menghapus semua perintah `INSERT INTO` (hanya butuh struktur).
    * Mengganti sintaks `INTEGER PRIMARY KEY AUTOINCREMENT` menjadi `INT PRIMARY KEY AUTO_INCREMENT`.
    * Menghapus sintaks `CHECK` yang tidak kompatibel.
    * Menghapus perintah transaksi SQLite (`BEGIN`, `COMMIT`, `PRAGMA`).

### 2. Script Migrasi Data (`migrasi.py`)

* **Tujuan:** Memindahkan data baris-per-baris dari `.sqlite` ke MySQL.
* **Fitur:**
    * **Batching:** Mengirim data dalam "cicilan" (1000 baris per paket) untuk menghindari error `max_allowed_packet`.
    * **Foreign Key Handling:** Mematikan cek `FOREIGN_KEY` di awal dan menyalakannya lagi di akhir, untuk menghindari error urutan tabel.
    * **Reserved Keywords:** Membungkus nama tabel dengan `""` (SQLite) dan `` `` (MySQL) agar aman dari error kata kunci (seperti `order`).

---

### 3. Langkah-demi-Langkah Migrasi

1.  **Ekspor Skema SQLite:**
    ```bash
    sqlite3 database_anda.sqlite .schema > dump_skema_sqlite.sql
    ```

2.  **Konversi Skema:**
    * Pastikan `INPUT_FILE` di `konversi.py` sudah benar.
    * Jalankan: `python konversi.py`
    * Hasilnya adalah `skema_BERSIH_FINAL.sql`.

3.  **Review Skema Bersih (Manual):**
    * Buka `skema_BERSIH_FINAL.sql`.
    * Perbaiki sintaks `CHECK` yang dihapus oleh skrip. Ganti kolom `VARCHAR` yang seharusnya `ENUM` secara manual.
    * **Contoh:** Ganti `tipe VARCHAR(255)` menjadi `tipe ENUM('Pemasukan', 'Pengeluaran', ...)`

4.  **Siapkan Database MySQL (Kosongkan):**
    * Masuk ke `mysql -u root -p`
    * Jalankan:
        ```sql
        DROP DATABASE IF EXISTS nama_db_mysql;
        CREATE DATABASE nama_db_mysql;
        ```

5.  **Impor Skema Bersih ke MySQL:**
    * Jalankan dari terminal (CMD/PowerShell):
        ```bash
        mysql -u root -p nama_db_mysql < skema_BERSIH_FINAL.sql
        ```
    * Ulangi langkah 3-5 jika masih ada error sintaks skema.

6.  **Jalankan Migrasi Data:**
    * Pastikan konfigurasi (nama DB, user, pass) di `migrasi.py` sudah benar.
    * Jalankan: `python migrasi.py`
    * Tunggu hingga semua tabel selesai diproses.

---

## Fase 3: Perbaikan Kode Aplikasi (Pasca-Migrasi)

Setelah data pindah, aplikasi Laravel perlu penyesuaian karena MySQL lebih "ketat" daripada SQLite.

### Masalah 1: `Subquery returns more than 1 row`
* **Error:** `SQLSTATE[21000]: ... 1242 Subquery returns more than 1 row`
* **Penyebab:** Subquery di `ORDER BY` mengembalikan banyak baris. SQLite "menebak" 1 baris, tapi MySQL error.
* **Kode Error (Contoh):**
    ```php
    ->orderByDesc(
        DB::raw('(SELECT created_at FROM permintaan_material WHERE ...)')
    )
    ```
* **Solusi:** Gunakan `MAX()` atau `LIMIT 1` di dalam subquery.
    ```php
    // Solusi 1: Pakai MAX()
    ->orderByDesc(
        DB::raw('(SELECT MAX(created_at) FROM permintaan_material WHERE ...)')
    )
    
    // Solusi 2: Pakai Subquery Eloquent
    ->orderByDesc(
        PermintaanMaterial::select('created_at')
            ->whereColumn('permintaan_material.detail_permintaan_id', 'detail_permintaan_material.id')
            ->latest()
            ->limit(1)
    )
    ```

### Masalah 2: `FUNCTION ... strftime does not exist`
* **Error:** `SQLSTATE[42000]: ... FUNCTION dsda3.strftime does not exist`
* **Penyebab:** `strftime()` adalah fungsi tanggal SQLite. MySQL menggunakan `DATE_FORMAT()` atau `YEAR()`.
* **Kode Error (Contoh):**
    ```php
    ->selectRaw("distinct strftime('%Y', created_at) as tahun")
    ```
* **Solusi:** Ganti `strftime` dengan fungsi MySQL yang setara.
    ```php
    // Ganti strftime('%Y', ...) menjadi YEAR(...)
    ->selectRaw("distinct YEAR(created_at) as tahun")

    // Ganti strftime('%Y-%m', ...) menjadi DATE_FORMAT(..., '%Y-%m')
    ->selectRaw("distinct DATE_FORMAT(created_at, '%Y-%m') as tahun_bulan")
    ```

### Masalah 3: `date(): Argument #2 ($timestamp) must be of type ?int, string given`
* **Error:** Error PHP `date(): Argument #2 ... must be of type ?int, string given`
* **Penyebab:** MySQL mengembalikan tanggal sebagai **string** (teks, misal: "2025-10-20"). Fungsi `date()` bawaan PHP membutuhkan **integer** (angka timestamp).
* **Kode Error (Contoh):**
    ```php
    $tanggal_format = date('Y-m-d', $variabel_tanggal_dari_db);
    ```
* **Solusi:**
    1.  **Cepat (di Blade/Controller):** Pakai `strtotime()` atau `Carbon::parse()`.
        ```php
        $tanggal_format = date('Y-m-d', strtotime($variabel_tanggal_dari_db));
        // atau
        $tanggal_format = \Carbon\Carbon::parse($variabel_tanggal_dari_db)->format('Y-m-d');
        ```
    2.  **Terbaik (Best Practice):** Tambahkan *casting* di Model Eloquent Anda. Ini akan otomatis mengubah semua string tanggal dari DB menjadi objek Carbon.
        ```php
        // Di dalam file Model Anda (misal: App/Models/Transaction.php)
        protected $casts = [
            'tanggal_kontrak' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

        // Setelah itu, di kode Anda bisa langsung format:
        $tanggal_format = $transaction->tanggal_kontrak->format('Y-m-d');
        ```