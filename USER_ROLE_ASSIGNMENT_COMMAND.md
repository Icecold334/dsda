# User Role Assignment Command

## Overview

Command `user:assign-roles` digunakan untuk assign role kepada user yang sudah ada berdasarkan mapping dari `AkunSudinSeeder`. Command ini sangat berguna setelah melakukan migrasi ulang atau seeding ulang pada tabel permission yang menyebabkan user kehilangan role mereka.

## Usage

### Basic Command

```bash
php artisan user:assign-roles
```

### Options

-   `--force` : Force reassign roles bahkan jika user sudah memiliki role
-   `--unit=<wilayah>` : Assign roles hanya untuk unit/wilayah tertentu

### Examples

#### Assign roles untuk semua user

```bash
php artisan user:assign-roles
```

#### Assign roles untuk semua user dengan force (menimpa role yang sudah ada)

```bash
php artisan user:assign-roles --force
```

#### Assign roles hanya untuk unit Jakarta Pusat

```bash
php artisan user:assign-roles --unit=pusat
```

#### Assign roles untuk unit tertentu dengan force

```bash
php artisan user:assign-roles --unit=utara --force
```

## Available Units

-   `pusat` - Jakarta Pusat
-   `utara` - Jakarta Utara
-   `selatan` - Jakarta Selatan
-   `barat` - Jakarta Barat
-   `timur` - Jakarta Timur

## Role Mapping

Command ini menggunakan mapping role yang sama seperti di `AkunSudinSeeder`:

### Email Pattern to Role Mapping

-   `kasudin.*` → `Kepala Suku Dinas`
-   `kasie_perencanaan.*` → `Kepala Seksi`
-   `perencanaan.*` → `Perencanaan`
-   `kasubagtu.*` → `Kepala Subbagian Tata Usaha`
-   `pb.*` → `Pengurus Barang`
-   `pgm.*` → `Pengurus Barang`
-   `admin.*` → `Pengurus Barang`
-   `kasipemel.*` → `Kepala Seksi`
-   `p3k.*` → `P3K`
-   `kasatpel.*` → `Kepala Satuan Pelaksana`
-   `kasie_pembangunan.*` → `Kepala Seksi`
-   `kasie_pompa.*` → `Kepala Seksi`

### Fallback Role Assignment

Jika email pattern tidak cocok, command akan mencoba identifikasi berdasarkan:

1. Nama unit kerja user
2. Default fallback ke `Pengurus Barang`

## Output

Command akan menampilkan:

-   Progress untuk setiap user yang diproses
-   Role yang berhasil di-identify dan assign
-   Summary total user yang diproses dan role yang di-assign
-   Error messages jika ada masalah

## Troubleshooting

### Tidak ada role yang di-assign

-   Pastikan user memiliki email yang valid
-   Periksa apakah unit kerja user sudah terdaftar
-   Gunakan option `--force` jika user sudah memiliki role

### Error pada specific user

-   Periksa data user di database
-   Pastikan role yang dimapping sudah tersedia di tabel roles
-   Periksa permission user untuk menjalankan command

## Related Files

-   `app/Console/Commands/AssignUserRoles.php` - Command file
-   `database/seeders/AkunSudinSeeder.php` - Source data mapping
-   `database/seeders/PermissionSeeder.php` - Role definitions

## Notes

-   Command ini aman untuk dijalankan berulang kali
-   Gunakan `--force` dengan hati-hati karena akan menimpa role existing
-   Backup database sebelum menjalankan dengan `--force` pada environment production
