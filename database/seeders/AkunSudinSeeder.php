<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UnitKerja;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AkunSudinSeeder extends Seeder
{
    public function run(): void
    {
        // Seed semua Sudin
        $this->seedSudinPusat();
        $this->seedSudinUtara();
        $this->seedSudinSelatan();
        $this->seedSudinBarat();
        $this->seedSudinTimur();
        // TODO: Tambahkan method untuk Sudin lainnya
        // $this->seedSudinKepulauanSeribu();
    }

    private function seedSudinPusat(): void
    {
        // Cari unit Jakarta Pusat
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Pusat tidak ditemukan. Pastikan UnitSeeder sudah dijalankan terlebih dahulu.');
        }

        $this->seedSudinAccounts($unit, $this->getPusatData());
    }

    private function getPusatData(): array
    {
        return [
            'wilayah' => 'pusat',
            'kecamatan_mapping' => [
                'Gambir' => 1,
                'Sawah Besar' => 8,
                'Kemayoran' => 7,
                'Senen' => 4,
                'Cempaka Putih' => 5,
                'Menteng' => 3,
                'Tanah Abang' => 2,
                'Johar Baru' => 6,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}{counter}@test.com'],
                'Pembantu Pengurus Barang II' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}{counter}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Adrian Mara Maulana, ST. M.Si', 'nip' => '197503292006041015'],
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Dwi Endah Aryaningrum, ST', 'nip' => '198501172010012022'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Rahmi Agustina, ST', 'nip' => '199008192020122014'],
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Nila Sari, ST', 'nip' => '198404072010012034'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Wawan Hadiyana, ST', 'nip' => '197008272009041001'],
                ['role' => 'Pembantu Pengurus Barang II', 'nama' => 'Mulyanto, ST', 'nip' => '197802162009041005'],
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Citrin Indriati, ST', 'nip' => '198110202010012016'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Mohammad Irfansyah, ST', 'nip' => '198004152009041007'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Herri Hermawan, ST', 'nip' => '198105192009041006'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Karsid', 'nip' => '197503182014121002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Rhefa Fauza Setiani, ST', 'nip' => '199609142022032009'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Gambir', 'nama' => 'Muhamad Imawan, ST', 'nip' => '196710191990081001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Sawah Besar', 'nama' => 'Yusuf Sumardani, ST', 'nip' => '197803032009041004'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kemayoran', 'nama' => 'Supriyadi, ST', 'nip' => '198101052009041002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Senen', 'nama' => 'Zulfahmi, ST', 'nip' => '197501062009041004'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cempaka Putih', 'nama' => 'Muhammad Sahudi, ST', 'nip' => '197911272009041002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Menteng', 'nama' => 'Nawan, SAP', 'nip' => '196803081990061001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Tanah Abang', 'nama' => 'Eli Menawan Sari, ST', 'nip' => '197706172010012012'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Johar Baru', 'nama' => 'Rudy Prasetya, ST', 'nip' => '198010032006041009'],
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Martineet Felix, ST', 'nip' => '198506172010011025'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Devita Octamara', 'nip' => '199710312020122021'],
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Yusuf Saut Pangibulan, ST, MPSDA', 'nip' => '198505252010011023'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Dian Darmaji, ST', 'nip' => '198209252010011030'],
            ]
        ];
    }

    private function seedSudinAccounts(UnitKerja $unit, array $sudinData): void
    {
        $wilayah = $sudinData['wilayah'];
        $kecamatanMapping = $sudinData['kecamatan_mapping'];
        $roleMappings = $sudinData['role_mapping'];
        $users = $sudinData['users'];

        // Track role counts for duplicate roles
        $roleCounters = [];

        foreach ($users as $userData) {
            if (empty($userData['nama']) || empty($userData['nip'])) {
                continue; // Skip empty entries
            }

            // Find role mapping
            $roleMapping = null;
            $kecamatanName = null;
            $kecamatanSlug = null;
            $kecamatanId = null;

            // Check for kecamatan-specific roles
            foreach ($roleMappings as $rolePattern => $mapping) {
                if (isset($mapping['has_kecamatan']) && $mapping['has_kecamatan']) {
                    // Extract kecamatan name from user role
                    if (preg_match('/Ketua Satuan Pelaksana Kecamatan (.+)/', $userData['role'], $matches)) {
                        $kecamatanName = $matches[1];
                        $kecamatanSlug = strtolower(str_replace(' ', '', $kecamatanName));
                        $kecamatanId = $kecamatanMapping[$kecamatanName] ?? null;
                        $roleMapping = $mapping;
                        break;
                    }
                } elseif ($userData['role'] === $rolePattern) {
                    $roleMapping = $mapping;
                    break;
                }
            }

            if (!$roleMapping) {
                continue; // Skip if no mapping found
            }

            // Handle role counters for duplicate roles
            $baseRole = $userData['role'];
            if (isset($roleMapping['has_kecamatan']) && $roleMapping['has_kecamatan']) {
                $baseRole = 'Ketua Satuan Pelaksana Kecamatan'; // Group all kasatpel together
            }

            if (!isset($roleCounters[$baseRole])) {
                $roleCounters[$baseRole] = 1;
            } else {
                $roleCounters[$baseRole]++;
            }

            // Generate email
            $email = $roleMapping['email_template'];
            $email = str_replace('{wilayah}', $wilayah, $email);

            if (isset($roleMapping['has_kecamatan']) && $roleMapping['has_kecamatan']) {
                $email = str_replace('{kecamatan_slug}', $kecamatanSlug, $email);
            }

            // Add counter for duplicate roles
            if (strpos($email, '{counter}') !== false) {
                $email = str_replace('{counter}', $roleCounters[$baseRole], $email);
            }

            $permissionRole = $roleMapping['role'];

            // Tentukan unit berdasarkan role
            $targetUnit = $unit; // Default ke unit utama

            // Untuk role lainnya, cari sub unit yang sesuai
            if (strpos($userData['role'], 'Perencanaan') !== false) {
                $targetUnit = $unit->children()->where('nama', 'LIKE', '%Perencanaan%')->first() ?? $unit;
            } elseif (strpos($userData['role'], 'Tata Usaha') !== false) {
                $targetUnit = $unit->children()->where('nama', 'LIKE', '%Tata Usaha%')->first() ?? $unit;
            } elseif (strpos($userData['role'], 'Pemeliharaan') !== false) {
                $targetUnit = $unit->children()->where('nama', 'LIKE', '%Pemeliharaan%')->first() ?? $unit;
            } elseif (strpos($userData['role'], 'Pembangunan') !== false) {
                $targetUnit = $unit->children()->where('nama', 'LIKE', '%Pembangunan%')->first() ?? $unit;
            } elseif (strpos($userData['role'], 'Pompa') !== false) {
                $targetUnit = $unit->children()->where('nama', 'LIKE', '%Pompa%')->first() ?? $unit;
            }

            $userCreateData = [
                'name' => $userData['nama'],
                'nip' => $userData['nip'],
                'unit_id' => $targetUnit ? $targetUnit->getKey() : null,
                'password' => Hash::make('test@123'),
                'email_verified_at' => now(),
            ];

            // Add kecamatan_id if specified
            if ($kecamatanId) {
                $userCreateData['kecamatan_id'] = $kecamatanId;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                $userCreateData
            );

            // Assign role berdasarkan mapping
            $role = Role::firstOrCreate(['name' => $permissionRole]);
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }

    // Template methods for other Sudin regions
    // Uncomment and populate with actual data when needed

    private function seedSudinUtara(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Utara tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getUtaraData());
    }

    private function getUtaraData(): array
    {
        return [
            'wilayah' => 'utara',
            'kecamatan_mapping' => [
                'Penjaringan' => 9,
                'Pademangan' => 10,
                'Tanjung Priok' => 11,
                'Koja' => 12,
                'Cilincing' => 13,
                'Kelapa Gading' => 14,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}{counter}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Pembantu Pengelola Gudang Material' => ['role' => 'Pengelola Gudang', 'email_template' => 'pgm.{wilayah}@test.com'],
                'Administrasi' => ['role' => 'Administrasi', 'email_template' => 'admin.{wilayah}{counter}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Ir. Ahmad Saipul MM', 'nip' => '196709291996031001'],

                // SEKSI PERENCANAAN
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Apriyani Talaohu, ST, MT', 'nip' => '197604052008042001'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Desni Citra Mumpuni, A.Md', 'nip' => '199512102019032012'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Dadang Darmawan', 'nip' => '196910262009041001'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Sony Marsono', 'nip' => '197709272009041003'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Ririan Safiadi Wahid', 'nip' => '198706262020121007'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Muhammad Fachri Maulvi', 'nip' => '199802072022031009'],

                // BAGIAN TATA USAHA
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Deny Tri Hendarto, SE', 'nip' => '198111092010011017'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Mohamad Suherman Eka Putra', 'nip' => '197710042009041003'],
                ['role' => 'Pembantu Pengelola Gudang Material', 'nama' => 'Sanjaya', 'nip' => '198008022009041005'],
                ['role' => 'Administrasi', 'nama' => 'Evian Nazar Qutub', 'nip' => '80548553'],
                ['role' => 'Administrasi', 'nama' => 'Makhsus Iskandar', 'nip' => '80210241'],
                ['role' => 'Administrasi', 'nama' => 'Dwi Kurnia Sandi', 'nip' => ''],

                // SEKSI PEMELIHARAAN
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Yudo Widiatmoko, ST, MT.', 'nip' => '198608302010011010'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Supiyan', 'nip' => '197907192009041001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sidiq', 'nip' => '196907052009041002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Muhammad Fachri Maulvi', 'nip' => '199802072022031009'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sanjaya', 'nip' => '198008022009041005'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Dewi Marlina, ST', 'nip' => '197603092010012008'],

                // SATUAN PELAKSANA KECAMATAN
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cilincing', 'nama' => 'Ichsan Nasution', 'nip' => '197106011996031000'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kelapa Gading', 'nama' => 'Jhoni Sariyanto Situmorang, ST.', 'nip' => '197706092010011021'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Koja', 'nama' => 'Slamet Riyanto, ST', 'nip' => '197410022009041003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pademangan', 'nama' => 'Dewi Marlina, ST', 'nip' => '197603092010012008'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Penjaringan', 'nama' => 'Pendi, ST', 'nip' => '197305302009041001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Tanjung Priok', 'nama' => 'Neti Heriati, ST', 'nip' => '197901102014122004'],

                // SEKSI PEMBANGUNAN
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Dr. Boris Karlop Lumbangaol, ST, MT.', 'nip' => '197811062010011020'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Supiyan', 'nip' => '197907192009041001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sidiq', 'nip' => '196907052009041002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Muhammad Fachri Maulvi', 'nip' => '199802072022031009'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sanjaya', 'nip' => '198008022009041005'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Dewi Marlina, ST', 'nip' => '197603092010012008'],

                // SEKSI POMPA
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Frans Agustinus Siahaan, ST, MT', 'nip' => '197908222010011022'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Slamet Riyanto, ST', 'nip' => '197410022009041003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Jaenal Abidin', 'nip' => '198107062009041002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Jhoni Sariyanto Situmorang, ST.', 'nip' => '197706092010011021'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Wawan Suwandi', 'nip' => '197509182009041003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Dadang Darmawan', 'nip' => '196910262009041001'],
            ]
        ];
    }

    /*
    private function seedSudinTimur(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Timur tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getTimurData());
    }

    private function getTimurData(): array
    {
        return [
            'wilayah' => 'timur',
            'kecamatan_mapping' => [
                'Matraman' => 33,
                'Pulogadung' => 34,
                'Jatinegara' => 35,
                'Duren Sawit' => 36,
                'Kramat Jati' => 37,
                'Makasar' => 38,
                'Cipayung' => 39,
                'Ciracas' => 40,
                'Pasar Rebo' => 41,
                'Cakung' => 42,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}{counter}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Pembantu Pengelola Gudang Material' => ['role' => 'Pengelola Gudang', 'email_template' => 'pgm.{wilayah}@test.com'],
                'Administrasi' => ['role' => 'Administrasi', 'email_template' => 'admin.{wilayah}{counter}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN (Data Dummy)
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Dr. Budi Santoso, ST, MT', 'nip' => '196801151995031001'],

                // SEKSI PERENCANAAN (Data Dummy)
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Siti Rahayu, ST, MT', 'nip' => '197505102008042002'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Ahmad Firdaus, ST', 'nip' => '198812152019031010'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Maya Sari, A.Md', 'nip' => '199206082020122015'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Rudi Hartono', 'nip' => '197408162009041002'],

                // BAGIAN TATA USAHA (Data Dummy)
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Bambang Sulistyo, SE', 'nip' => '198005122010011018'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Hendra Gunawan', 'nip' => '197912042009041004'],
                ['role' => 'Pembantu Pengelola Gudang Material', 'nama' => 'Eko Purnomo', 'nip' => '198309152009041006'],
                ['role' => 'Administrasi', 'nama' => 'Dewi Kartini', 'nip' => '80657891'],
                ['role' => 'Administrasi', 'nama' => 'Agus Setiawan', 'nip' => '80342156'],

                // SEKSI PEMELIHARAAN (Data Dummy)
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Indra Kusuma, ST, MT', 'nip' => '198207152010011011'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Joko Widodo', 'nip' => '197806192009041003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Rani Mulyani, ST', 'nip' => '198504122010012009'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Yoga Pratama', 'nip' => '199001082020121008'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sri Wahyuni', 'nip' => '198712292014122005'],

                // SATUAN PELAKSANA KECAMATAN (Data Dummy)
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Matraman', 'nama' => 'Andi Wijaya, ST', 'nip' => '197203121996031002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pulogadung', 'nama' => 'Lina Sari, ST', 'nip' => '198006192010012011'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Jatinegara', 'nama' => 'Dedi Firmansyah, ST', 'nip' => '197509082009041005'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Duren Sawit', 'nama' => 'Nina Kurnia, ST', 'nip' => '198201152014122006'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kramat Jati', 'nama' => 'Hadi Suprianto, ST', 'nip' => '197807102009041007'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Makasar', 'nama' => 'Fitri Handayani, ST', 'nip' => '198403222010012013'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cipayung', 'nama' => 'Arief Rahman, ST', 'nip' => '197612142009041008'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Ciracas', 'nama' => 'Sari Dewi, ST', 'nip' => '198508172010012014'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pasar Rebo', 'nama' => 'Toni Hermawan, ST', 'nip' => '197704252009041009'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cakung', 'nama' => 'Rina Marlina, ST', 'nip' => '198711082010012015'],

                // SEKSI PEMBANGUNAN (Data Dummy)
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Fajar Nugroho, ST, MT', 'nip' => '198410052010011024'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Eko Budiono', 'nip' => '197905192009041010'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Wati Susilowati', 'nip' => '198608142010012016'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Heru Santoso', 'nip' => '199203112020121011'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Lisa Permata, ST', 'nip' => '199506282019032017'],

                // SEKSI POMPA (Data Dummy)
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Ridwan Kamil, ST, MT', 'nip' => '198603112010011026'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Adi Suryana', 'nip' => '197704082009041012'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Putri Handayani', 'nip' => '198809202014122018'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Benny Kurniawan', 'nip' => '199108152020121013'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Ratna Sari, ST', 'nip' => '199407182019032019'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Wahyu Pratama', 'nip' => '198502032009041014'],
            ]
        ];
    }
    */

    private function seedSudinTimur(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Timur tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getTimurData());
    }

    private function getTimurData(): array
    {
        return [
            'wilayah' => 'timur',
            'kecamatan_mapping' => [
                'Matraman' => 33,
                'Pulogadung' => 34,
                'Jatinegara' => 35,
                'Duren Sawit' => 36,
                'Kramat Jati' => 37,
                'Makasar' => 38,
                'Cipayung' => 39,
                'Ciracas' => 40,
                'Pasar Rebo' => 41,
                'Cakung' => 42,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}{counter}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Pembantu Pengelola Gudang Material' => ['role' => 'Pengelola Gudang', 'email_template' => 'pgm.{wilayah}@test.com'],
                'Administrasi' => ['role' => 'Administrasi', 'email_template' => 'admin.{wilayah}{counter}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN (Data Dummy)
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Dr. Budi Santoso, ST, MT', 'nip' => '196801151995031001'],

                // SEKSI PERENCANAAN (Data Dummy)
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Siti Rahayu, ST, MT', 'nip' => '197505102008042002'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Ahmad Firdaus, ST', 'nip' => '198812152019031010'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Maya Sari, A.Md', 'nip' => '199206082020122015'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Rudi Hartono', 'nip' => '197408162009041002'],

                // BAGIAN TATA USAHA (Data Dummy)
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Bambang Sulistyo, SE', 'nip' => '198005122010011018'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Hendra Gunawan', 'nip' => '197912042009041004'],
                ['role' => 'Pembantu Pengelola Gudang Material', 'nama' => 'Eko Purnomo', 'nip' => '198309152009041006'],
                ['role' => 'Administrasi', 'nama' => 'Dewi Kartini', 'nip' => '80657891'],
                ['role' => 'Administrasi', 'nama' => 'Agus Setiawan', 'nip' => '80342156'],

                // SEKSI PEMELIHARAAN (Data Dummy)
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Indra Kusuma, ST, MT', 'nip' => '198207152010011011'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Joko Widodo', 'nip' => '197806192009041003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Rani Mulyani, ST', 'nip' => '198504122010012009'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Yoga Pratama', 'nip' => '199001082020121008'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sri Wahyuni', 'nip' => '198712292014122005'],

                // SATUAN PELAKSANA KECAMATAN (Data Dummy)
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Matraman', 'nama' => 'Andi Wijaya, ST', 'nip' => '197203121996031002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pulogadung', 'nama' => 'Lina Sari, ST', 'nip' => '198006192010012011'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Jatinegara', 'nama' => 'Dedi Firmansyah, ST', 'nip' => '197509082009041005'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Duren Sawit', 'nama' => 'Nina Kurnia, ST', 'nip' => '198201152014122006'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kramat Jati', 'nama' => 'Hadi Suprianto, ST', 'nip' => '197807102009041007'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Makasar', 'nama' => 'Fitri Handayani, ST', 'nip' => '198403222010012013'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cipayung', 'nama' => 'Arief Rahman, ST', 'nip' => '197612142009041008'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Ciracas', 'nama' => 'Sari Dewi, ST', 'nip' => '198508172010012014'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pasar Rebo', 'nama' => 'Toni Hermawan, ST', 'nip' => '197704252009041009'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cakung', 'nama' => 'Rina Marlina, ST', 'nip' => '198711082010012015'],

                // SEKSI PEMBANGUNAN (Data Dummy)
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Fajar Nugroho, ST, MT', 'nip' => '198410052010011024'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Eko Budiono', 'nip' => '197905192009041010'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Wati Susilowati', 'nip' => '198608142010012016'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Heru Santoso', 'nip' => '199203112020121011'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Lisa Permata, ST', 'nip' => '199506282019032017'],

                // SEKSI POMPA (Data Dummy)
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Ridwan Kamil, ST, MT', 'nip' => '198603112010011026'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Adi Suryana', 'nip' => '197704082009041012'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Putri Handayani', 'nip' => '198809202014122018'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Benny Kurniawan', 'nip' => '199108152020121013'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Ratna Sari, ST', 'nip' => '199407182019032019'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Wahyu Pratama', 'nip' => '198502032009041014'],
            ]
        ];
    }

    private function seedSudinSelatan(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Selatan tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getSelatanData());
    }

    private function getSelatanData(): array
    {
        return [
            'wilayah' => 'selatan',
            'kecamatan_mapping' => [
                'Kebayoran Baru' => 23,
                'Kebayoran Lama' => 24,
                'Cilandak' => 25,
                'Pesanggrahan' => 26,
                'Pasar Minggu' => 27,
                'Jagakarsa' => 28,
                'Mampang Prapatan' => 29,
                'Pancoran' => 30,
                'Tebet' => 31,
                'Setiabudi' => 32,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Santo, SST, M.Si', 'nip' => '197302211996031001'],

                // SEKSI PERENCANAAN
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Inge Sukma Yupicha', 'nip' => '198403112010012033'],

                // BAGIAN TATA USAHA
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Siti Nurjannah, M.Si', 'nip' => '196806251993032007'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Nasrudin Darmadi', 'nip' => '198210062014121003'],

                // SEKSI PEMELIHARAAN
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Paulus Junjung, ST', 'nip' => '198004142010011032'],

                // SATUAN PELAKSANA KECAMATAN
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cilandak', 'nama' => 'Yansori, ST', 'nip' => '196901312009041001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Jagakarsa', 'nama' => 'Sartono, ST', 'nip' => '197208122009041001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebayoran Baru', 'nama' => 'Sutopo, ST', 'nip' => '197809122009041003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebayoran Lama', 'nama' => 'Andriansyah, ST', 'nip' => '197008011998031007'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Mampang Prapatan', 'nama' => 'Dicky Dwi Prakoso, ST', 'nip' => '199201272015041001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pancoran', 'nama' => 'Agus Bowo Leksono, S.Kom', 'nip' => '198808092011011007'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pasar Minggu', 'nama' => 'Rosi Surya Indah, ST', 'nip' => '197404022014122001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pesanggrahan', 'nama' => 'Chairul Anwar, ST', 'nip' => '196712131990081003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Setiabudi', 'nama' => 'Retno Wulandari, ST', 'nip' => '197904072010012025'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Tebet', 'nama' => 'Hastyanti Hidayat, ST', 'nip' => '198508072010012034'],

                // SEKSI PEMBANGUNAN
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Horas Yosua, ST, MT', 'nip' => '198510262010011014'],

                // SEKSI POMPA
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Heriyanto, ST', 'nip' => '198102102010011023'],
            ]
        ];
    }

    private function seedSudinBarat(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Barat')->first();

        if (!$unit) {
            throw new \Exception('Unit Jakarta Barat tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getBaratData());
    }

    private function getBaratData(): array
    {
        return [
            'wilayah' => 'barat',
            'kecamatan_mapping' => [
                'Cengkareng' => 15,
                'Grogol Petamburan' => 16,
                'Taman Sari' => 17,
                'Tambora' => 18,
                'Kalideres' => 19,
                'Kebon Jeruk' => 20,
                'Palmerah' => 21,
                'Kembangan' => 22,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Ketua Satuan Pelaksana (Plt.) Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Purwanti Suryandari, ST', 'nip' => '197509192001122001'],

                // SEKSI PERENCANAAN
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Islauni Juliana, ST', 'nip' => '198707092010012022'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Maria Alvina Angielica', 'nip' => '199706302020122017'],

                // BAGIAN TATA USAHA
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Eko Wahyono, SE', 'nip' => '197802031998031004'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Mokhamad Zahroni', 'nip' => '199711012020121004'],

                // SEKSI PEMELIHARAAN
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Yopi Naidiza Siregar, ST', 'nip' => '197905222010011016'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Arief Chandra Pamungkas', 'nip' => '199809062020121003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Jaani Kurniawan', 'nip' => '199001262020121016'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Muji Pranoto', 'nip' => '197612262009041002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Sumitra', 'nip' => '197004202009041001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Maria Alvina Angielica', 'nip' => '199706302020122017'],

                // SATUAN PELAKSANA KECAMATAN
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cengkareng', 'nama' => 'Mulyadi, ST', 'nip' => '197205061997031003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Grogol Petamburan', 'nama' => 'Ibnu Affandi, ST', 'nip' => '198112042010011003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Taman Sari', 'nama' => 'Aria Raksa Kusumah, S.Ak', 'nip' => '198004272014121003'],
                ['role' => 'Ketua Satuan Pelaksana (Plt.) Kecamatan Tambora', 'nama' => 'Yopi Naidiza Siregar, ST', 'nip' => '197905222010011016'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebon Jeruk', 'nama' => 'Suprapto, ST', 'nip' => '197804132009041002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kalideres', 'nama' => 'A. Iskandar Zulkarnain, ST', 'nip' => '198507212010011023'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Palmerah', 'nama' => 'Arif Junaidi', 'nip' => '198007122009041007'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kembangan', 'nama' => 'Husnapri Jakhtinihari, ST', 'nip' => '198004242010012029'],

                // SEKSI PEMBANGUNAN
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Imam Prasetyo, ST. MT', 'nip' => '198203062010011029'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Ria Magdalena Simbolon', 'nip' => '198711082020122014'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Ika Nurhafni', 'nip' => '198504262010012032'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Iman Ramadhan', 'nip' => '197509262009041004'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Jeri Widian', 'nip' => '197901292009041001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Hesti Pratiwi', 'nip' => '199508232019032012'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Mukhamad Aqshso', 'nip' => '197601222009041001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Muhammad Tri Edi Saputra', 'nip' => '199705072022031007'],

                // SEKSI POMPA
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'Wira Yudha Bhakti, ST. MT', 'nip' => '198712092010011008'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Thomas Lisdiyantoko', 'nip' => '199608292020121007'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Hesti Pratiwi', 'nip' => '199508232019032012'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Suhaliman', 'nip' => '197804102009041002'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'M usman Harun', 'nip' => '196811201990081001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Mujibul Anwar', 'nip' => '198301032010011011'],
            ]
        ];
    }

    /*

    private function seedSudinKepulauanSeribu(): void
    {
        $unit = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kabupaten Administrasi Kepulauan Seribu')->first();

        if (!$unit) {
            throw new \Exception('Unit Kepulauan Seribu tidak ditemukan.');
        }

        $this->seedSudinAccounts($unit, $this->getKepulauanSeribuData());
    }

    private function getKepulauanSeribuData(): array
    {
        return [
            'wilayah' => 'seribu',
            'kecamatan_mapping' => [
                'Kepulauan Seribu Utara' => 43,
                'Kepulauan Seribu Selatan' => 44,
            ],
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email_template' => 'kasudin.{wilayah}@test.com'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_perencanaan.{wilayah}@test.com'],
                // ... add other role mappings as needed
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
            ],
            'users' => [
                // Add actual user data here
            ]
        ];
    }
    */
}
