<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-roles 
                           {--force : Force reassign roles even if user already has roles}
                           {--unit= : Assign roles only for specific unit (wilayah: pusat, utara, selatan, barat, timur)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign roles to users based on their unit and position mapping from AkunSudinSeeder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting role assignment for users...');

        $force = $this->option('force');
        $unitFilter = $this->option('unit');

        // Import semua data dari AkunSudinSeeder
        $seederData = [
            'pusat' => $this->getPusatData(),
            'utara' => $this->getUtaraData(),
            'selatan' => $this->getSelatanData(),
            'barat' => $this->getBaratData(),
            'timur' => $this->getTimurData(),
        ];

        $totalProcessed = 0;
        $totalAssigned = 0;
        $errors = [];

        foreach ($seederData as $wilayah => $data) {
            // Skip jika ada filter unit dan tidak sesuai
            if ($unitFilter && $unitFilter !== $wilayah) {
                continue;
            }

            $this->info("Processing {$wilayah} region...");

            // Cari unit kerja berdasarkan wilayah
            $unit = $this->findUnitByWilayah($wilayah);
            if (!$unit) {
                $this->warn("Unit for {$wilayah} not found, skipping...");
                continue;
            }

            $result = $this->processUsersForUnit($unit, $data, $force);
            $totalProcessed += $result['processed'];
            $totalAssigned += $result['assigned'];
            $errors = array_merge($errors, $result['errors']);
        }

        // Summary
        $this->info('=== Assignment Summary ===');
        $this->info("Total users processed: {$totalProcessed}");
        $this->info("Total roles assigned: {$totalAssigned}");

        if (count($errors) > 0) {
            $this->warn("Errors encountered: " . count($errors));
            foreach ($errors as $error) {
                $this->error($error);
            }
        }

        $this->info('Role assignment completed!');
    }

    private function findUnitByWilayah($wilayah)
    {
        $unitNames = [
            'pusat' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat',
            'utara' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara',
            'selatan' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan',
            'barat' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Barat',
            'timur' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur',
        ];

        return UnitKerja::where('nama', $unitNames[$wilayah] ?? '')->first();
    }

    private function processUsersForUnit($unit, $seederData, $force)
    {
        $processed = 0;
        $assigned = 0;
        $errors = [];
        $roleMappings = $seederData['role_mapping'];
        $kecamatanMapping = $seederData['kecamatan_mapping'];
        $seederUsers = $seederData['users'];

        // Ambil semua user dari unit ini dan sub-unitnya
        $users = User::where('unit_id', $unit->id)
            ->orWhereIn('unit_id', $unit->children()->pluck('id'))
            ->get();

        foreach ($users as $user) {
            $processed++;

            // Skip jika user sudah punya role dan tidak force
            if (!$force && $user->roles()->count() > 0) {
                continue;
            }

            try {
                $roleAssigned = $this->assignRoleToUserFromSeederData($user, $seederUsers, $roleMappings, $kecamatanMapping);
                if ($roleAssigned) {
                    $assigned++;
                    $this->line("✓ Assigned role to: {$user->name} ({$user->email})");
                } else {
                    $this->line("- No matching data found for: {$user->name} ({$user->email})");
                }
            } catch (\Exception $e) {
                $errors[] = "Error assigning role to {$user->name}: " . $e->getMessage();
            }
        }

        return [
            'processed' => $processed,
            'assigned' => $assigned,
            'errors' => $errors
        ];
    }

    private function assignRoleToUserFromSeederData($user, $seederUsers, $roleMappings, $kecamatanMapping)
    {
        // Debug info
        $this->line("Processing user: {$user->name} (NIP: {$user->nip}) ({$user->email})");

        // Cari user di data seeder berdasarkan NIP atau nama
        $matchedSeederUser = null;

        foreach ($seederUsers as $seederUser) {
            // Match berdasarkan NIP (prioritas utama)
            if (!empty($user->nip) && !empty($seederUser['nip']) && $user->nip === $seederUser['nip']) {
                $matchedSeederUser = $seederUser;
                $this->line("  → Matched by NIP: {$seederUser['nip']}");
                break;
            }

            // Match berdasarkan nama (case insensitive, cleanup spaces)
            $userName = trim(strtolower($user->name));
            $seederUserName = trim(strtolower($seederUser['nama']));

            if ($userName === $seederUserName) {
                $matchedSeederUser = $seederUser;
                $this->line("  → Matched by name: {$seederUser['nama']}");
                break;
            }
        }

        if (!$matchedSeederUser) {
            return false;
        }

        // Ambil role dari data seeder
        $userRole = $matchedSeederUser['role'];
        $this->line("  → Seeder role: {$userRole}");

        // Cari mapping role untuk mendapatkan permission role
        $permissionRole = null;

        foreach ($roleMappings as $rolePattern => $roleMapping) {
            // Handle role dengan kecamatan
            if (isset($roleMapping['has_kecamatan']) && $roleMapping['has_kecamatan']) {
                // Extract kecamatan dari role
                if (preg_match('/Ketua Satuan Pelaksana Kecamatan (.+)/', $userRole, $matches)) {
                    $kecamatanName = $matches[1];
                    $expectedPattern = str_replace('{kecamatan}', $kecamatanName, $rolePattern);
                    if ($userRole === $expectedPattern) {
                        $permissionRole = $roleMapping['role'];
                        $this->line("  → Mapped role (with kecamatan): {$permissionRole}");
                        break;
                    }
                }
            } else {
                // Role biasa tanpa kecamatan
                if ($userRole === $rolePattern) {
                    $permissionRole = $roleMapping['role'];
                    $this->line("  → Mapped role: {$permissionRole}");
                    break;
                }
            }
        }

        if (!$permissionRole) {
            $this->line("  → No role mapping found for: {$userRole}");
            return false;
        }

        // Assign role
        $roleModel = Role::firstOrCreate(['name' => $permissionRole]);

        // Remove existing roles if force
        if ($this->option('force')) {
            $user->syncRoles([]);
        }

        $user->assignRole($roleModel);
        return true;
    }

    private function getPusatData()
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

    private function getUtaraData()
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
                'Pembantu Pengelola Gudang Material' => ['role' => 'Pengurus Barang', 'email_template' => 'pgm.{wilayah}@test.com'],
                'Administrasi' => ['role' => 'Pengurus Barang', 'email_template' => 'admin.{wilayah}{counter}@test.com'],
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

    private function getSelatanData()
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
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
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

    private function getBaratData()
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
                'Admin Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'apb1.{wilayah}@test.com'],
                'Admin Pengurus Barang II' => ['role' => 'Pengurus Barang', 'email_template' => 'apb2.{wilayah}@test.com'],
                'Admin Gudang I Mercu Buana' => ['role' => 'Pengurus Barang', 'email_template' => 'ag1.mercubuana.{wilayah}@test.com'],
                'Admin Gudang II Mercu Buana' => ['role' => 'Pengurus Barang', 'email_template' => 'ag2.mercubuana.{wilayah}@test.com'],
                'Admin Gudang I Posko Kembangan' => ['role' => 'Pengurus Barang', 'email_template' => 'ag1.poskokembangan.{wilayah}@test.com'],
                'Admin Gudang II Posko Kembangan' => ['role' => 'Pengurus Barang', 'email_template' => 'ag2.poskokembangan.{wilayah}@test.com'],
                'Admin Gudang I Pos Pengumben' => ['role' => 'Pengurus Barang', 'email_template' => 'ag1.pospengumben.{wilayah}@test.com'],
                'Admin Gudang II Pos Pengumben' => ['role' => 'Pengurus Barang', 'email_template' => 'ag2.pospengumben.{wilayah}@test.com'],
                'Admin Gudang Perumnas' => ['role' => 'Pengurus Barang', 'email_template' => 'ag.perumnas.{wilayah}@test.com'],
                'Admin Gudang Tomang Barat' => ['role' => 'Pengurus Barang', 'email_template' => 'ag.tomangbarat.{wilayah}@test.com'],
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
                ['role' => 'Admin Pengurus Barang I', 'nama' => 'Noval Kurnain', 'nip' => '80028371'],
                ['role' => 'Admin Pengurus Barang II', 'nama' => 'Elsa Puty Maulidia', 'nip' => '80167071'],
                ['role' => 'Admin Gudang I Mercu Buana', 'nama' => 'Aminullah', 'nip' => '80030480'],
                ['role' => 'Admin Gudang II Mercu Buana', 'nama' => 'Dodi Pramana Putra', 'nip' => '80243518'],
                ['role' => 'Admin Gudang I Posko Kembangan', 'nama' => 'Umar', 'nip' => '80095225'],
                ['role' => 'Admin Gudang II Posko Kembangan', 'nama' => 'Ahmad Mawardy', 'nip' => '80239907'],
                ['role' => 'Admin Gudang I Pos Pengumben', 'nama' => 'Agus Mawardin', 'nip' => '80450116'],
                ['role' => 'Admin Gudang II Pos Pengumben', 'nama' => 'Arfan Faisal', 'nip' => '80243446'],
                ['role' => 'Admin Gudang Perumnas', 'nama' => 'Fitra Al Ramadhan', 'nip' => '80341567'],
                ['role' => 'Admin Gudang Tomang Barat', 'nama' => 'Agus Mawardin', 'nip' => '80450116'],

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
                ['role' => 'Tim Pendukung PPK', 'nama' => 'M Usman Harun', 'nip' => '196811201990081001'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Mujibul Anwar', 'nip' => '198301032010011011'],
            ]
        ];
    }

    private function getTimurData()
    {
        return [
            'wilayah' => 'timur',
            'kecamatan_mapping' => [
                'Matraman' => 33,
                'Pulo Gadung' => 34,
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
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email_template' => 'perencanaan.{wilayah}@test.com'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email_template' => 'kasubagtu.{wilayah}@test.com'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email_template' => 'pb.{wilayah}@test.com'],
                'Admin Gudang I' => ['role' => 'Pengurus Barang', 'email_template' => 'admin_gudang1.{wilayah}@test.com'],
                'Admin Gudang II' => ['role' => 'Pengurus Barang', 'email_template' => 'admin_gudang2.{wilayah}@test.com'],
                'Admin Gudang III' => ['role' => 'Pengurus Barang', 'email_template' => 'admin_gudang3.{wilayah}@test.com'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasipemel.{wilayah}@test.com'],
                'Tim Pendukung PPK' => ['role' => 'P3K', 'email_template' => 'p3k.{wilayah}{counter}@test.com'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana', 'email_template' => 'kasatpel.{wilayah}.{kecamatan_slug}@test.com', 'has_kecamatan' => true],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pembangunan.{wilayah}@test.com'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email_template' => 'kasie_pompa.{wilayah}@test.com'],
            ],
            'users' => [
                // PIMPINAN
                ['role' => 'Kepala Suku Dinas', 'nama' => 'Abdul Rauf Gaffar', 'nip' => '196912101998031008'],

                // SEKSI PERENCANAAN
                ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Fajar Avisena', 'nip' => '197811182010011019'],
                ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Krista Wulansari', 'nip' => '198602242010012034'],

                // BAGIAN TATA USAHA
                ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Herawan', 'nip' => '197410021996031002'],
                ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Reza Perdana Kameswara', 'nip' => '197712122009041003'],
                ['role' => 'Admin Gudang I', 'nama' => 'Achmad Zulkifli', 'nip' => '80297938'],
                ['role' => 'Admin Gudang II', 'nama' => 'Mufli Ramsi', 'nip' => '80153456'],
                ['role' => 'Admin Gudang III', 'nama' => 'Dea Rizky Pradnya', 'nip' => '80433247'],

                // SEKSI PEMELIHARAAN
                ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Puryanto Palebangan', 'nip' => '198111052010011018'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Dian Kartika Eka S', 'nip' => '198103182010012022'],

                // SATUAN PELAKSANA KECAMATAN
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cakung', 'nama' => 'Dian Nur Cahyono', 'nip' => '198307272010011039'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Cipayung', 'nama' => 'Dian Kartika Eka S', 'nip' => '198103182010012022'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Ciracas', 'nama' => 'Yulia Indah', 'nip' => '198707162010012028'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Duren Sawit', 'nama' => 'Achmad Dody Firmansyah', 'nip' => '197103241998031006'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Jatinegara', 'nama' => 'Robby Triawan', 'nip' => '197903282010011014'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Kramat Jati', 'nama' => 'Muchlis', 'nip' => '197010161998031006'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Makasar', 'nama' => 'Nurdin', 'nip' => '197810112009041001'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Matraman', 'nama' => 'Sugeng Sugiono', 'nip' => '196803231995031002'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pasar Rebo', 'nama' => 'Nana Juhana', 'nip' => '196809271995031003'],
                ['role' => 'Ketua Satuan Pelaksana Kecamatan Pulo Gadung', 'nama' => 'Didi Rusdiana', 'nip' => '197607252009041001'],

                // SEKSI PEMBANGUNAN
                ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Tengku Saugi Zikri', 'nip' => '198501312010011012'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Yulia Indah', 'nip' => '198707162010012028'],

                // SEKSI POMPA
                ['role' => 'Kepala Seksi Pompa', 'nama' => 'John Christian Tarigan', 'nip' => '196911102014121003'],
                ['role' => 'Tim Pendukung PPK', 'nama' => 'Krista Wulansari', 'nip' => '198602242010012034'],
            ]
        ];
    }
}
