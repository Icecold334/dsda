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
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Agus Supriyanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Bambang Riyanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Eko Sugianto',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Arga Seftiyan Wijaya',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Bambang Sunarto',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Suryanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Pusat'
            ],

            // Sudin SDA Utara
            [
                'nama' => 'Hisyam Anshori',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Absalon Wahyudin Tarihoran',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Saripudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Dadang',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Syarifudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Diding',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Satria',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Margono',
                'nopol' => null,
                'wilayah' => 'Jakarta Utara'
            ],

            // Sudin SDA Timur (dummy)
            [
                'nama' => 'Ahmad Faisal',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Andi Santoso',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Mustang Edward',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'M.Sandi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Madih',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Try Hardono',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Rakhman Hadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ahmad Puadih',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Mohammad Toha',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Yusup Efendi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Wacim',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Suwardi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Josrin Manalu',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Iwan Pratama',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Uweng Firdaus',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Abdullah',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ricky Adol',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Deni S Susilo',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Slamet',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Roy Matroji',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Agus Wahidin',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Abdul Kodir',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Adi Hatta',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Afrizal Muslim',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Agus Budhianto',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ahmad Muhajir',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ahmad Nur Hafid',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ahmad Saiful',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Andi Yudha Pamungkas T A',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Andri',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Anwar Hilman',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Aries Dharmawan',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Aris Sugito',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Armain',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Asep Panji Setiawan',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Bambang Cahyono',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Bobby Rakasiwi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Dawud',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Hendi Suhendi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Hendra',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Idham Maulana Sidik',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ilhamsyah Tambunan',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Iqbal Wahyudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Kardi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'M. Bambang Sudirman',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'M.T Rakhmatan Segonang',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Maman Suparman',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Maradona',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Muhamad Soleh',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Mukhridin',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Parjono',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Reza Imam Cahyadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Rohiyat',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ronel Pangaribuan',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Sarjumanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Sastra Iskandar',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Setiyono',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Subagdo',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Sugiman',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Taufik Hidayat',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Wahyudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Windhu Suryo Wibowo',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Yusup Efendi',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Zulkifli Sinaga',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Rohmansyah',
                'nopol' => null,
                'wilayah' => 'Jakarta Timur'
            ],

            // Sudin SDA Selatan
            [
                'nama' => 'Supriyadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Hari Triatna',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Dedi Andriansah',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Dimas Aditya Putra',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Ferly Sormin',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Sidik',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Jasno',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Zulian Agus',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Maulana Malik',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'M. Fadillah Tigana',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Rian Saputra',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Cosmas Silaen',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Wawan',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Marhali',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Muhamad Topik',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Didit Bastian',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Andri Sentosa',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Jatmiko Nugroho',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Hairudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Jutidharo Endro',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Abdol Rouf',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],
            [
                'nama' => 'Aji Sekar Bawono',
                'nopol' => null,
                'wilayah' => 'Jakarta Selatan'
            ],

            // Sudin SDA Barat
            [
                'nama' => 'Abdul Mutholib',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'M Agus Saleh',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Muhadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'William Andreas Wuisan',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Daman Huri',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Feri Irawan',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Guntur',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Hadi Sumarmo',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Yusri Andani',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Ely Johan',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Edi Sismanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Usep Matius',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Adi Sukarjo',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Syaifulloh',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Nuri Hendriansyah',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Satria Bahari',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Cupriyanto',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Encim',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Arif Supanji',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Saripudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Ahmad Hasyim Baihaqi Y',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Jhon Edward G',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'M. Ihrim Hadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Budi Sugiarto',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Endang Supriatna',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'M Firmansyah',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Johan Wahyudin',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Fahrus Salam',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Jonatan Sembiring',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Suryadi',
                'nopol' => null,
                'wilayah' => 'Jakarta Barat'
            ],
            // Sudin SDA Kepulauan Seribu (dummy)
            [
                'nama' => 'Rizki Nugraha',
                'nopol' => null,
                'wilayah' => 'Kepulauan Seribu'
            ],
            [
                'nama' => 'Dedy Hermawan',
                'nopol' => null,
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

            // jakarta pusat
            [
                'nama' => 'Nandang Suhada',
                'wilayah' => 'Jakarta Pusat'
            ],
            [
                'nama' => 'Paryono',
                'wilayah' => 'Jakarta Pusat'
            ],
            ['nama' => 'Ananda fajar Kurniawan ', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Ari Afrial', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Ahmad Kurniyanto', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Anggi Hasanudin ', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Abdul Rachman', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Dodi Wijaya', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Ilham Ahmad', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'M. Fajri Kiswanto', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Siti Nursadiah', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Tri Handoko Putih', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Vicky Ali Bachtiar', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Willy jafeth Marianus', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Sain muhamad', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Agung sri Sukrisno', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Ahmad Bahrudin', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Danu Satriyo', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Moh Setiawan', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Sahrul Anwar', 'wilayah' => 'Jakarta Pusat'],
            ['nama' => 'Yudik Kiswanto', 'wilayah' => 'Jakarta Pusat'],
            // Jakarta Utara
            [
                'nama' => 'Anton Suherman',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Darfian',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Hendra Saipul Anwar',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Junaidi',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Mohamad Wahet',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Muhamad Fadli Elwuar',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Sulaeman',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Tarmidi',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Tedy Supriyanto',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Suparno',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Amdoro',
                'wilayah' => 'Jakarta Utara'
            ],
            [
                'nama' => 'Hartarto',
                'wilayah' => 'Jakarta Utara'
            ],

            // Jakarta Timur
            [
                'nama' => 'Heru Purwanto',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Agus Supriatna',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Djunaedi Kurnia',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Endang Kusmana',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Hidayat',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Fery Tiwa',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Rusmantoro',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Tri Cahyana',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Zakaria',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Casno',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Ade Suseno',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Kholil As\'ari',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Suhendra',
                'wilayah' => 'Jakarta Timur'
            ],
            [
                'nama' => 'Achmad Sofiyan',
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
                'nama' => 'Rojali',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Surdi Adam',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Khoirul',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Bambang Suharjo',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Kukuh Kuncoro',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Jhony Satria Mirza',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Umar Sumantri',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Majuk',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Miskar',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Agus Mawardin',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Arfan Faisal',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Fitra Al Ramadhan',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Adliyas',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Ubaydillah',
                'wilayah' => 'Jakarta Barat'
            ],
            [
                'nama' => 'Toton Fhatoni',
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
