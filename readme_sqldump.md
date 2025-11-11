# 🧭 Panduan Migrasi Database SQLite → MySQL (Laravel Project)

> Catatan lengkap hasil debugging dan solusi untuk semua error yang ditemukan saat migrasi dari SQLite ke MySQL pada project Laravel.

---

## 🗂️ Ringkasan Masalah

Berikut daftar **semua error nyata yang ditemukan selama proses migrasi** beserta **solusinya** (dari awal sampai terakhir).

| No  | Pesan Error / Masalah                                                    | Penyebab                                                                      | Solusi                                                                                                                             |
| --- | ------------------------------------------------------------------------ | ----------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| 1   | `Parse error: near "database": syntax error`                             | Menjalankan perintah shell (`database.sqlite .schema`) di dalam prompt SQLite | Jalankan perintah `.schema` langsung di **terminal**, bukan di dalam SQLite: `sqlite3 database.sqlite .schema > sqlite_schema.sql` |
| 2   | `PRAGMA foreign_keys=OFF` error                                          | MySQL tidak mengenal perintah `PRAGMA`                                        | Hapus semua baris `PRAGMA`, `BEGIN TRANSACTION;`, dan `COMMIT;` dari file dump                                                     |
| 3   | `You have an error in your SQL syntax near '"migrations"'`               | SQLite menggunakan tanda kutip ganda (`"`) untuk nama tabel                   | Ganti semua tanda `"` menjadi backtick `` ` ``                                                                                     |
| 4   | `autoincrement not null`                                                 | MySQL butuh `AUTO_INCREMENT` dengan underscore dan huruf besar                | Ganti `autoincrement` → `AUTO_INCREMENT`                                                                                           |
| 5   | `varchar not null` tanpa ukuran                                          | MySQL butuh panjang untuk VARCHAR                                             | Ubah `varchar` → `VARCHAR(255)`                                                                                                    |
| 6   | `Duplicate entry '1' for key 'migrations.PRIMARY'`                       | Data `id` duplikat di tabel `migrations` (sudah ada baris dengan id=1)        | Kosongkan tabel: `TRUNCATE TABLE migrations;` atau hapus baris `INSERT INTO migrations` dari dump                                  |
| 7   | `Cannot add or update a child row: a foreign key constraint fails (...)` | Foreign key aktif saat tabel parent (`unit_kerja`) belum diimport             | Tambahkan di awal dump: `SET FOREIGN_KEY_CHECKS=0;` dan di akhir `SET FOREIGN_KEY_CHECKS=1;`                                       |
| 8   | `You have an error in your SQL syntax near 'option VALUES...'`           | `option` adalah keyword MySQL                                                 | Tambahkan backtick `` `option` `` atau ubah nama tabel ke `options`                                                                |
| 9   | `Data too long for column 'nama' at row 1`                               | Data teks melebihi batas panjang kolom (`VARCHAR(255)`)                       | Ubah kolom teks (`nama`, `tipe`, `ukuran`) menjadi `TEXT` atau `LONGTEXT`                                                          |
| 10  | Masih `Data too long` meski sudah `TEXT`                                 | Data deskripsi sangat panjang (>64 KB)                                        | Gunakan `LONGTEXT` (bisa simpan sampai 4 GB teks)                                                                                  |
| 11  | Error di tabel `merk_stok`                                               | Kolom `nama`, `tipe`, `ukuran` terlalu kecil untuk deskripsi panjang          | Ubah: `ALTER TABLE merk_stok MODIFY nama LONGTEXT, MODIFY tipe LONGTEXT, MODIFY ukuran LONGTEXT;`                                  |

---

## 🧩 Penyesuaian Umum SQLite → MySQL

| Sintaks SQLite                          | Sintaks MySQL yang Benar         |
| --------------------------------------- | -------------------------------- |
| `"tabel"`                               | `` `tabel` ``                    |
| `integer primary key autoincrement`     | `INT AUTO_INCREMENT PRIMARY KEY` |
| `text`                                  | `TEXT` atau `LONGTEXT`           |
| `real`                                  | `DOUBLE`                         |
| `boolean`                               | `TINYINT(1)`                     |
| `blob`                                  | `LONGBLOB`                       |
| `varchar` tanpa ukuran                  | `VARCHAR(255)`                   |
| `PRAGMA`, `BEGIN TRANSACTION`, `COMMIT` | ❌ Hapus                         |
| `without rowid`                         | ❌ Hapus                         |

---

## ⚙️ Struktur Akhir Contoh (Sudah Disesuaikan)

```sql
CREATE TABLE IF NOT EXISTS `merk_stok` (
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `kode` VARCHAR(255),
  `barang_id` INT NOT NULL,
  `nama` LONGTEXT,
  `tipe` LONGTEXT,
  `ukuran` LONGTEXT,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`barang_id`) REFERENCES `barang_stok`(`id`)
);


Tambahkan di awal file dump:

SET FOREIGN_KEY_CHECKS=0;


Dan di akhir file dump:

SET FOREIGN_KEY_CHECKS=1;


```
