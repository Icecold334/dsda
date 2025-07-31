<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    /**
     *            ['kecamatan_id' => 28, 'nama' => 'Tanjung Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Bukit Duri', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Kebon Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Menteng Dalam', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Manggarai', 'created_at' => now(), 'updated_at' => now()], the database seeds.
     */
    public function run(): void
    {
        // Data kelurahan berdasarkan Excel yang diberikan
        // Mapping kecamatan_id:
        // 1 = Gambir, 2 = Tanah Abang, 3 = Menteng, 4 = Senen, 5 = Cempaka Putih
        // 6 = Johar Baru, 7 = Kemayoran, 8 = Sawah Besar
        // 9 = Penjaringan, 10 = Pademangan, 11 = Tanjung Priok, 12 = Koja, 13 = Cilincing, 14 = Kelapa Gading
        // 15 = Cengkareng, 16 = Grogol Petamburan, 17 = Taman Sari, 18 = Tambora, 19 = Kalideres, 20 = Kebon Jeruk, 21 = Palmerah, 22 = Kembangan
        // 23 = Kebayoran Baru, 24 = Kebayoran Lama, 25 = Cilandak, 26 = Pesanggrahan, 27 = Pasar Minggu, 28 = Jagakarsa, 29 = Mampang Prapatan, 30 = Pancoran, 31 = Tebet, 32 = Setiabudi
        // 33 = Matraman, 34 = Pulo Gadung, 35 = Jatinegara, 36 = Duren Sawit, 37 = Kramat Jati, 38 = Makasar, 39 = Cipayung, 40 = Ciracas, 41 = Pasar Rebo, 42 = Cakung
        // 43 = Kepulauan Seribu Utara, 44 = Kepulauan Seribu Selatan

        $kelurahans = [
            // Jakarta Pusat - Gambir (kecamatan_id = 1)
            ['kecamatan_id' => 1, 'nama' => 'Gambir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Kebon Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Duri Pulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Cideng', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Tanah Abang (kecamatan_id = 2)
            ['kecamatan_id' => 2, 'nama' => 'Bendungan Hilir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Karet Tengsin', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Kebon Melati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Kebon Kacang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Kampung Bali', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Petamburan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama' => 'Gelora', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Menteng (kecamatan_id = 3)
            ['kecamatan_id' => 3, 'nama' => 'Cikini', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Gondangdia', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Kebon Sirih', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Menteng', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama' => 'Pegangsaan', 'created_at' => now(), 'updated_at' => now()],

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

            // Jakarta Pusat - Johar Baru (kecamatan_id = 6)
            ['kecamatan_id' => 6, 'nama' => 'Galur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Johar Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Kampung Rawa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 6, 'nama' => 'Tanah Tinggi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Kemayoran (kecamatan_id = 7)
            ['kecamatan_id' => 7, 'nama' => 'Cempaka Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Gunung Sahari Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Harapan Mulia', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Kosong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Kemayoran', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Serdang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Sumur Batu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 7, 'nama' => 'Utan Panjang', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Pusat - Sawah Besar (kecamatan_id = 8)
            ['kecamatan_id' => 8, 'nama' => 'Gunung Sahari Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Kartini', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Karang Anyar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Mangga Dua Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 8, 'nama' => 'Pasar Baru', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Penjaringan (kecamatan_id = 9)
            ['kecamatan_id' => 9, 'nama' => 'Penjaringan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Pluit', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Pejagalan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Kapuk Muara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 9, 'nama' => 'Kamal Muara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Pademangan (kecamatan_id = 10)
            ['kecamatan_id' => 10, 'nama' => 'Ancol', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 10, 'nama' => 'Pademangan Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 10, 'nama' => 'Pademangan Timur', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Tanjung Priok (kecamatan_id = 11)
            ['kecamatan_id' => 11, 'nama' => 'Tanjung Priok', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Sungai Bambu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Papanggo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Warakas', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Kebon Bawang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Sunter Agung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 11, 'nama' => 'Sunter Jaya', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Koja (kecamatan_id = 12)
            ['kecamatan_id' => 12, 'nama' => 'Koja Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Koja Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Tugu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Tugu Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Rawa Badak Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Rawa Badak Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 12, 'nama' => 'Lagoa', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Cilincing (kecamatan_id = 13)
            ['kecamatan_id' => 13, 'nama' => 'Cilincing', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Kali Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Marunda', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Rorotan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Semper Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Semper Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 13, 'nama' => 'Sukapura', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Utara - Kelapa Gading (kecamatan_id = 14)
            ['kecamatan_id' => 14, 'nama' => 'Kelapa Gading Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Kelapa Gading Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 14, 'nama' => 'Pegangsaan Dua', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Cengkareng (kecamatan_id = 15)
            ['kecamatan_id' => 15, 'nama' => 'Cengkareng Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Cengkareng Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Duri Kosambi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Kapuk', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Kedaung Kali Angke', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 15, 'nama' => 'Rawa Buaya', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Grogol Petamburan (kecamatan_id = 16)
            ['kecamatan_id' => 16, 'nama' => 'Grogol', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Jelambar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Jelambar Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Tanjung Duren Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Tanjung Duren Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Tomang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 16, 'nama' => 'Wijaya Kusuma', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Taman Sari (kecamatan_id = 17)
            ['kecamatan_id' => 17, 'nama' => 'Glodok', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Keagungan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Krukut', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Mangga Besar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Maphar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Pinangsia', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Taman Sari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 17, 'nama' => 'Tangki', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Tambora (kecamatan_id = 18)
            ['kecamatan_id' => 18, 'nama' => 'Angke', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Duri Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Duri Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Jembatan Besi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Jembatan Lima', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Kali Anyar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Krendang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Pekojan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Roa Malaka', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Tambora', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 18, 'nama' => 'Tanah Sereal', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kalideres (kecamatan_id = 19)
            ['kecamatan_id' => 19, 'nama' => 'Kalideres', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Kamal', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Pegadungan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Semanan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 19, 'nama' => 'Tegal Alur', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kebon Jeruk (kecamatan_id = 20)
            ['kecamatan_id' => 20, 'nama' => 'Duri Kepa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Kebon Jeruk', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Kedoya Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Kedoya Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Kelapa Dua', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Sukabumi Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 20, 'nama' => 'Sukabumi Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Palmerah (kecamatan_id = 21)
            ['kecamatan_id' => 21, 'nama' => 'Jati Pulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Kemanggisan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Kota Bambu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Kota Bambu Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Palmerah', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 21, 'nama' => 'Slipi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Barat - Kembangan (kecamatan_id = 22)
            ['kecamatan_id' => 22, 'nama' => 'Joglo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Kembangan Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Kembangan Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Meruya Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Meruya Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 22, 'nama' => 'Srengseng', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Kebayoran Baru (kecamatan_id = 23)
            ['kecamatan_id' => 23, 'nama' => 'Gunung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Kramat Pela', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Gandaria Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Cipete Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Pulo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Melawai', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Petogogan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Rawa Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Selong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 23, 'nama' => 'Senayan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Kebayoran Lama (kecamatan_id = 24)
            ['kecamatan_id' => 24, 'nama' => 'Cipulir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Grogol Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Grogol Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Kebayoran Lama Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Kebayoran Lama Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 24, 'nama' => 'Pondok Pinang', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Cilandak (kecamatan_id = 25)
            ['kecamatan_id' => 25, 'nama' => 'Cilandak Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Cipete Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Gandaria Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Lebak Bulus', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 25, 'nama' => 'Pondok Labu', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Pesanggrahan (kecamatan_id = 26)
            ['kecamatan_id' => 26, 'nama' => 'Bintaro', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Pesanggrahan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Petukangan Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Petukangan Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 26, 'nama' => 'Ulujami', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Pasar Minggu (kecamatan_id = 27)
            ['kecamatan_id' => 27, 'nama' => 'Jati Padang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Pejaten Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Pejaten Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Kalibata', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Pasar Minggu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 27, 'nama' => 'Ragunan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Jagakarsa (kecamatan_id = 28)
            ['kecamatan_id' => 28, 'nama' => 'Cipedak', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Jagakarsa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Lenteng Agung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Srengseng Sawah', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Tanjung Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Cilandak Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 28, 'nama' => 'Ciganjur', 'created_at' => now(), 'updated_at' => now()],

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
            ['kecamatan_id' => 30, 'nama' => 'Rawa Jati', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Tebet (kecamatan_id = 31)
            ['kecamatan_id' => 31, 'nama' => 'Bukit Duri', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Kebon Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Manggarai', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Manggarai Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Tebet Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Tebet Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 31, 'nama' => 'Menteng Dalam', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Selatan - Setiabudi (kecamatan_id = 32)
            ['kecamatan_id' => 32, 'nama' => 'Guntur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Karet', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Karet Kuningan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Karet Semanggi', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Kuningan Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Menteng Atas', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Pasar Manggis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 32, 'nama' => 'Setiabudi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Matraman (kecamatan_id = 33)
            ['kecamatan_id' => 33, 'nama' => 'Kebon Manggis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Pal Meriam', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Kayu Manis', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Pisangan Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Utan Kayu Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 33, 'nama' => 'Utan Kayu Utara', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Pulo Gadung (kecamatan_id = 34)
            ['kecamatan_id' => 34, 'nama' => 'Cipinang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Cipinang Besar Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Jati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Jatinegara Kaum', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Pisangan Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Pulo Gadung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 34, 'nama' => 'Rawamangun', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Jatinegara (kecamatan_id = 35)
            ['kecamatan_id' => 35, 'nama' => 'Bali Mester', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Bidaracina', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Besar Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Besar Utara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Cempedak', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Jatinegara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Rawa Bunga', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 35, 'nama' => 'Kampung Melayu', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Duren Sawit (kecamatan_id = 36)
            ['kecamatan_id' => 36, 'nama' => 'Duren Sawit', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Klender', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Malaka Jaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Malaka Sari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Bambu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Kopi', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Kramat Jati (kecamatan_id = 37)
            ['kecamatan_id' => 37, 'nama' => 'Batu Ampar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Balekambang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Cawang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Cililitan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Kampung Tengah', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Kramat Jati', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 37, 'nama' => 'Dukuh', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Makasar (kecamatan_id = 38)
            ['kecamatan_id' => 38, 'nama' => 'Cipinang Melayu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Halim Perdanakusuma', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Kebon Pala', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Makasar', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 38, 'nama' => 'Pinang Ranti', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Cipayung (kecamatan_id = 39)
            ['kecamatan_id' => 39, 'nama' => 'Bambu Apus', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Ceger', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Cilangkap', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Cipayung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Lubang Buaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Pondok Ranggon', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Munjul', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 39, 'nama' => 'Setu', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Ciracas (kecamatan_id = 40)
            ['kecamatan_id' => 40, 'nama' => 'Cibubur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Kelapa Dua Wetan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Mekarsari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Rambutan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 40, 'nama' => 'Susukan', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Pasar Rebo (kecamatan_id = 41)
            ['kecamatan_id' => 41, 'nama' => 'Cijantung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kampung Baru', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kampung Gedong', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Kalisari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 41, 'nama' => 'Pekayon', 'created_at' => now(), 'updated_at' => now()],

            // Jakarta Timur - Cakung (kecamatan_id = 42)
            ['kecamatan_id' => 42, 'nama' => 'Cakung Barat', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Cakung Timur', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Jatinegara', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Rawa Terate', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Penggilingan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Pulo Gebang', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 42, 'nama' => 'Ujung Menteng', 'created_at' => now(), 'updated_at' => now()],

            // Kepulauan Seribu - Kepulauan Seribu Utara (kecamatan_id = 43)
            ['kecamatan_id' => 43, 'nama' => 'Pulau Harapan', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Kelapa', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Panggang', 'created_at' => now(), 'updated_at' => now()],

            // Kepulauan Seribu - Kepulauan Seribu Selatan (kecamatan_id = 44)
            ['kecamatan_id' => 44, 'nama' => 'Pulau Pari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Tidung', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Untung Jawa', 'created_at' => now(), 'updated_at' => now()]
        ];

        Kelurahan::insert($kelurahans);
    }
}
