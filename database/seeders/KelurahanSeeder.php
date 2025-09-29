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
            ['kecamatan_id' => 1, 'nama' => 'Cideng'],
            ['kecamatan_id' => 1, 'nama' => 'Duri Pulo'],
            ['kecamatan_id' => 1, 'nama' => 'Gambir'],
            ['kecamatan_id' => 1, 'nama' => 'Kebon Kelapa'],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Selatan'],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Utara'],

            // Jakarta Pusat - Sawah Besar (kecamatan_id = 2)
            ['kecamatan_id' => 2, 'nama' => 'Gunung Sahari Utara'],
            ['kecamatan_id' => 2, 'nama' => 'Karang Anyar'],
            ['kecamatan_id' => 2, 'nama' => 'Kartini'],
            ['kecamatan_id' => 2, 'nama' => 'Mangga Dua Selatan'],
            ['kecamatan_id' => 2, 'nama' => 'Pasar Baru'],

            // Jakarta Pusat - Kemayoran (kecamatan_id = 3)
            ['kecamatan_id' => 3, 'nama' => 'Cempaka Baru'],
            ['kecamatan_id' => 3, 'nama' => 'Gunung Sahari Selatan'],
            ['kecamatan_id' => 3, 'nama' => 'Harapan Mulya'],
            ['kecamatan_id' => 3, 'nama' => 'Kebon Kosong'],
            ['kecamatan_id' => 3, 'nama' => 'Kemayoran'],
            ['kecamatan_id' => 3, 'nama' => 'Serdang'],
            ['kecamatan_id' => 3, 'nama' => 'Sumur Batu'],
            ['kecamatan_id' => 3, 'nama' => 'Utan Panjang'],

            // Jakarta Pusat - Senen (kecamatan_id = 4)
            ['kecamatan_id' => 4, 'nama' => 'Bungur'],
            ['kecamatan_id' => 4, 'nama' => 'Kenari'],
            ['kecamatan_id' => 4, 'nama' => 'Kramat'],
            ['kecamatan_id' => 4, 'nama' => 'Kwitang'],
            ['kecamatan_id' => 4, 'nama' => 'Paseban'],
            ['kecamatan_id' => 4, 'nama' => 'Senen'],

            // Jakarta Pusat - Cempaka Putih (kecamatan_id = 5)
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Barat'],
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Timur'],
            ['kecamatan_id' => 5, 'nama' => 'Rawasari'],

            // Jakarta Pusat - Menteng (kecamatan_id = 6)
            ['kecamatan_id' => 6, 'nama' => 'Cikini'],
            ['kecamatan_id' => 6, 'nama' => 'Gondangdia'],
            ['kecamatan_id' => 6, 'nama' => 'Kebon Sirih'],
            ['kecamatan_id' => 6, 'nama' => 'Menteng'],
            ['kecamatan_id' => 6, 'nama' => 'Pegangsaan'],

            // Jakarta Pusat - Tanah Abang (kecamatan_id = 7)
            ['kecamatan_id' => 7, 'nama' => 'Bendungan Hilir'],
            ['kecamatan_id' => 7, 'nama' => 'Gelora'],
            ['kecamatan_id' => 7, 'nama' => 'Kampung Bali'],
            ['kecamatan_id' => 7, 'nama' => 'Karet Tengsin'],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Kacang'],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Melati'],
            ['kecamatan_id' => 7, 'nama' => 'Petamburan'],

            // Jakarta Pusat - Johar Baru (kecamatan_id = 8)
            ['kecamatan_id' => 8, 'nama' => 'Galur'],
            ['kecamatan_id' => 8, 'nama' => 'Johar Baru'],
            ['kecamatan_id' => 8, 'nama' => 'Kampung Rawa'],
            ['kecamatan_id' => 8, 'nama' => 'Tanah Tinggi'],

            // Jakarta Utara - Cilincing (kecamatan_id = 9)
            ['kecamatan_id' => 9, 'nama' => 'Cilincing'],
            ['kecamatan_id' => 9, 'nama' => 'Kalibaru'],
            ['kecamatan_id' => 9, 'nama' => 'Marunda'],
            ['kecamatan_id' => 9, 'nama' => 'Rorotan'],
            ['kecamatan_id' => 9, 'nama' => 'Semper Barat'],
            ['kecamatan_id' => 9, 'nama' => 'Semper Timur'],
            ['kecamatan_id' => 9, 'nama' => 'Sukapura'],

            // Jakarta Utara - Kelapa Gading (kecamatan_id = 10)
            ['kecamatan_id' => 10, 'nama' => 'Kelapa Gading Barat'],
            ['kecamatan_id' => 10, 'nama' => 'Kelapa Gading Timur'],
            ['kecamatan_id' => 10, 'nama' => 'Pegangsaan Dua'],

            // Jakarta Utara - Koja (kecamatan_id = 11)
            ['kecamatan_id' => 11, 'nama' => 'Koja'],
            ['kecamatan_id' => 11, 'nama' => 'Lagoa'],
            ['kecamatan_id' => 11, 'nama' => 'Rawa Badak Selatan'],
            ['kecamatan_id' => 11, 'nama' => 'Rawa Badak Utara'],
            ['kecamatan_id' => 11, 'nama' => 'Tugu Selatan'],
            ['kecamatan_id' => 11, 'nama' => 'Tugu Utara'],

            // Jakarta Utara - Pademangan (kecamatan_id = 12)
            ['kecamatan_id' => 12, 'nama' => 'Ancol'],
            ['kecamatan_id' => 12, 'nama' => 'Pademangan Barat'],
            ['kecamatan_id' => 12, 'nama' => 'Pademangan Timur'],

            // Jakarta Utara - Penjaringan (kecamatan_id = 13)
            ['kecamatan_id' => 13, 'nama' => 'Kamal Muara'],
            ['kecamatan_id' => 13, 'nama' => 'Kapuk Muara'],
            ['kecamatan_id' => 13, 'nama' => 'Pejagalan'],
            ['kecamatan_id' => 13, 'nama' => 'Penjaringan'],
            ['kecamatan_id' => 13, 'nama' => 'Pluit'],

            // Jakarta Utara - Tanjung Priok (kecamatan_id = 14)
            ['kecamatan_id' => 14, 'nama' => 'Kebon Bawang'],
            ['kecamatan_id' => 14, 'nama' => 'Papanggo'],
            ['kecamatan_id' => 14, 'nama' => 'Sungai Bambu'],
            ['kecamatan_id' => 14, 'nama' => 'Sunter Agung'],
            ['kecamatan_id' => 14, 'nama' => 'Sunter Jaya'],
            ['kecamatan_id' => 14, 'nama' => 'Tanjung Priok'],
            ['kecamatan_id' => 14, 'nama' => 'Warakas'],

            // Jakarta Timur - Cakung (kecamatan_id = 15)
            ['kecamatan_id' => 15, 'nama' => 'Cakung Barat'],
            ['kecamatan_id' => 15, 'nama' => 'Cakung Timur'],
            ['kecamatan_id' => 15, 'nama' => 'Jatinegara'],
            ['kecamatan_id' => 15, 'nama' => 'Penggilingan'],
            ['kecamatan_id' => 15, 'nama' => 'Pulo Gebang'],
            ['kecamatan_id' => 15, 'nama' => 'Rawa Terate'],
            ['kecamatan_id' => 15, 'nama' => 'Ujung Menteng'],

            // Jakarta Timur - Cipayung (kecamatan_id = 16)
            ['kecamatan_id' => 16, 'nama' => 'Bambu Apus'],
            ['kecamatan_id' => 16, 'nama' => 'Ceger'],
            ['kecamatan_id' => 16, 'nama' => 'Cilangkap'],
            ['kecamatan_id' => 16, 'nama' => 'Cipayung'],
            ['kecamatan_id' => 16, 'nama' => 'Lubang Buaya'],
            ['kecamatan_id' => 16, 'nama' => 'Munjul'],
            ['kecamatan_id' => 16, 'nama' => 'Pondok Ranggon'],
            ['kecamatan_id' => 16, 'nama' => 'Setu'],

            // Jakarta Timur - Ciracas (kecamatan_id = 17)
            ['kecamatan_id' => 17, 'nama' => 'Cibubur'],
            ['kecamatan_id' => 17, 'nama' => 'Ciracas'],
            ['kecamatan_id' => 17, 'nama' => 'Kelapa Dua Wetan'],
            ['kecamatan_id' => 17, 'nama' => 'Rambutan'],
            ['kecamatan_id' => 17, 'nama' => 'Susukan'],

            // Jakarta Timur - Duren Sawit (kecamatan_id = 18)
            ['kecamatan_id' => 18, 'nama' => 'Duren Sawit'],
            ['kecamatan_id' => 18, 'nama' => 'Klender'],
            ['kecamatan_id' => 18, 'nama' => 'Malaka Jaya'],
            ['kecamatan_id' => 18, 'nama' => 'Malaka Sari'],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Bambu'],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Kelapa'],
            ['kecamatan_id' => 18, 'nama' => 'Pondok Kopi'],

            // Jakarta Timur - Jatinegara (kecamatan_id = 19)
            ['kecamatan_id' => 19, 'nama' => 'Bali Mester'],
            ['kecamatan_id' => 19, 'nama' => 'Bidara Cina'],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Besar Selatan'],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Besar Utara'],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Cempedak'],
            ['kecamatan_id' => 19, 'nama' => 'Cipinang Muara'],
            ['kecamatan_id' => 19, 'nama' => 'Kampung Melayu'],
            ['kecamatan_id' => 19, 'nama' => 'Rawa Bunga'],

            // Jakarta Timur - Kramat Jati (kecamatan_id = 20)
            ['kecamatan_id' => 20, 'nama' => 'Balekambang'],
            ['kecamatan_id' => 20, 'nama' => 'Batu Ampar'],
            ['kecamatan_id' => 20, 'nama' => 'Cawang'],
            ['kecamatan_id' => 20, 'nama' => 'Cililitan'],
            ['kecamatan_id' => 20, 'nama' => 'Dukuh'],
            ['kecamatan_id' => 20, 'nama' => 'Kramat Jati'],
            ['kecamatan_id' => 20, 'nama' => 'Tengah'],

            // Jakarta Timur - Makasar (kecamatan_id = 21)
            ['kecamatan_id' => 21, 'nama' => 'Cipinang Melayu'],
            ['kecamatan_id' => 21, 'nama' => 'Halim Perdana Kusuma'],
            ['kecamatan_id' => 21, 'nama' => 'Kebon Pala'],
            ['kecamatan_id' => 21, 'nama' => 'Makasar'],
            ['kecamatan_id' => 21, 'nama' => 'Pinang Ranti'],

            // Jakarta Timur - Matraman (kecamatan_id = 22)
            ['kecamatan_id' => 22, 'nama' => 'Kayu Manis'],
            ['kecamatan_id' => 22, 'nama' => 'Kebon Manggis'],
            ['kecamatan_id' => 22, 'nama' => 'Pal Meriam'],
            ['kecamatan_id' => 22, 'nama' => 'Pisangan Baru'],
            ['kecamatan_id' => 22, 'nama' => 'Utan Kayu Selatan'],
            ['kecamatan_id' => 22, 'nama' => 'Utan Kayu Utara'],

            // Jakarta Timur - Pasar Rebo (kecamatan_id = 23)
            ['kecamatan_id' => 23, 'nama' => 'Baru'],
            ['kecamatan_id' => 23, 'nama' => 'Cijantung'],
            ['kecamatan_id' => 23, 'nama' => 'Gedong'],
            ['kecamatan_id' => 23, 'nama' => 'Kalisari'],
            ['kecamatan_id' => 23, 'nama' => 'Pekayon'],

            // Jakarta Timur - Pulo Gadung (kecamatan_id = 24)
            ['kecamatan_id' => 24, 'nama' => 'Cipinang'],
            ['kecamatan_id' => 24, 'nama' => 'Jati'],
            ['kecamatan_id' => 24, 'nama' => 'Jatinegara Kaum'],
            ['kecamatan_id' => 24, 'nama' => 'Kayu Putih'],
            ['kecamatan_id' => 24, 'nama' => 'Pisangan Timur'],
            ['kecamatan_id' => 24, 'nama' => 'Pulo Gadung'],
            ['kecamatan_id' => 24, 'nama' => 'Rawamangun'],

            // Jakarta Selatan - Cilandak (kecamatan_id = 25)
            ['kecamatan_id' => 25, 'nama' => 'Cilandak Barat'],
            ['kecamatan_id' => 25, 'nama' => 'Cipete Selatan'],
            ['kecamatan_id' => 25, 'nama' => 'Gandaria Selatan'],
            ['kecamatan_id' => 25, 'nama' => 'Lebak Bulus'],
            ['kecamatan_id' => 25, 'nama' => 'Pondok Labu'],

            // Jakarta Selatan - Jagakarsa (kecamatan_id = 26)
            ['kecamatan_id' => 26, 'nama' => 'Ciganjur'],
            ['kecamatan_id' => 26, 'nama' => 'Cipedak'],
            ['kecamatan_id' => 26, 'nama' => 'Jagakarsa'],
            ['kecamatan_id' => 26, 'nama' => 'Lenteng Agung'],
            ['kecamatan_id' => 26, 'nama' => 'Srengseng Sawah'],
            ['kecamatan_id' => 26, 'nama' => 'Tanjung Barat'],

            // Jakarta Selatan - Kebayoran Baru (kecamatan_id = 27)
            ['kecamatan_id' => 27, 'nama' => 'Cipete Utara'],
            ['kecamatan_id' => 27, 'nama' => 'Gandaria Utara'],
            ['kecamatan_id' => 27, 'nama' => 'Gunung'],
            ['kecamatan_id' => 27, 'nama' => 'Kramat Pela'],
            ['kecamatan_id' => 27, 'nama' => 'Melawai'],
            ['kecamatan_id' => 27, 'nama' => 'Petogogan'],
            ['kecamatan_id' => 27, 'nama' => 'Pulo'],
            ['kecamatan_id' => 27, 'nama' => 'Rawa Barat'],
            ['kecamatan_id' => 27, 'nama' => 'Selong'],
            ['kecamatan_id' => 27, 'nama' => 'Senayan'],

            // Jakarta Selatan - Kebayoran Lama (kecamatan_id = 28)
            ['kecamatan_id' => 28, 'nama' => 'Cipulir'],
            ['kecamatan_id' => 28, 'nama' => 'Grogol Selatan'],
            ['kecamatan_id' => 28, 'nama' => 'Grogol Utara'],
            ['kecamatan_id' => 28, 'nama' => 'Kebayoran Lama Selatan'],
            ['kecamatan_id' => 28, 'nama' => 'Kebayoran Lama Utara'],
            ['kecamatan_id' => 28, 'nama' => 'Pondok Pinang'],

            // Jakarta Selatan - Mampang Prapatan (kecamatan_id = 29)
            ['kecamatan_id' => 29, 'nama' => 'Bangka'],
            ['kecamatan_id' => 29, 'nama' => 'Kuningan Barat'],
            ['kecamatan_id' => 29, 'nama' => 'Mampang Prapatan'],
            ['kecamatan_id' => 29, 'nama' => 'Pela Mampang'],
            ['kecamatan_id' => 29, 'nama' => 'Tegal Parang'],

            // Jakarta Selatan - Pancoran (kecamatan_id = 30)
            ['kecamatan_id' => 30, 'nama' => 'Cikoko'],
            ['kecamatan_id' => 30, 'nama' => 'Duren Tiga'],
            ['kecamatan_id' => 30, 'nama' => 'Kalibata'],
            ['kecamatan_id' => 30, 'nama' => 'Pancoran'],
            ['kecamatan_id' => 30, 'nama' => 'Pengadegan'],
            ['kecamatan_id' => 30, 'nama' => 'Rawajati'],

            // Jakarta Selatan - Pasar Minggu (kecamatan_id = 31)
            ['kecamatan_id' => 31, 'nama' => 'Cilandak Timur'],
            ['kecamatan_id' => 31, 'nama' => 'Jati Padang'],
            ['kecamatan_id' => 31, 'nama' => 'Kebagusan'],
            ['kecamatan_id' => 31, 'nama' => 'Pasar Minggu'],
            ['kecamatan_id' => 31, 'nama' => 'Pejaten Barat'],
            ['kecamatan_id' => 31, 'nama' => 'Pejaten Timur'],
            ['kecamatan_id' => 31, 'nama' => 'Ragunan'],

            // Jakarta Selatan - Pesanggrahan (kecamatan_id = 32)
            ['kecamatan_id' => 32, 'nama' => 'Bintaro'],
            ['kecamatan_id' => 32, 'nama' => 'Pesanggrahan'],
            ['kecamatan_id' => 32, 'nama' => 'Petukangan Selatan'],
            ['kecamatan_id' => 32, 'nama' => 'Petukangan Utara'],
            ['kecamatan_id' => 32, 'nama' => 'Ulujami'],

            // Jakarta Selatan - Setiabudi (kecamatan_id = 33)
            ['kecamatan_id' => 33, 'nama' => 'Guntur'],
            ['kecamatan_id' => 33, 'nama' => 'Karet Kuningan'],
            ['kecamatan_id' => 33, 'nama' => 'Karet Semanggi'],
            ['kecamatan_id' => 33, 'nama' => 'Karet'],
            ['kecamatan_id' => 33, 'nama' => 'Kuningan Timur'],
            ['kecamatan_id' => 33, 'nama' => 'Menteng Atas'],
            ['kecamatan_id' => 33, 'nama' => 'Pasar Manggis'],
            ['kecamatan_id' => 33, 'nama' => 'Setiabudi'],

            // Jakarta Selatan - Tebet (kecamatan_id = 34)
            ['kecamatan_id' => 34, 'nama' => 'Bukit Duri'],
            ['kecamatan_id' => 34, 'nama' => 'Kebon Baru'],
            ['kecamatan_id' => 34, 'nama' => 'Manggarai Selatan'],
            ['kecamatan_id' => 34, 'nama' => 'Manggarai'],
            ['kecamatan_id' => 34, 'nama' => 'Menteng Dalam'],
            ['kecamatan_id' => 34, 'nama' => 'Tebet Barat'],
            ['kecamatan_id' => 34, 'nama' => 'Tebet Timur'],

            // Jakarta Barat - Cengkareng (kecamatan_id = 35)
            ['kecamatan_id' => 35, 'nama' => 'Cengkareng Barat'],
            ['kecamatan_id' => 35, 'nama' => 'Cengkareng Timur'],
            ['kecamatan_id' => 35, 'nama' => 'Duri Kosambi'],
            ['kecamatan_id' => 35, 'nama' => 'Kapuk'],
            ['kecamatan_id' => 35, 'nama' => 'Kedaung Kali Angke'],
            ['kecamatan_id' => 35, 'nama' => 'Rawa Buaya'],

            // Jakarta Barat - Grogol Petamburan (kecamatan_id = 36)
            ['kecamatan_id' => 36, 'nama' => 'Grogol'],
            ['kecamatan_id' => 36, 'nama' => 'Jembatan Baru'],
            ['kecamatan_id' => 36, 'nama' => 'Jelambar'],
            ['kecamatan_id' => 36, 'nama' => 'Tanjung Duren Selatan'],
            ['kecamatan_id' => 36, 'nama' => 'Tanjung Duren Utara'],
            ['kecamatan_id' => 36, 'nama' => 'Tomang'],
            ['kecamatan_id' => 36, 'nama' => 'Wijaya Kusuma'],

            // Jakarta Barat - Taman Sari (kecamatan_id = 37)
            ['kecamatan_id' => 37, 'nama' => 'Glodok'],
            ['kecamatan_id' => 37, 'nama' => 'Keagungan'],
            ['kecamatan_id' => 37, 'nama' => 'Krukut'],
            ['kecamatan_id' => 37, 'nama' => 'Mangga Besar'],
            ['kecamatan_id' => 37, 'nama' => 'Maphar'],
            ['kecamatan_id' => 37, 'nama' => 'Pinangsia'],
            ['kecamatan_id' => 37, 'nama' => 'Taman Sari'],
            ['kecamatan_id' => 37, 'nama' => 'Tangki'],

            // Jakarta Barat - Tambora (kecamatan_id = 38)
            ['kecamatan_id' => 38, 'nama' => 'Angke'],
            ['kecamatan_id' => 38, 'nama' => 'Duri Selatan'],
            ['kecamatan_id' => 38, 'nama' => 'Duri Utara'],
            ['kecamatan_id' => 38, 'nama' => 'Jembatan Besi'],
            ['kecamatan_id' => 38, 'nama' => 'Jembatan Lima'],
            ['kecamatan_id' => 38, 'nama' => 'Kali Anyar'],
            ['kecamatan_id' => 38, 'nama' => 'Krendang'],
            ['kecamatan_id' => 38, 'nama' => 'Pekojan'],
            ['kecamatan_id' => 38, 'nama' => 'Roa Malaka'],
            ['kecamatan_id' => 38, 'nama' => 'Tambora'],
            ['kecamatan_id' => 38, 'nama' => 'Tanah Sereal'],

            // Jakarta Barat - Kebon Jeruk (kecamatan_id = 39)
            ['kecamatan_id' => 39, 'nama' => 'Duri Kepa'],
            ['kecamatan_id' => 39, 'nama' => 'Kebon Jeruk'],
            ['kecamatan_id' => 39, 'nama' => 'Kedoya Selatan'],
            ['kecamatan_id' => 39, 'nama' => 'Kedoya Utara'],
            ['kecamatan_id' => 39, 'nama' => 'Kelapa Dua'],
            ['kecamatan_id' => 39, 'nama' => 'Sukabumi Selatan'],
            ['kecamatan_id' => 39, 'nama' => 'Sukabumi Utara'],

            // Jakarta Barat - Kalideres (kecamatan_id = 40)
            ['kecamatan_id' => 40, 'nama' => 'Kalideres'],
            ['kecamatan_id' => 40, 'nama' => 'Kamal'],
            ['kecamatan_id' => 40, 'nama' => 'Pegadungan'],
            ['kecamatan_id' => 40, 'nama' => 'Semanan'],
            ['kecamatan_id' => 40, 'nama' => 'Tegal Alur'],

            // Jakarta Barat - Palmerah (kecamatan_id = 41)
            ['kecamatan_id' => 41, 'nama' => 'Jatipulo'],
            ['kecamatan_id' => 41, 'nama' => 'Kemanggisan'],
            ['kecamatan_id' => 41, 'nama' => 'Kota Bambu Selatan'],
            ['kecamatan_id' => 41, 'nama' => 'Kota Bambu Utara'],
            ['kecamatan_id' => 41, 'nama' => 'Palmerah'],
            ['kecamatan_id' => 41, 'nama' => 'Slipi'],

            // Jakarta Barat - Kembangan (kecamatan_id = 42)
            ['kecamatan_id' => 42, 'nama' => 'Joglo'],
            ['kecamatan_id' => 42, 'nama' => 'Kembangan Selatan'],
            ['kecamatan_id' => 42, 'nama' => 'Kembangan Utara'],
            ['kecamatan_id' => 42, 'nama' => 'Meruya Selatan'],
            ['kecamatan_id' => 42, 'nama' => 'Meruya Utara'],
            ['kecamatan_id' => 42, 'nama' => 'Srengseng'],

            // Kepulauan Seribu - Kepulauan Seribu Utara (kecamatan_id = 43)
            ['kecamatan_id' => 43, 'nama' => 'Pulau Harapan'],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Kelapa'],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Panggang'],

            // Kepulauan Seribu - Kepulauan Seribu Selatan (kecamatan_id = 44)
            ['kecamatan_id' => 44, 'nama' => 'Pulau Pari'],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Tidung'],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Untung Jawa']
        ];
        // Insert data using firstOrCreate in chunks to avoid duplicates and maintain performance
        foreach (array_chunk($kelurahans, 50) as $chunk) {
            foreach ($chunk as $kelurahan) {
                Kelurahan::firstOrCreate([
                    'kecamatan_id' => $kelurahan['kecamatan_id'],
                    'nama' => $kelurahan['nama']
                ]);
            }

        }
    }
}