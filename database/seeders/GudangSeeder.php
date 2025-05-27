<?php

namespace Database\Seeders;

use App\Models\LokasiStok;
use App\Models\UnitKerja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudangs =
            [
                ['unit_id' => 44, 'nama' => 'Gudang JP Sunter Kemayoran', 'slug' => 'gudang-jp-sunter-kemayoran', 'alamat' => 'Jl. Sunter Kemayoran RT13/RW09, Kel. Sunter Jaya, Kec. Tj. Priok', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 44, 'nama' => 'Gudang Material Damplas', 'slug' => 'gudang-material-damplas', 'alamat' => 'Jl. D. Dampelas RT19 / RW04, Kel. Bendungan Hilir, Kec. Tanah Abang', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 44, 'nama' => 'Gudang Perkakas dan Solar Pompa Melati', 'slug' => 'gudang-perkakas-dan-solar-pompa-melati', 'alamat' => 'Jl. Dukuh Pinggir I No. 23, RT02/RW05, Kel. Kebon Melati, Kec. Tanah Abang', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 51, 'nama' => 'Gudang Material Ketel Uap', 'slug' => 'gudang-material-ketel-uap', 'alamat' => 'Jl. Ketel Uap No.1 Kel. Ancol Kec. Pademangan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 51, 'nama' => 'Gudang Material Pemeliharaan Drainase SDA JU', 'slug' => 'gudang-material-pemeliharaan-drainase-sda-ju', 'alamat' => 'Jalan Yos Sudarso ( Samping Pompa Taman Plumpang) Kel. Rawa Badak Utara', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 58, 'nama' => 'Gudang Material Kembangan', 'slug' => 'gudang-material-kembangan', 'alamat' => 'Jl. Pulau Tidung IV No.9, RT.7/RW.3, Kembangan Utara, Kec. Kembangan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 58, 'nama' => 'Gudang Material Mercu Buana', 'slug' => 'gudang-material-mercu-buana', 'alamat' => 'Jl. Meruya Selatan 58-4, RT.4/RW.1, Meruya Sel., Kec. Kembangan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 58, 'nama' => 'Gudang Material Pos Pengumben', 'slug' => 'gudang-material-pos-pengumben', 'alamat' => 'Jl. Pos Pengumben Lama No.23, RT.4/RW.5, Sukabumi Selatan Kec. Kb. Jeruk', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 58, 'nama' => 'Gudang Material Rumah Pompa Perumnas', 'slug' => 'gudang-material-rumah-pompa-perumnas', 'alamat' => 'Jl. Pedongkelan Raya, RT.15/RW.12, Kapuk, Kecamatan Cengkareng', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 58, 'nama' => 'Gudang Material Rumah Pompa Tomang Barat', 'slug' => 'gudang-material-rumah-pompa-tomang-barat', 'alamat' => 'Jl. Tanjung Duren Utara XI No.44 3, RT.3/RW.1, Tj. Duren Utara, Kec. Grogol petamburan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 65, 'nama' => 'Gudang Material Rawa Minyak', 'slug' => 'gudang-material-rawa-minyak', 'alamat' => 'Jl. AUP Kellurahan Pasar Minggu Kecamatan Pasar Minggu', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 65, 'nama' => 'Gudang Material Ex Alteng', 'slug' => 'gudang-material-ex-alteng', 'alamat' => 'Jl. Kelapa 3 No. 45 Kel. Lenteng Agung kec. Jagakarsa', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Material SDA Pondok Bambu', 'slug' => 'gudang-material-sda-pondok-bambu', 'alamat' => 'Jl. Pahlawan Revolusi ( BKT No 9 RT 009 RW 009 Kel. Pondok Bambu Kec. Duren Sawit', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Solar Pulogebang', 'slug' => 'gudang-solar-pulogebang', 'alamat' => 'Jl. Raya Stasiun Cakung (Samping Sop Ayam Pak Min), Kel. Pulogebang Kec. Cakung', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Material Rawa Bebek sisi Utara', 'slug' => 'gudang-material-rawa-bebek-sisi-utara', 'alamat' => 'Jl. Rawa Bebek No.7 13, RT.006/RW.6, Pulo Gebang, Kec. Cakung', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Material Rawa Bebek sisi Selatan', 'slug' => 'gudang-material-rawa-bebek-sisi-selatan', 'alamat' => 'Jl. Rawa Bebek No.6, RT.7/RW.6, Pulo Gebang, Kec. Cakung', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Material Kecamatan Ciracas', 'slug' => 'gudang-material-kecamatan-ciracas', 'alamat' => 'Jl. Raya Ciracas No.3 1, RW.11, Klp. Dua Wetan, Kec. Ciracas', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 72, 'nama' => 'Gudang Material Ujung Menteng', 'slug' => 'gudang-material-ujung-menteng', 'alamat' => 'Jl. Menteng Kejora 5, Ujung Menteng, Kec. Cakung', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Untung Jawa', 'slug' => 'gudang-material-pantai-untung-jawa', 'alamat' => 'Jalan Betok Besi Rt.02 Rw. 002 Pulau Untung Jawa', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Untung Jawa', 'slug' => 'gudang-material-abal-untung-jawa', 'alamat' => 'Pulau untung Jawa jl. Bougenville RT 02/03 kel.pulau untung Jawa kec.kep.seribu selatan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Lancang', 'slug' => 'gudang-material-pantai-lancang', 'alamat' => 'RT 003 / RW 03 Dermaga Pelabuhan Barat', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Lancang', 'slug' => 'gudang-material-abal-lancang', 'alamat' => 'Wilayah RT 003 RW 03 Dermaga Barat Pulau Lancang', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Material Pantai Pari', 'slug' => 'material-pantai-pari', 'alamat' => 'Jalan Pari Utama Rt.02 Rw. 04 Pulau Pari Kec. Kepulauan Seribu Selatan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Tidung', 'slug' => 'gudang-material-pantai-tidung', 'alamat' => 'Jalan Gudang SDA Lampu Delapan, Rt.002 Rw. 02 Kel. Pulau Tidung, Kec. Seribu Selatan, Kabupaten Administrasi Kepulauan Seribu, DKI Jakarta', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Tidung', 'slug' => 'gudang-material-abal-tidung', 'alamat' => 'jalan pantai selatan Rt 006/02 keluarahan pulau tidung kec. Kepulauan seribu selatan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Pramuka', 'slug' => 'gudang-material-pantai-pramuka', 'alamat' => 'Wilayah RT. 003 RW. 04 Lapangan Bola, Pulau Pramuka.', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Pramuka', 'slug' => 'gudang-material-abal-pramuka', 'alamat' => 'Pulau Pramuka, RT 003 RW 005, Kelurahan Pulau Panggang, Kec. Kepulauan Seribu Utara, Kab. Adm. Kepulauan Seribu', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Panggang', 'slug' => 'gudang-material-pantai-panggang', 'alamat' => 'Jl.ikan mogong  Dermaga nelayan 1 RT 003 RW 001 pulau panggang', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Panggang', 'slug' => 'gudang-material-abal-panggang', 'alamat' => 'Jl.ikan mogong, Dermaga nelayan 1 , RT 003 RW 001 pulau panggang', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Kelapa', 'slug' => 'gudang-material-pantai-kelapa', 'alamat' => 'Rt 001/02 kelurahan pulau kelapa', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Kelapa', 'slug' => 'gudang-material-abal-kelapa', 'alamat' => 'Pulau Kelapa, RT 006 RW 002, Kelurahan Pulau Kelapa, Kec. Kepulauan Seribu Utara, Kab. Adm. Kepulauan Seribu', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Harapan', 'slug' => 'gudang-material-pantai-harapan', 'alamat' => 'Jalan Lingkar Sisi Barat Rt. 001, Rw. 001 Kel. Pulau Harapan, Kec. Kepulauan Seribu Utara.', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Harapan', 'slug' => 'gudang-material-abal-harapan', 'alamat' => 'Pulau harapan RT 001 Rw 01 kelurahan pulau harapan', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Kelapa Dua', 'slug' => 'gudang-material-pantai-kelapa-dua', 'alamat' => 'Pulau Kelapa Dua Rt. 001/ Rw. 005 Kel. Pulau Kelapa Kec. Kepulauan Seribu Utara.', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Kelapa Dua', 'slug' => 'gudang-material-abal-kelapa-dua', 'alamat' => 'Gedung SPALD kelapa dua RT.003/ RW.005, Pulau Kelapa Dua', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material Pantai Sebira', 'slug' => 'gudang-material-pantai-sebira', 'alamat' => 'Pulau sabira RT 004/RW 003', 'created_at' => now(), 'updated_at' => now()],
                ['unit_id' => 79, 'nama' => 'Gudang Material ABAL Sebira', 'slug' => 'gudang-material-abal-sebira', 'alamat' => 'Pulau Sabira, RT 001 RW 003, Kelurahan Pulau Sabira, Kec. Kepulauan Seribu Utara, Kab. Adm. Kepulauan Seribu', 'created_at' => now(), 'updated_at' => now()],
            ];
        LokasiStok::insert($gudangs);
    }
}
