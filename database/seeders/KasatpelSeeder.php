<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class KasatpelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatanJakarta = [
            [
                'Jakarta Pusat',
                ['Gambir', 'Tanah Abang', 'Menteng', 'Senen', 'Cempaka Putih', 'Johar Baru', 'Kemayoran', 'Sawah Besar'],
            ],
            [
                'Jakarta Utara',
                ['Penjaringan', 'Pademangan', 'Tanjung Priok', 'Koja', 'Cilincing', 'Kelapa Gading'],
            ],
            [
                'Jakarta Barat',
                ['Cengkareng', 'Grogol Petamburan', 'Taman Sari', 'Tambora', 'Kalideres', 'Kebon Jeruk', 'Palmerah', 'Kembangan'],
            ],
            [
                'Jakarta Selatan',
                ['Kebayoran Baru', 'Kebayoran Lama', 'Cilandak', 'Pesanggrahan', 'Pasar Minggu', 'Jagakarsa', 'Mampang Prapatan', 'Pancoran', 'Tebet', 'Setiabudi'],
            ],
            [
                'Jakarta Timur',
                ['Matraman', 'Pulogadung', 'Jatinegara', 'Duren Sawit', 'Kramat Jati', 'Makasar', 'Cipayung', 'Ciracas', 'Pasar Rebo', 'Cakung'],
            ],
            [
                'Kepulauan Seribu',
                ['Kepulauan Seribu Utara', 'Kepulauan Seribu Selatan'],
            ],
        ];

        $role = Role::firstOrCreate([
            'name' => 'Kepala Satuan Pelaksana',
            'guard_name' => 'web',
        ]);

        foreach ($kecamatanJakarta as [$kota, $kecamatans]) {
            foreach ($kecamatans as $kecamatan) {
                $unit = UnitKerja::where('nama', 'like', "%$kota%")->first();
                $kecamatan = Kecamatan::create(['unit_id' => $unit->id, 'kecamatan' => $kecamatan]);

                if (!$unit) {
                    continue;
                }

                if (Str::contains($unit->nama, 'Seribu')) {
                    $sudin = 'seribu';
                } else {

                    $sudin = Str::lower(str_replace('Suku Dinas Sumber Daya Air Kota Administrasi Jakarta ', '', $unit->nama));
                }


                User::firstOrCreate([
                    'email' => "kasatpel.{$sudin}." . Str::lower(str_replace(' ', '', $kecamatan->kecamatan)) . "@test.com",
                ], [
                    'email_verified_at' => now(),
                    'kecamatan_id' => $kecamatan->id,
                    'name' => fake('id_ID')->name(),
                    'unit_id' => $unit->id,
                    'password' => bcrypt('test@123'),
                ])->roles()->syncWithoutDetaching([$role->id]);;
            }
        }
    }
}
