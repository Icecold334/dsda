<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kelurahan berdasarkan struktur administrasi DKI Jakarta yang benar
        // Mapping kecamatan_id:
        // Jakarta Pusat: 1 = Gambir, 2 = Sawah Besar, 3 = Kemayoran, 4 = Senen, 5 = Cempaka Putih, 6 = Menteng, 7 = Tanah Abang, 8 = Johar Baru
        // Jakarta Utara: 9 = Cilincing, 10 = Kelapa Gading, 11 = Koja, 12 = Pademangan, 13 = Penjaringan, 14 = Tanjung Priok
        // Jakarta Timur: 15 = Cakung, 16 = Cipayung, 17 = Ciracas, 18 = Duren Sawit, 19 = Jatinegara, 20 = Kramat Jati, 21 = Makasar, 22 = Matraman, 23 = Pasar Rebo, 24 = Pulo Gadung
        // Jakarta Selatan: 25 = Cilandak, 26 = Jagakarsa, 27 = Kebayoran Baru, 28 = Kebayoran Lama, 29 = Mampang Prapatan, 30 = Pancoran, 31 = Pasar Minggu, 32 = Pesanggrahan, 33 = Setiabudi, 34 = Tebet
        // Jakarta Barat: 35 = Cengkareng, 36 = Grogol Petamburan, 37 = Taman Sari, 38 = Tambora, 39 = Kebon Jeruk, 40 = Kalideres, 41 = Palmerah, 42 = Kembangan
        // Kepulauan Seribu: 43 = Kepulauan Seribu Utara, 44 = Kepulauan Seribu Selatan

        $kelurahans = [
            // Jakarta Pusat - Gambir (kecamatan_id = 1)
            ['kecamatan_id' => 1, 'nama' => 'Cideng', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Duri Pulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Gambir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Kebon Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Sawah Besar (kecamatan_id = 2)
            ['kecamatan_id' => 2, 'nama' => 'Gunung Sahari Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Karang Anyar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Kartini', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Mangga Dua Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Pasar Baru', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Kemayoran (kecamatan_id = 3)
            ['kecamatan_id' => 3, 'nama' => 'Cempaka Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Gunung Sahari Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Harapan Mulya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Kebon Kosong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Kemayoran', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Serdang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Sumur Batu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Utan Panjang', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Senen (kecamatan_id = 4)
            ['kecamatan_id' => 4, 'nama' => 'Bungur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama' => 'Kenari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama' => 'Kramat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama' => 'Kwitang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama' => 'Paseban', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama' => 'Senen', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Cempaka Putih (kecamatan_id = 5)
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 5, 'nama' => 'Rawasari', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Menteng (kecamatan_id = 6)
            ['kecamatan_id' => 6, 'nama' => 'Cikini', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Gondangdia', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Kebon Sirih', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Menteng', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Pegangsaan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Tanah Abang (kecamatan_id = 7)
            ['kecamatan_id' => 7, 'nama' => 'Bendungan Hilir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Gelora', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Kampung Bali', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Karet Tengsin', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Kacang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Melati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Petamburan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Johar Baru (kecamatan_id = 8)
            ['kecamatan_id' => 8, 'nama' => 'Galur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Johar Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Kampung Rawa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Tanah Tinggi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Cilincing (kecamatan_id = 9)
            ['kecamatan_id' => 9, 'nama' => 'Cilincing', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Kalibaru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Marunda', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Rorotan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Semper Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Semper Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Sukapura', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Kelapa Gading (kecamatan_id = 10)
            ['kecamatan_id' => 10, 'nama' => 'Kelapa Gading Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 10, 'nama' => 'Kelapa Gading Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 10, 'nama' => 'Pegangsaan Dua', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Koja (kecamatan_id = 11)
            ['kecamatan_id' => 11, 'nama' => 'Koja', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Lagoa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Rawa Badak Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Rawa Badak Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Tugu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Tugu Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Pademangan (kecamatan_id = 12)
            ['kecamatan_id' => 12, 'nama' => 'Ancol', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Pademangan Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Pademangan Timur', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Penjaringan (kecamatan_id = 13)
            ['kecamatan_id' => 13, 'nama' => 'Kamal Muara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Kapuk Muara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Pejagalan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Penjaringan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Pluit', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Tanjung Priok (kecamatan_id = 14)
            ['kecamatan_id' => 14, 'nama' => 'Kebon Bawang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Papanggo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Sungai Bambu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Sunter Agung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Sunter Jaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Tanjung Priok', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Warakas', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Cakung (kecamatan_id = 15)
            ['kecamatan_id' => 15, 'nama' => 'Cakung Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Cakung Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Jatinegara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Penggilingan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Pulo Gebang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Rawa Terate', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Ujung Menteng', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Cipayung (kecamatan_id = 16)
            ['kecamatan_id' => 16, 'nama' => 'Bambu Apus', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Ceger', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Cilangkap', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Cipayung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Lubang Buaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Munjul', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Pondok Ranggon', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Setu', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Ciracas (kecamatan_id = 17)
            ['kecamatan_id' => 17, 'nama' => 'Cibubur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Ciracas', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Kelapa Dua Wetan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Rambutan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Susukan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Duren Sawit (kecamatan_id = 18)
            ['kecamatan_id' => 18, 'nama' => 'Duren Sawit', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Klender', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Malaka Jaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Malaka Sari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Bambu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Kopi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Jatinegara (kecamatan_id = 19)
            ['kecamatan_id' => 19, 'nama' => 'Bali Mester', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Bidara Cina', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Besar Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Besar Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Cempedak', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Muara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Kampung Melayu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Rawa Bunga', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Kramat Jati (kecamatan_id = 20)
            ['kecamatan_id' => 20, 'nama' => 'Balekambang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Batu Ampar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Cawang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Cililitan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Dukuh', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Kramat Jati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Tengah', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Makasar (kecamatan_id = 21)
            ['kecamatan_id' => 21, 'nama' => 'Cipinang Melayu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Halim Perdana Kusuma', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Kebon Pala', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Makasar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Pinang Ranti', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Matraman (kecamatan_id = 22)
            ['kecamatan_id' => 22, 'nama' => 'Kayu Manis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Kebon Manggis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Pal Meriam', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Pisangan Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Utan Kayu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Utan Kayu Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Pasar Rebo (kecamatan_id = 23)
            ['kecamatan_id' => 23, 'nama' => 'Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Cijantung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Gedong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Kalisari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Pekayon', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Pulo Gadung (kecamatan_id = 24)
            ['kecamatan_id' => 24, 'nama' => 'Cipinang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Jati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Jatinegara Kaum', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Kayu Putih', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Pisangan Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Pulo Gadung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Rawamangun', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Cilandak (kecamatan_id = 25)
            ['kecamatan_id' => 25, 'nama' => 'Cilandak Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Cipete Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Gandaria Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Lebak Bulus', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Pondok Labu', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Jagakarsa (kecamatan_id = 26)
            ['kecamatan_id' => 26, 'nama' => 'Ciganjur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Cipedak', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Jagakarsa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Lenteng Agung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Srengseng Sawah', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Tanjung Barat', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Kebayoran Baru (kecamatan_id = 27)
            ['kecamatan_id' => 27, 'nama' => 'Cipete Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Gandaria Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Gunung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Kramat Pela', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Melawai', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Petogogan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Pulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Rawa Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Selong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Senayan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Kebayoran Lama (kecamatan_id = 28)
            ['kecamatan_id' => 28, 'nama' => 'Cipulir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Grogol Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Grogol Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Kebayoran Lama Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Kebayoran Lama Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Pondok Pinang', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Mampang Prapatan (kecamatan_id = 29)
            ['kecamatan_id' => 29, 'nama' => 'Bangka', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 29, 'nama' => 'Kuningan Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 29, 'nama' => 'Mampang Prapatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 29, 'nama' => 'Pela Mampang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 29, 'nama' => 'Tegal Parang', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Pancoran (kecamatan_id = 30)
            ['kecamatan_id' => 30, 'nama' => 'Cikoko', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 30, 'nama' => 'Duren Tiga', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 30, 'nama' => 'Kalibata', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 30, 'nama' => 'Pancoran', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 30, 'nama' => 'Pengadegan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 30, 'nama' => 'Rawajati', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Pasar Minggu (kecamatan_id = 31)
            ['kecamatan_id' => 31, 'nama' => 'Cilandak Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Jati Padang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Kebagusan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Pasar Minggu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Pejaten Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Pejaten Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Ragunan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Pesanggrahan (kecamatan_id = 32)
            ['kecamatan_id' => 32, 'nama' => 'Bintaro', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Pesanggrahan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Petukangan Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Petukangan Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Ulujami', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Setiabudi (kecamatan_id = 33)
            ['kecamatan_id' => 33, 'nama' => 'Guntur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Karet Kuningan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Karet Semanggi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Karet', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Kuningan Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Menteng Atas', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Pasar Manggis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Setiabudi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Tebet (kecamatan_id = 34)
            ['kecamatan_id' => 34, 'nama' => 'Bukit Duri', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Kebon Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Manggarai Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Manggarai', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Menteng Dalam', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Tebet Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Tebet Timur', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Cengkareng (kecamatan_id = 35)
            ['kecamatan_id' => 35, 'nama' => 'Cengkareng Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Cengkareng Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Duri Kosambi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Kapuk', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Kedaung Kali Angke', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Rawa Buaya', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Grogol Petamburan (kecamatan_id = 36)
            ['kecamatan_id' => 36, 'nama' => 'Grogol', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Jembatan Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Jelambar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Tanjung Duren Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Tanjung Duren Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Tomang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Wijaya Kusuma', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Taman Sari (kecamatan_id = 37)
            ['kecamatan_id' => 37, 'nama' => 'Glodok', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Keagungan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Krukut', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Mangga Besar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Maphar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Pinangsia', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Taman Sari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Tangki', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Tambora (kecamatan_id = 38)
            ['kecamatan_id' => 38, 'nama' => 'Angke', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Duri Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Duri Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Jembatan Besi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Jembatan Lima', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Kali Anyar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Krendang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Pekojan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Roa Malaka', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Tambora', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Tanah Sereal', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kebon Jeruk (kecamatan_id = 39)
            ['kecamatan_id' => 39, 'nama' => 'Duri Kepa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Kebon Jeruk', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Kedoya Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Kedoya Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Kelapa Dua', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Sukabumi Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Sukabumi Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kalideres (kecamatan_id = 40)
            ['kecamatan_id' => 40, 'nama' => 'Kalideres', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Kamal', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Pegadungan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Semanan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Tegal Alur', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Palmerah (kecamatan_id = 41)
            ['kecamatan_id' => 41, 'nama' => 'Jatipulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kemanggisan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kota Bambu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kota Bambu Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Palmerah', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Slipi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kembangan (kecamatan_id = 42)
            ['kecamatan_id' => 42, 'nama' => 'Joglo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Kembangan Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Kembangan Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Meruya Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Meruya Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Srengseng', 'created_at' => now(), 'updated_at' => now()],

            // Kepulauan Seribu - Kepulauan Seribu Utara (kecamatan_id = 43)
            ['kecamatan_id' => 43, 'nama' => 'Pulau Harapan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Panggang', 'created_at' => now(), 'updated_at' => now()],

            // Kepulauan Seribu - Kepulauan Seribu Selatan (kecamatan_id = 44)
            ['kecamatan_id' => 44, 'nama' => 'Pulau Pari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Tidung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Untung Jawa', 'created_at' => now(), 'updated_at' => now()]
        ];

        // Insert data in chunks of 50
        foreach (array_chunk($kelurahans, 50) as $chunk) {
            Kelurahan::insert($chunk);
        }
    }
}