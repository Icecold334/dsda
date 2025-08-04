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

        // Mapping data yang sama seperti di AkunSudinSeeder
        $unitMappings = [
            'pusat' => $this->getPusatMapping(),
            'utara' => $this->getUtaraMapping(),
            'selatan' => $this->getSelatanMapping(),
            'barat' => $this->getBaratMapping(),
            'timur' => $this->getTimurMapping(),
        ];

        $totalProcessed = 0;
        $totalAssigned = 0;
        $errors = [];

        foreach ($unitMappings as $wilayah => $mapping) {
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

            $result = $this->processUsersForUnit($unit, $mapping, $force);
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

    private function processUsersForUnit($unit, $mapping, $force)
    {
        $processed = 0;
        $assigned = 0;
        $errors = [];
        $roleMappings = $mapping['role_mapping'];
        $kecamatanMapping = $mapping['kecamatan_mapping'];

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
                $roleAssigned = $this->assignRoleToUser($user, $roleMappings, $kecamatanMapping);
                if ($roleAssigned) {
                    $assigned++;
                    $this->line("✓ Assigned role to: {$user->name} ({$user->email})");
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

    private function assignRoleToUser($user, $roleMappings, $kecamatanMapping)
    {
        // Debug info
        $this->line("Processing user: {$user->name} ({$user->email})");

        // Coba identifikasi role berdasarkan email pattern
        $role = $this->identifyRoleFromEmail($user->email, $roleMappings, $kecamatanMapping);

        if (!$role) {
            // Fallback: coba identifikasi dari nama user atau unit
            $role = $this->identifyRoleFromContext($user, $roleMappings);
        }

        if ($role) {
            $this->line("  → Identified role: {$role}");
            $roleModel = Role::firstOrCreate(['name' => $role]);

            // Remove existing roles if force
            if ($this->option('force')) {
                $user->syncRoles([]);
            }

            $user->assignRole($roleModel);
            return true;
        } else {
            $this->line("  → No role identified");
        }

        return false;
    }

    private function identifyRoleFromEmail($email, $roleMappings, $kecamatanMapping)
    {
        if (!$email || strpos($email, '@') === false) {
            return null;
        }

        $emailPrefix = substr($email, 0, strpos($email, '@'));

        // Mapping email prefix ke role
        $emailToRole = [
            'kasudin' => 'Kepala Suku Dinas',
            'kasie_perencanaan' => 'Kepala Seksi',
            'perencanaan' => 'Perencanaan',
            'kasubagtu' => 'Kepala Subbagian Tata Usaha',
            'pb' => 'Pengurus Barang',
            'pgm' => 'Pengurus Barang',
            'admin' => 'Pengurus Barang',
            'kasipemel' => 'Kepala Seksi',
            'p3k' => 'P3K',
            'kasatpel' => 'Kepala Satuan Pelaksana',
            'kasie_pembangunan' => 'Kepala Seksi',
            'kasie_pompa' => 'Kepala Seksi',
        ];

        foreach ($emailToRole as $prefix => $role) {
            if (strpos($emailPrefix, $prefix) === 0) {
                return $role;
            }
        }

        return null;
    }

    private function identifyRoleFromContext($user, $roleMappings)
    {
        // Coba identifikasi dari nama unit atau konteks lain
        $unitName = $user->unitKerja ? $user->unitKerja->nama : '';

        if (strpos($unitName, 'Perencanaan') !== false) {
            return 'Perencanaan';
        } elseif (strpos($unitName, 'Tata Usaha') !== false) {
            return 'Kepala Subbagian Tata Usaha';
        } elseif (strpos($unitName, 'Pemeliharaan') !== false) {
            return 'Kepala Seksi';
        } elseif (strpos($unitName, 'Pembangunan') !== false) {
            return 'Kepala Seksi';
        } elseif (strpos($unitName, 'Pompa') !== false) {
            return 'Kepala Seksi';
        }

        // Default fallback berdasarkan email yang tidak dikenali
        if ($user->email) {
            return 'Pengurus Barang'; // Role default
        }

        return null;
    }

    private function getPusatMapping()
    {
        return [
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang'],
                'Pembantu Pengurus Barang II' => ['role' => 'Pengurus Barang'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi'],
                'Tim Pendukung PPK' => ['role' => 'P3K'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana'],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi'],
            ],
            'kecamatan_mapping' => [
                'Gambir' => 1,
                'Sawah Besar' => 8,
                'Kemayoran' => 7,
                'Senen' => 4,
                'Cempaka Putih' => 5,
                'Menteng' => 3,
                'Tanah Abang' => 2,
                'Johar Baru' => 6,
            ]
        ];
    }

    private function getUtaraMapping()
    {
        return [
            'role_mapping' => [
                'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas'],
                'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi'],
                'Staf Seksi Perencanaan' => ['role' => 'Perencanaan'],
                'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha'],
                'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang'],
                'Pembantu Pengelola Gudang Material' => ['role' => 'Pengurus Barang'],
                'Administrasi' => ['role' => 'Pengurus Barang'],
                'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi'],
                'Tim Pendukung PPK' => ['role' => 'P3K'],
                'Ketua Satuan Pelaksana Kecamatan {kecamatan}' => ['role' => 'Kepala Satuan Pelaksana'],
                'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi'],
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi'],
            ],
            'kecamatan_mapping' => [
                'Penjaringan' => 9,
                'Pademangan' => 10,
                'Tanjung Priok' => 11,
                'Koja' => 12,
                'Cilincing' => 13,
                'Kelapa Gading' => 14,
            ]
        ];
    }

    private function getSelatanMapping()
    {
        return [
            'role_mapping' => [
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi'],
            ],
            'kecamatan_mapping' => [
                'Setiabudi' => 32,
            ]
        ];
    }

    private function getBaratMapping()
    {
        return [
            'role_mapping' => [
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi'],
            ],
            'kecamatan_mapping' => [
                'Kembangan' => 22,
            ]
        ];
    }

    private function getTimurMapping()
    {
        return [
            'role_mapping' => [
                'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi'],
            ],
            'kecamatan_mapping' => [
                'Cakung' => 42,
            ]
        ];
    }
}
