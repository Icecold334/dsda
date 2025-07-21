<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Security;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DriverSecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Driver berdasarkan JSON
        $drivers = [
            // Sudin SDA Pusat
            [
                'nama' => 'Iwam Hanapi',
                'nopol' => 'B 80098189 XX', // ID karyawan dijadikan nopol
                'kecamatan' => 'Gambir',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Agus Supriyanto',
                'nopol' => 'B 80099451 XX',
                'kecamatan' => 'Sawah Besar',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Bambang Riyanto',
                'nopol' => 'B 80734228 XX',
                'kecamatan' => 'Kemayoran',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Eko Sugianto',
                'nopol' => 'B 80333008 XX',
                'kecamatan' => 'Senen',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Arga Seftiyan Wijaya',
                'nopol' => 'B 80049975 XX',
                'kecamatan' => 'Cempaka Putih',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Bambang Sunarto',
                'nopol' => 'B 80108282 XX',
                'kecamatan' => 'Menteng',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Suryanto',
                'nopol' => 'B 80028235 XX',
                'kecamatan' => 'Tanah Abang',
                'wilayah' => 'Jakarta Pusat'
            ],

            // Sudin SDA Utara
            [
                'nama' => 'Hisyam Anshori',
                'nopol' => 'B 80243096 XX',
                'kecamatan' => 'Cilincing',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Absalon Wahyudin Tarihoran',
                'nopol' => 'B 80124593 XX',
                'kecamatan' => 'Cilincing',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Saripudin',
                'nopol' => 'B 80156921 XX',
                'kecamatan' => 'Kelapa Gading',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Dadang',
                'nopol' => 'B 80124313 XX',
                'kecamatan' => 'Kelapa Gading',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Syarifudin',
                'nopol' => 'B 80125522 XX',
                'kecamatan' => 'Koja',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Diding',
                'nopol' => 'B 80295140 XX',
                'kecamatan' => 'Pademangan',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Satria',
                'nopol' => 'B 80124724 XX',
                'kecamatan' => 'Pademangan',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Margono',
                'nopol' => 'B 80124299 XX',
                'kecamatan' => 'Tanjung Priok',
                'wilayah' => 'Jakarta Utara'
            ],

            // Sudin SDA Timur
            [
                'nama' => 'Joni Susilo',
                'nopol' => 'B 80123456 XX',
                'kecamatan' => 'Cakung',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Suryadi',
                'nopol' => 'B 80234567 XX',
                'kecamatan' => 'Cipayung',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'nopol' => 'B 80345678 XX',
                'kecamatan' => 'Kramat Jati',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Budi Santoso',
                'nopol' => 'B 80456789 XX',
                'kecamatan' => 'Makasar',
                'wilayah' => 'Jakarta Timur'
            ],

            // Sudin SDA Selatan
            [
                'nama' => 'Dedi Kurniawan',
                'nopol' => 'B 80567890 XX',
                'kecamatan' => 'Cilandak',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Roni Setiawan',
                'nopol' => 'B 80678901 XX',
                'kecamatan' => 'Jagakarsa',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Hadi Purnomo',
                'nopol' => 'B 80789012 XX',
                'kecamatan' => 'Kebayoran Baru',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Eko Prasetyo',
                'nopol' => 'B 80890123 XX',
                'kecamatan' => 'Mampang Prapatan',
                'wilayah' => 'Jakarta Selatan'
            ],

            // Sudin SDA Barat
            [
                'nama' => 'Joko Widodo',
                'nopol' => 'B 80901234 XX',
                'kecamatan' => 'Cengkareng',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Indra Gunawan',
                'nopol' => 'B 80012345 XX',
                'kecamatan' => 'Grogol Petamburan',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Hendra Setiadi',
                'nopol' => 'B 80123789 XX',
                'kecamatan' => 'Kalideres',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Wahyu Firmansyah',
                'nopol' => 'B 80234890 XX',
                'kecamatan' => 'Kebon Jeruk',
                'wilayah' => 'Jakarta Barat'
            ],

            // Sudin SDA Kepulauan Seribu
            [
                'nama' => 'Rizki Nugraha',
                'nopol' => 'B 80345901 XX',
                'kecamatan' => 'Pulau Seribu Utara',
                'wilayah' => 'Kepulauan Seribu'
            ],
            [
                'nama' => 'Dedy Hermawan',
                'nopol' => 'B 80456012 XX',
                'kecamatan' => 'Pulau Seribu Selatan',
                'wilayah' => 'Kepulauan Seribu'
            ],
        ];

        // Insert data driver
        foreach ($drivers as $driver) {
            // Cari unit_id berdasarkan wilayah
            $unitId = $this->getUnitIdByWilayah($driver['wilayah']);

            if ($unitId) {
                Driver::create([
                    'nama' => $driver['nama'],
                    'nopol' => $driver['nopol'],
                    'unit_id' => $unitId,
                ]);
            }
        }

        // Data Security berdasarkan JSON
        $securities = [
            // Jakarta Pusat
            [
                'nama' => 'Security Gudang JP Sunter Kemayoran',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Security Gudang Material Dampas',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Security Gudang Perkakas dan Solar Pompa Melati',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Security Gudang Pemel Jakarta Pusat',
                'wilayah' => 'Jakarta Pusat'
            ],

            // Jakarta Utara
            [
                'nama' => 'Security Gudang Material Jakarta Utara',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Security Gudang Peralatan Jakarta Utara',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Security Kantor Jakarta Utara',
                'wilayah' => 'Jakarta Utara'
            ],

            // Jakarta Timur
            [
                'nama' => 'Security Gudang Material Jakarta Timur',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Security Gudang Peralatan Jakarta Timur',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Security Kantor Jakarta Timur',
                'wilayah' => 'Jakarta Timur'
            ],

            // Jakarta Selatan
            [
                'nama' => 'Jekson Riwu',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Dimas Sapta Alamsyah',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Rizki Kurnia Adinata',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Budhi Alvino',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Rojih',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Arif Maulana',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Maryadi',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Riki',
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'M. Kamaluddin Zuhdi',
                'wilayah' => 'Jakarta Selatan'
            ],

            // Jakarta Barat
            [
                'nama' => 'Security Gudang Material Jakarta Barat',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Security Gudang Peralatan Jakarta Barat',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Security Kantor Jakarta Barat',
                'wilayah' => 'Jakarta Barat'
            ],

            // Kepulauan Seribu
            [
                'nama' => 'Security Kantor Kepulauan Seribu',
                'wilayah' => 'Kepulauan Seribu'
            ],
            [
                'nama' => 'Security Gudang Kepulauan Seribu',
                'wilayah' => 'Kepulauan Seribu'
            ],
        ];

        // Insert data security
        foreach ($securities as $security) {
            // Cari unit_id berdasarkan wilayah
            $unitId = $this->getUnitIdByWilayah($security['wilayah']);

            if ($unitId) {
                Security::create([
                    'nama' => $security['nama'],
                    'unit_id' => $unitId,
                ]);
            }
        }
    }

    /**
     * Dapatkan unit_id berdasarkan nama wilayah
     */
    private function getUnitIdByWilayah($wilayah)
    {
        $unitMapping = [
            'Jakarta Pusat' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat',
            'Jakarta Utara' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara',
            'Jakarta Timur' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur',
            'Jakarta Selatan' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan',
            'Jakarta Barat' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Barat',
            'Kepulauan Seribu' => 'Suku Dinas Sumber Daya Air Kabupaten Administrasi Kepulauan Seribu',
        ];

        $unitName = $unitMapping[$wilayah] ?? null;
        if ($unitName) {
            $unit = \App\Models\UnitKerja::where('nama', $unitName)->first();
            return $unit ? $unit->id : 1; // Default ke unit ID 1 jika tidak ditemukan
        }

        return 1; // Default unit ID
    }
}
