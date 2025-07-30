<?php
// File untuk debugging masalah permintaan barang dengan RAB

// ANALISIS MASALAH:
// Ketika menggunakan RAB, kolom pilih barang kosong meskipun ada stok di gudang
// dan ada item barang di RAB

// PENYEBAB POTENSIAL:
/*
1. Logika filter di fillBarangs() method baris 104-107:
   ->when($this->withRab && $rabId > 0, function ($query) use ($rabId) {
       $query->whereHas('merkStok.listRab', function ($q) use ($rabId) {
           $q->where('rab_id', $rabId);
       });
   })

   Filter ini mencari barang yang:
   - Ada di gudang (dari transaksi_stok)
   - Ada di RAB (dari list_rab)
   
   Jika ada barang di RAB tapi tidak ada transaksi_stok untuk barang tersebut,
   maka barang tidak akan muncul.

2. Logic di line 113-123 menghitung stok berdasarkan transaksi_stok:
   - Hanya barang yang memiliki stok > 0 dari transaksi yang akan muncul
   - Jika barang ada di RAB tapi belum ada pemasukan/stok di gudang,
     maka tidak akan muncul

3. Kemungkinan masalah data:
   - RAB memiliki item barang tertentu
   - Tapi belum ada transaksi pemasukan untuk barang tersebut di gudang
   - Atau transaksi sudah ada tapi tipe transaksi tidak sesuai filter

SOLUSI YANG DIPERLUKAN:
1. Modifikasi fillBarangs() untuk menampilkan semua barang dari RAB
   meskipun stok di gudang = 0
2. Validasi stok dilakukan di level input jumlah, bukan di level pilihan barang
3. Memisahkan logika "barang tersedia untuk dipilih" vs "barang bisa diminta"
*/

// INVESTIGASI LEBIH LANJUT DIPERLUKAN:
/*
1. Cek data di table list_rab - apakah ada item untuk RAB yang dipilih?
2. Cek data di table transaksi_stok - apakah ada stok untuk merk yang ada di RAB?
3. Cek relasi MerkStok->listRab apakah benar?
4. Cek apakah $this->withRab dan $rabId sudah terisi dengan benar?
*/

// REKOMENDASI PERBAIKAN:
/*
Ubah logic di fillBarangs() menjadi:
1. Jika withRab = true dan ada rab_id:
   - Tampilkan semua barang yang ada di RAB (dari list_rab)
   - Tidak peduli apakah ada stok di gudang atau tidak
   
2. Validasi stok dilakukan di:
   - updated() method ketika pilih merk (untuk set max)
   - checkAdd() method ketika input jumlah
   
3. Ini akan memungkinkan user melihat semua barang dari RAB
   tapi tetap mendapat validasi jika stok tidak mencukupi
*/
