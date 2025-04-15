<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\OpsiPersetujuan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    public $faker;
    public $kecamatanJakarta;
    public $units;
    public $bagian;
    public $posisi;

    public function __construct()
    {
        $this->units = [
            'Sekretariat' => [
                'kepala' => 'Hendri, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Umum', 'kepala' => 'Putu Riska Komala Putri, ST', 'staf' => ['Ahmad Gunadi'], 'cs' => ['Nisya', 'Insan']],
                    ['nama' => 'Subkelompok Kepegawaian', 'kepala' => 'Ratna Pertiwi, ST'],
                    ['nama' => 'Subkelompok Program dan Pelaporan', 'kepala' => 'Astrid Marzia Damayanti, ST'],
                    ['nama' => 'Subbagian Keuangan', 'kepala' => 'Indra Prabowo, SE'],
                ],
            ],
            'Bidang Pengendalian Banjir dan Drainase' => [
                'kepala' => 'Ika Agustin Ningrum, ST, MPSDA',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Vega Fitria Mutiara Sari, ST, M.T'],
                    ['nama' => 'Subkelompok Pengendalian Banjir', 'kepala' => 'Ericson Indra Pulungan, ST, MT'],
                    ['nama' => 'Subkelompok Drainase', 'kepala' => 'Firmansyah Saputra, ST'],
                ],
                'staf' => [
                    'Dede Heru Rahmat',
                    'Zaenal Abidin',
                    'Rully Narulita',
                    'Satrio Wardanu',
                    'Agis Setyowati',
                    'Andhy Irawan',
                    'Adie Widodo'
                ],
            ],
            'Bidang Geologi, Konservasi Air Baku dan Penyediaan Air Bersih' => [
                'kepala' => 'Nelson, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Elisabeth Tarigan, ST, M.IWM'],
                    ['nama' => 'Subkelompok Geologi dan Konservasi Air Baku', 'kepala' => 'Ikhwan Maulani, ST, MT'],
                    ['nama' => 'Subkelompok Pengendalian dan Penyediaan Air Bersih', 'kepala' => 'Maman Supratman, ST, M.Sc'],
                ],
                'staf' => [
                    'Waryono',
                    'Fathidliyatul Haq',
                    'Hari Krisdihanto',
                    'Ade Nanang'
                ],
            ],
            'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai' => [
                'kepala' => 'Ciko Tricanescoro, ST, M.Sc',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Alfan Widyastanto, ST'],
                    ['nama' => 'Subkelompok Pengendalian Rob dan Pengamanan Pesisir Pantai', 'kepala' => 'Achmad Daeroby, ST'],
                    ['nama' => 'Subkelompok Pengembangan Pesisir Pantai', 'kepala' => 'Yursid Suryanegara, ST'],
                ],
                'staf' => [
                    'Ade Mohammad Yusuf',
                    'Aditya Reyhan',
                    'Gud Open L. Sinaga',
                    'Supeno',
                    'Eddy Sunarjo',
                    'Hasan',
                    'Syaiful Rahman'
                ],
            ],
            'Bidang Pengelolaan Air Limbah' => [
                'kepala' => 'Robby Dwi Mariansyah, ST',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Sarah Dewi Yani, ST, MT'],
                    ['nama' => 'Subkelompok Pembangunan Sarana dan Prasarana Pengelolaan Air Limbah', 'kepala' => 'Heria Suwandi, ST'],
                    ['nama' => 'Subkelompok Peningkatan dan Pengendalian Air Limbah', 'kepala' => 'Glenn Santista, ST'],
                ],
                'staf' => [
                    'Tika Risky Pertiwi',
                    'Nining Ardiyanti Hasan',
                    'Harry Try Hermawan',
                    'Dedi Alfa Julian Purba',
                    'Muhammad Shohih'
                ],
            ],
            'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air' => [
                'kepala' => 'Nur Aprileny, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Niswatin Farika, ST, MT'],
                ],
                'staf' => [
                    'Rido Budi A.',
                    'Agus Sulistyono',
                    'Nadhi Sugandi',
                    'Sujono, S,Sos',
                    'Sutisna',
                    'Firman'
                ],
            ],
            'Unit Peralatan dan Perbekalan Sumber Daya Air' => [
                'kepala' => 'Yose Rizal, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Sofia Ismiati, ST'],
                ],
                'staf' => [
                    'Rinaldi Nugroho',
                    'Achmad Syafi',
                    'Asep Sukamto',
                    'Syamsul Hadi Susetya',
                    'Falaah Nugraha',
                    'Futut Firmanda',
                    'Migie Romataliga',
                    'Niswatul Huwaidah',
                    'Nurman'
                ],
            ],
            'Pusat Data dan Informasi Sumber Daya Air' => [
                'kepala' => 'Drs. Nugraharyadi',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Gde Made Panji Diarsa, S.Kom, M.T.I'],
                ],
                'staf' => [
                    'Damhuri, S.Kom',
                    'Sri Hidayanto',
                    'Rizky Rivandry',
                    'Ade Sudiman',
                    'Arsianto Paulus Patriot'
                ],
            ],
            'Unit Pengadaan Tanah Sumber Daya Air' => [
                'kepala' => 'Roedito Setiawan, SH',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Ibnu Affan, ST'],
                ],
                'staf' => [
                    'Ilan Sadida',
                    'Mustopah',
                    'Muhammad Ridho Jasni',
                    'Bayu Ramadhan',
                    'Mustopah',
                    'Ilan Sadida',
                    'Dandy Idwal Muad'
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat' => [
                'kepala' => 'Adrian Mara Maulana, ST, M.Si',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Evy Af Ida, S.Sos, M.Si'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Dwi Endah Aryaningrum, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Citrin Indriati, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Martineet Felix, ST'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Yusuf Saut Pangibulan, ST, MPSDA'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara' => [
                'kepala' => 'Ir. Ahmad Saipul, MM',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Deny Tri Hendarto, SE'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Apriyani Talaohu, ST, MT'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Yudo Widiatmoko, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Boris Karlop Lumbangaol, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Frans Agustinus Siahaan, ST, MT'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Barat' => [
                'kepala' => 'Purwanti Suryandari, ST, MM',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Eko Wahyono, SE'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Islauni Juliana, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Yopi Maidiza Siregar, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Imam Prasetyo, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Wira Yudha Bhakti, ST, MT'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan' => [
                'kepala' => 'Santo, SST, M.Si',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Siti Nurjannah, M.Si'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Inge Sukma Yupicha, ST, MT'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Paulus Junjung, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Horas Yosua, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Heriyanto, ST'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur' => [
                'kepala' => 'Ir. Abdul Rauf Gaffar, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Herawan, SE, MM'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Fajar Avisena, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Puryanto Palebangan, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Tengku Saugi Zikri, ST'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'John Christian Tarigan, ST'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kabupaten Administrasi Kepulauan Seribu' => [
                'kepala' => 'Mustajab, ST',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Geofrey Rejoice Novena, S.Kom'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Fitri Wiyati, ST, MT'],
                    ['nama' => 'Seksi Pengembangan dan Pengamanan Pesisir Pantai', 'kepala' => 'Wahyu Maulana, ST'],
                    ['nama' => 'Seksi Air Bersih dan Air Limbah', 'kepala' => 'Rezky Arie Pranata, ST'],
                ],
            ],
        ];
        $this->faker = Faker::create('id_ID');
        $this->kecamatanJakarta = [
            // 'Jakarta Pusat' => [
            'Gambir',
            'Tanah Abang',
            'Menteng',
            'Senen',
            'Cempaka Putih',
            'Johar Baru',
            'Kemayoran',
            'Sawah Besar',
            // ],
            // 'Jakarta Utara' => [
            'Penjaringan',
            'Pademangan',
            'Tanjung Priok',
            'Koja',
            'Cilincing',
            'Kelapa Gading',
            // ],
            // 'Jakarta Barat' => [
            'Cengkareng',
            'Grogol Petamburan',
            'Taman Sari',
            'Tambora',
            'Kalideres',
            'Kebon Jeruk',
            'Palmerah',
            'Kembangan',
            // ],
            // 'Jakarta Selatan' => [
            'Kebayoran Baru',
            'Kebayoran Lama',
            'Cilandak',
            'Pesanggrahan',
            'Pasar Minggu',
            'Jagakarsa',
            'Mampang Prapatan',
            'Pancoran',
            'Tebet',
            'Setiabudi',
            // ],
            // 'Jakarta Timur' => [
            'Matraman',
            'Pulogadung',
            'Jatinegara',
            'Duren Sawit',
            'Kramat Jati',
            'Makasar',
            'Cipayung',
            'Ciracas',
            'Pasar Rebo',
            'Cakung',
            // ],
            // 'Kepulauan Seribu' => [
            'Kepulauan Seribu Utara',
            'Kepulauan Seribu Selatan'
            // ]
        ];
        $this->bagian = [
            'Belakang Gudang',
            'Bawah Tanah',
            'Loteng',
            'Timur Gudang',
            'Barat Gudang',
            'Utara Gudang',
            'Selatan Gudang',
            'Pintu Masuk Utama',
            'Pintu Keluar Barang',
            'Area Packing',
            'Area Penerimaan Barang',
            'Area Penyimpanan Rak Tinggi',
            'Area Penyimpanan Rak Rendah',
            'Zona Pendingin',
            'Zona Bebas',
            'Zona Kering',
            'Zona Basah',
            'Ruangan Penyimpanan Kecil',
            'Ruangan Penyimpanan Besar',
            'Lantai Dasar',
            'Area Transit',
            'Gudang Utama',
            'Gudang Samping',
            'Area Forklift',
            'Area Inventaris',
            'Ruang Kontainer',
            'Ruangan Logistik',
            'Koridor Utara',
            'Koridor Selatan',
            'Koridor Timur',
            'Koridor Barat',
            'Area Stok Pallet',
            'Zona Pemisahan Barang',
            'Tempat Penyimpanan Sementara',
            'Ruangan Pengemasan',
            'Ruang Kontrol Stok',
            'Ruang Supervisi Gudang',
            'Tempat Pemindaian Barang',
            'Area Distribusi Internal',
            'Area Inspeksi Barang',
            'Tempat Pengecekan Barang',
            'Zona Pallet Pendingin',
            'Zona Pallet Normal',
        ];
        $this->posisi = [
            'Rak 1',
            'Rak 2',
            'Rak 3',
            'Rak 4',
            'Rak 5',
            'Rak 6',
            'Rak 7',
            'Rak 8',
            'Rak 9',
            'Rak 10',
            'Rak 11',
            'Rak 12',
            'Rak 13',
            'Rak 14',
            'Rak 15',
            'Lemari 1',
            'Lemari 2',
            'Lemari 3',
            'Lemari 4',
            'Lemari 5',
            'Lemari 6',
            'Lemari 7',
            'Lemari 8',
            'Lemari 9',
            'Lemari 10',
            'Laci 1',
            'Laci 2',
            'Laci 3',
            'Laci 4',
            'Laci 5',
            'Laci 6',
            'Laci 7',
            'Laci 8',
            'Laci 9',
            'Laci 10',
            'Zona A1',
            'Zona A2',
            'Zona A3',
            'Zona A4',
            'Zona A5',
            'Zona B1',
            'Zona B2',
            'Zona B3',
            'Zona B4',
            'Zona B5',
            'Pallet 1',
            'Pallet 2',
            'Pallet 3',
            'Pallet 4',
            'Pallet 5',
            'Pallet 6',
            'Pallet 7',
            'Pallet 8',
            'Pallet 9',
            'Pallet 10',
        ];
        // $this->kecamatanJakarta = [
        //     'Jakarta Pusat' => [
        //         'Gambir',
        //         'Tanah Abang',
        //         'Menteng',
        //         'Senen',
        //         'Cempaka Putih',
        //         'Johar Baru',
        //         'Kemayoran',
        //         'Sawah Besar'
        //     ],
        //     'Jakarta Utara' => [
        //         'Penjaringan',
        //         'Pademangan',
        //         'Tanjung Priok',
        //         'Koja',
        //         'Cilincing',
        //         'Kelapa Gading'
        //     ],
        //     'Jakarta Barat' => [
        //         'Cengkareng',
        //         'Grogol Petamburan',
        //         'Taman Sari',
        //         'Tambora',
        //         'Kalideres',
        //         'Kebon Jeruk',
        //         'Palmerah',
        //         'Kembangan'
        //     ],
        //     'Jakarta Selatan' => [
        //         'Kebayoran Baru',
        //         'Kebayoran Lama',
        //         'Cilandak',
        //         'Pesanggrahan',
        //         'Pasar Minggu',
        //         'Jagakarsa',
        //         'Mampang Prapatan',
        //         'Pancoran',
        //         'Tebet',
        //         'Setiabudi'
        //     ],
        //     'Jakarta Timur' => [
        //         'Matraman',
        //         'Pulogadung',
        //         'Jatinegara',
        //         'Duren Sawit',
        //         'Kramat Jati',
        //         'Makasar',
        //         'Cipayung',
        //         'Ciracas',
        //         'Pasar Rebo',
        //         'Cakung'
        //     ],
        //     'Kepulauan Seribu' => [
        //         'Kepulauan Seribu Utara',
        //         'Kepulauan Seribu Selatan'
        //     ]
        // ];
    }
    public function run(): void
    {
        $this->unitSeed();
        $this->lokasiSeed();

        $jenisList = ['umum', 'spare-part', 'material']; // Daftar jenis
        $tipe = 'permintaan'; // Tipe permintaan

        foreach ($this->units as $unitName => $unitData) {
            $unit = UnitKerja::where('nama', $unitName)->first();

            // Ambil semua role untuk unit ini
            $allRoles = User::whereHas('unitKerja', function ($query) use ($unit) {
                $query->where('parent_id', $unit->id)->orWhere('unit_id', $unit->id);
            })
                ->get()
                ->pluck('roles') // Ambil seluruh data role dari relasi
                ->flatten()
                ->unique('id')
                ->pluck('id') // Ambil hanya ID
                ->toArray(); // Konversi ke array

            // Daftar role yang HARUS digunakan untuk jenis 'umum' dalam urutan tetap
            $rolePriorityForUmum = [
                'Customer Services',
                'Penanggung Jawab'
                // 'Kepala Subbagian'
            ];

            // Ambil ID dari role yang sesuai untuk 'umum'
            $roleIdMapping = Role::whereIn('name', $rolePriorityForUmum)
                ->get()
                ->mapWithKeys(function ($role) {
                    return [$role->name => $role->id];
                })
                ->toArray();

            // Susun berdasarkan urutan yang telah ditentukan
            $filteredRolesForUmum = [];
            foreach ($rolePriorityForUmum as $roleName) {
                if (isset($roleIdMapping[$roleName])) {
                    $filteredRolesForUmum[] = $roleIdMapping[$roleName];
                }
            }
            foreach ($jenisList as $jenis) {
                // Tentukan approval roles berdasarkan jenis
                if ($jenis === 'umum') {
                    // Jika jenis 'umum', ambil semua role dalam urutan tetap
                    $approvalRoles = $filteredRolesForUmum;
                } else {
                    // Jika bukan 'umum', pilih 3 role secara acak
                    $approvalRoles = collect($allRoles)
                        ->shuffle()
                        ->take(3)
                        ->toArray();
                }

                // Simpan roles ke $unitData
                $unitData['roles'] = $approvalRoles;

                // Filter role untuk finalizer (ambil dari role yang tidak dipilih untuk approval)
                $availableFinalizerRoles = array_diff($allRoles, $approvalRoles);

                // Pilih secara acak untuk finalizer jika ada yang tersedia
                $finalizerRole = !empty($availableFinalizerRoles) ? Arr::random($availableFinalizerRoles) : null;

                // Simpan konfigurasi persetujuan
                $approvalConfiguration = \App\Models\OpsiPersetujuan::create([
                    'unit_id' => $unit->id,
                    'uuid' => Str::uuid(), // Generate UUID unik
                    'jenis' => $jenis,
                    'tipe' => $tipe,
                    'deskripsi' => "Konfigurasi persetujuan untuk unit $unitName dengan jenis $jenis.",
                    'urutan_persetujuan' => 1, // Urutan persetujuan pertama
                    'cancel_persetujuan' => 2, // Urutan pembatalan kedua
                    'jabatan_penyelesai_id' => $finalizerRole, // Jabatan penyelesaian
                ]);

                // Simpan role untuk setiap opsi persetujuan dalam urutan tetap
                foreach ($approvalRoles as $index => $role) {
                    // Tentukan nilai approval berdasarkan nama role
                    $roleName = array_search($role, $roleIdMapping); // Cari nama role berdasarkan ID

                    // Jika role adalah "Customer Services" atau "Penanggung Jawab", approval = 1, selain itu 0/null
                    // $approvalValue = in_array($roleName, ['Customer Services', 'Penanggung Jawab']) ? 1 : 0;

                    \App\Models\JabatanPersetujuan::create([
                        'opsi_persetujuan_id' => $approvalConfiguration->id,
                        'jabatan_id' => $role,
                        'urutan' => $index + 1,
                        // 'approval' => $approvalValue,
                    ]);
                }
            }
        }
        $jenisList = ['kdo', 'ruangan', 'alat']; // Daftar jenis
        $tipe = 'peminjaman'; // Tipe permintaan

        $rolePriorityForPeminjaman = [
            'Customer Services',
            'Penanggung Jawab'
            // 'Kepala Subbagian'
        ];

        // Ambil ID dari role yang sesuai untuk 'peminjaman'
        $roleIdMapping = Role::whereIn('name', $rolePriorityForPeminjaman)
            ->get()
            ->mapWithKeys(function ($role) {
                return [$role->name => $role->id];
            })
            ->toArray();

        foreach ($this->units as $unitName => $unitData) {
            $unit = UnitKerja::where('nama', $unitName)->first();

            // Ambil semua role untuk unit ini
            $allRoles = User::whereHas('unitKerja', function ($query) use ($unit) {
                $query->where('parent_id', $unit->id)
                    ->orWhere('unit_id', $unit->id);
            })
                ->get()
                ->pluck('roles') // Ambil seluruh data role dari relasi
                ->flatten()
                ->unique('id')
                ->pluck('id') // Ambil hanya ID
                ->toArray(); // Konversi ke array

            // Susun berdasarkan urutan yang telah ditentukan
            $filteredRolesForPeminjaman = [];
            foreach ($rolePriorityForPeminjaman as $roleName) {
                if (isset($roleIdMapping[$roleName])) {
                    $filteredRolesForPeminjaman[] = $roleIdMapping[$roleName];
                }
            }

            // Simpan roles ke $unitData
            $unitData['roles'] = $filteredRolesForPeminjaman;

            // Filter role untuk finalizer (ambil dari role yang tidak dipilih untuk approval)
            $availableFinalizerRoles = array_diff($allRoles, $filteredRolesForPeminjaman);

            // Pilih finalizer secara acak jika ada yang tersedia
            $finalizerRole = !empty($availableFinalizerRoles) ? Arr::random($availableFinalizerRoles) : null;

            foreach ($jenisList as $jenis) {
                // Simpan konfigurasi persetujuan
                $approvalConfiguration = \App\Models\OpsiPersetujuan::create([
                    'unit_id' => $unit->id,
                    'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID unik
                    'jenis' => $jenis,
                    'tipe' => $tipe,
                    'deskripsi' => "Konfigurasi persetujuan untuk unit $unitName dengan jenis $jenis.",
                    'urutan_persetujuan' => 1, // Urutan persetujuan pertama
                    'cancel_persetujuan' => 2, // Urutan pembatalan kedua
                    'jabatan_penyelesai_id' => $finalizerRole, // Jabatan penyelesaian
                ]);

                // Simpan role untuk setiap opsi persetujuan dalam urutan tetap
                foreach ($filteredRolesForPeminjaman as $index => $role) {
                    // Tentukan nilai approval berdasarkan nama role
                    $roleName = array_search($role, $roleIdMapping);

                    // Jika role adalah "Customer Services" atau "Penanggung Jawab", approval = 1, selain itu 0/null
                    // $approvalValue = in_array($roleName, ['Customer Services', 'Penanggung Jawab']) ? 1 : 0;

                    \App\Models\JabatanPersetujuan::create([
                        'opsi_persetujuan_id' => $approvalConfiguration->id,
                        'jabatan_id' => $role,
                        'urutan' => $index + 1,
                        // 'approval' => $approvalValue,
                    ]);
                }
            }
        }

        $jenisList = ['barang']; // Daftar jenis
        $tipe = 'pengiriman'; // Tipe permintaan
        foreach ($this->units as $unitName => $unitData) {
            $unit = UnitKerja::where('nama', $unitName)->first();

            // Ambil semua role untuk unit ini
            $allRoles = User::whereHas('unitKerja', function ($query) use ($unit) {
                $query->where('parent_id', $unit->id)->orWhere('unit_id', $unit->id);
            })
                ->whereHas('roles', function ($role) {
                    return $role->where('name', 'Pemeriksa Barang');
                })
                ->get()
                ->pluck('id') // Ambil hanya ID
                ->toArray();

            $finalizerRole = Arr::random($allRoles);

            foreach ($jenisList as $jenis) {
                // Simpan konfigurasi persetujuan
                $approvalConfiguration = OpsiPersetujuan::create([
                    'unit_id' => $unit->id,
                    'uuid' => Str::uuid(), // Generate UUID unik
                    'jenis' => $jenis,
                    'tipe' => $tipe,
                    'deskripsi' => "Konfigurasi persetujuan untuk unit $unitName dengan jenis $jenis.",
                    'urutan_persetujuan' => 1, // Urutan persetujuan pertama
                    'cancel_persetujuan' => 2, // Urutan pembatalan kedua
                    'user_penyelesai_id' => $finalizerRole, // user penyelesaian
                ]);
            }
        }
    }

    private function lokasiSeed()
    {
        $kecamatanJakarta = collect($this->kecamatanJakarta);
        $units = UnitKerja::whereNull('parent_id')->get();
        foreach ($units as $unit) {
            foreach ($kecamatanJakarta->random(8)->all() as $kecamatan) {
                $lokasi = LokasiStok::create([
                    'unit_id' => $unit->id,
                    'nama' => 'Gudang ' . $kecamatan,
                    'slug' => Str::slug('Gudang ' . $kecamatan),
                    'alamat' => $this->faker->address,
                ]);

                $roleOnce = ['Penerima Barang', 'Penjaga Gudang'];
                foreach ($roleOnce as $item) {
                    User::create([
                        'email_verified_at' => now(),
                        'name' => $this->faker->name(),
                        'unit_id' => $unit->id,
                        'lokasi_id' => $lokasi->id,
                        'email' => Str::lower(str_replace(' ', '_', $item)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $item)) . "%")->count() + 1 . "@email.com",
                        'password' => bcrypt('123'), // Password default
                    ])->roles()->attach(Role::where('name', $item)->first()->id);
                }

                $this->bagianSeed($lokasi);
            }
            // Tambahkan Gudang Umum jika unit adalah Sekretariat dan punya anak Subbagian Umum
            if ($unit->nama === 'Sekretariat') {
                $subbagianUmum = $unit->children()->where('nama', 'Subbagian Umum')->first();

                if ($subbagianUmum) {
                    $lokasiUmum = LokasiStok::create([
                        'unit_id' => $subbagianUmum->id,
                        'nama' => 'Gudang Umum',
                        'slug' => Str::slug('Gudang Umum'),
                        'alamat' => $this->faker->address,
                    ]);

                    // Buat user dengan role Penerima Barang di Gudang Umum
                    $roleOnce = ['Penerima Barang', 'Penjaga Gudang'];
                    foreach ($roleOnce as $item) {
                        User::create([
                            'email_verified_at' => now(),
                            'name' => $this->faker->name(),
                            'unit_id' => $subbagianUmum->id,
                            'lokasi_id' => $lokasiUmum->id,
                            'email' => Str::lower(str_replace(' ', '_', $item)) . (User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $item)) . "%")->count() + 1) . "@email.com",
                            'password' => bcrypt('123'),
                        ])->roles()->attach(Role::where('name', $item)->first()->id);
                    }

                    $this->bagianSeed($lokasiUmum);
                }
            }
        }
    }
    private function bagianSeed($lokasi)
    {

        // foreach ($lokasis as $i => $lokasi) {
        foreach (collect($this->bagian)->random(4)->all() as $bagian) {
            $bag = BagianStok::create([
                'lokasi_id' => $lokasi->id,
                'nama' => $bagian,
            ]);
            $this->posisiSeed($bag);
        }
        // }
    }
    private function posisiSeed($bag)
    {

        foreach (collect($this->posisi)->random(3)->all() as $posisi) { // Iterasi array posisi di setiap bagian
            PosisiStok::create([
                'bagian_id' => $bag->id,
                'nama' => $posisi, // $posisi adalah string
            ]);
        }
    }
    private function unitSeed()
    {
        $units = $this->units;


        $roles = ['Penanggung Jawab', 'Anggota', 'Pejabat Pembuat Komitmen', 'Kasatpel', 'Pejabat Pelaksana Teknis Kegiatan', 'Penerima Barang', 'Penjaga Gudang', 'Pemeriksa Barang', 'Pengurus Barang',  'Kepala Seksi', 'Perencanaan', 'Kepala Subbagian Tata Usaha', 'Kepala Seksi Pemeliharaan', 'Kepala Unit', 'Driver'];

        $superRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);
        $guestRole = Role::firstOrCreate([
            'name' => 'guest',
            'guard_name' => 'web',
        ]);
        $superUser = User::create([
            'email_verified_at' => now(),
            'name' => 'superadmin',
            'email' => 'superadmin@email.com',
            'unit_id' => null,
            'lokasi_id' => null,
            'password' => bcrypt('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $guestUser = User::create([
            'email_verified_at' => now(),
            'name' => 'guest',
            'email' => 'guest@email.com',
            'unit_id' => null,
            'lokasi_id' => null,
            'password' => bcrypt('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $superUser->roles()->attach($superRole->id);
        $guestUser->roles()->attach($guestRole->id);
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        foreach ($units as $unitName => $unitData) {
            // Simpan unit
            $hak = (Str::contains($unitName, ['Sekretariat', 'Bidang', 'Unit', 'Pusat Data'])) ? 1 : 0;
            $unit = UnitKerja::create([
                'nama' => $unitName,
                'kode' => strtoupper(substr(str_replace('Bidang', '', $unitName), 0, 3)), // Membuat kode dari 3 huruf pertama nama unit
                'parent_id' => null, // Unit utama
                'hak' => $hak, // 
                'keterangan' => "Unit $unitName.",
            ]);



            if (Str::contains($unitName, 'Suku Dinas Sumber Daya Air')) {
                // Buat kepala unit utama
                $unitRole = Role::firstOrCreate([
                    // 'name' => 'Kepala Suku Dinas Sumber Daya Air',
                    'name' => 'Kepala Suku Dinas',
                    'guard_name' => 'web',
                ]);
            } else {
                // Buat kepala unit utama
                $unitRole = Role::firstOrCreate([
                    // 'name' => 'Kepala ' . $unitName,
                    'name' => 'Kepala Unit',
                    'guard_name' => 'web',
                ]);
            }
            $unitUser = User::create([
                'email_verified_at' => now(),
                'name' => $unitData['kepala'],
                'unit_id' => $unit->id,
                'email' => Str::lower(str_replace(' ', '_', $unitRole->name)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $unitRole->name)) . "%")->count() + 1 . "@email.com",
                'password' => bcrypt('123'), // Password default
            ]);
            $unitUser->roles()->attach($unitRole->id);
            $roleMulti = ['Pejabat Pelaksana Teknis Kegiatan', 'Pemeriksa Barang', 'Perencanaan'];
            foreach ($roleMulti as $role) {
                for ($i = 1; $i <= 3; $i++) {
                    User::create([
                        'email_verified_at' => now(),
                        'name' => $this->faker->name(),
                        'unit_id' => $unit->id,
                        'email' => Str::lower(str_replace(' ', '_', $role)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $role)) . "%")->count() + 1 . "@email.com",
                        'password' => bcrypt('123'), // Password default
                    ])->roles()->attach(Role::where('name', $role)->first()->id);
                }
            }


            $pemeliharaanExists = collect($unitData['sub_units'])->contains(function ($subUnit) {
                return $subUnit['nama'] === "Seksi Pemeliharaan";
            });

            if (!$pemeliharaanExists) {
                $unitData['sub_units'][] = [
                    'nama' => 'Seksi Pemeliharaan',
                    'kepala' => $this->faker->name, // Provide a default name
                ];
            }
            $tataUsahaExists = collect($unitData['sub_units'])->contains(function ($subUnit) {
                return $subUnit['nama'] === "Subbagian Tata Usaha";
            });

            if (!$tataUsahaExists) {
                $unitData['sub_units'][] = [
                    'nama' => 'Subbagian Tata Usaha',
                    'kepala' => $this->faker->name, // Provide a default name
                ];
            }

            $defaultRoles = [
                'Pejabat Pembuat Komitmen',
                'Kasatpel',
                'Pemeriksa Barang',
                'Pengurus Barang',
            ];

            $roleOnce = $defaultRoles;


            foreach ($roleOnce as $item) {
                User::create([
                    'email_verified_at' => now(),
                    'name' => $this->faker->name(),
                    'unit_id' => $unit->id,
                    'email' => Str::lower(str_replace(' ', '_', $item)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $item)) . "%")->count() + 1 . "@email.com",
                    'password' => bcrypt('123'), // Password default
                ])->roles()->attach(Role::where('name', $item)->first()->id);
            }

            $roleName = 'Penanggung Jawab'; // Role yang akan digunakan

            for ($i = 1; $i <= 5; $i++) { // Loop buat 5 user
                $user = User::create([
                    'email_verified_at' => now(),
                    'name' => $this->faker->name(),
                    'unit_id' => $unit->id ?? null, // Gunakan unit jika ada
                    'email' => Str::lower(str_replace(' ', '_', $roleName)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $roleName)) . "%")->count() + 1 . "@email.com",
                    'password' => bcrypt('123'), // Password default
                ])->roles()->attach(Role::where('name', $roleName)->first()->id);
            }
            // Simpan sub-unit
            foreach ($unitData['sub_units'] as $subUnit) {
                // Cek apakah parent adalah Sekretariat, Bidang, Unit, atau Pusat Data
                $isParentHak = (Str::contains($unitName, ['Sekretariat', 'Bidang', 'Unit', 'Pusat Data']));
                // Hak sub-unit = 1 jika parentnya memenuhi syarat
                $hakSubUnit = ($isParentHak) ? 1 : 0;
                $subUnitEntry =  UnitKerja::create([
                    'nama' => $subUnit['nama'],
                    'kode' => strtoupper(substr(str_replace('Subkelompok', '', $subUnit['nama']), 0, 3)),
                    'parent_id' => $unit->id, // Sub-unit terkait dengan unit
                    'hak' => $hakSubUnit, // 
                    'keterangan' => "Sub-unit $subUnit[nama].",
                ]);
                if (Str::contains($subUnit['nama'], 'Seksi')) {
                    // Buat Jabatan
                    $role = Role::firstOrCreate([
                        // 'name' => 'Kepala ' . $subUnit['nama'],
                        'name' => 'Kepala Seksi',
                        'guard_name' => 'web',
                    ]);
                } else {
                    // Buat Jabatan
                    $role = Role::firstOrCreate([
                        // 'name' => 'Kepala' . $subUnit['nama'],
                        'name' => 'Kepala Subbagian',
                        'guard_name' => 'web',
                    ]);
                }

                // Buat User
                $user = User::create([
                    'email_verified_at' => now(),
                    'name' => $subUnit['kepala'],
                    'unit_id' => $subUnitEntry->id,
                    'email' => Str::lower(str_replace(' ', '_', $role->name)) . User::where('email', 'LIKE', Str::lower(str_replace(' ', '_', $role->name)) . "%")->count() + 1 . "@email.com",
                    'password' => bcrypt('123'), // Password default
                ]);
                $user->roles()->attach($role->id);

                // Tambahkan Driver untuk setiap Sub-unit
                $driverRole = Role::firstOrCreate([
                    'name' => 'Driver',
                    'guard_name' => 'web',
                ]);

                User::create([
                    'email_verified_at' => now(),
                    'name' => $this->faker->name(),
                    'unit_id' => $subUnitEntry->id,
                    'email' => Str::lower(Str::replace(' ', '_', 'driver_' . $subUnit['nama'])) . (User::where('email', 'LIKE', Str::lower(Str::replace(' ', '_', 'driver_' . $subUnit['nama'])) . "%")->count() + 1) . "@email.com",
                    'password' => bcrypt('123'),
                ])->roles()->attach($driverRole->id);

                // Tambahkan Pengurus Barang dari Staf Jika Ada
                if (isset($subUnit['staf'])) {
                    $pengurusBarangRole = Role::firstOrCreate([
                        'name' => 'Pengurus Barang',
                        'guard_name' => 'web',
                    ]);

                    foreach ($subUnit['staf'] as $stafNama) {
                        User::create([
                            'email_verified_at' => now(),
                            'name' => $stafNama, // Gunakan nama yang sudah ada di array staf
                            'unit_id' => $subUnitEntry->id,
                            'email' => Str::lower(str_replace(' ', '_', $stafNama)) . "@email.com",
                            'password' => bcrypt('123'),
                        ])->roles()->attach($pengurusBarangRole->id);
                    }
                }
                // Tambahkan Customer Service dari CS Jika Ada
                if (isset($subUnit['cs'])) {
                    $csRole = Role::firstOrCreate([
                        'name' => 'Customer Services',
                        'guard_name' => 'web',
                    ]);

                    foreach ($subUnit['cs'] as $stafCS) {
                        User::create([
                            'email_verified_at' => now(),
                            'name' => $stafCS, // Gunakan nama yang sudah ada di array staf
                            'unit_id' => $subUnitEntry->id,
                            'email' => Str::lower(str_replace(' ', '_', $stafCS)) . "@email.com",
                            'password' => bcrypt('123'),
                        ])->roles()->attach($csRole->id);
                    }
                }
            }
        }
    }
}
