<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\User;
use App\Models\ListRab;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\MerkStok;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\SubKegiatan;
use Faker\Factory as Faker;
use App\Models\UraianRekening;
use Illuminate\Database\Seeder;
use App\Models\AktivitasSubKegiatan;
use Illuminate\Support\Facades\File;

class RabSeeder extends Seeder
{



    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(public_path('rab.json'));
        $data = json_decode($json, true);

        foreach ($data as $unit) {
            $unitKerja = UnitKerja::find($unit['id']);
            if (!$unitKerja) {
                continue; // skip jika unit belum ada
            }

            foreach ($unit['rincian'] as $rincian) {
                $program = Program::firstOrCreate(
                    [
                        'kode' => $rincian['Kode Program'] ?? null,
                        'bidang_id' => $unitKerja->id,
                    ],
                    [
                        'program' => $rincian['Program'] ?? '',
                    ]
                );

                $kegiatan = Kegiatan::firstOrCreate(
                    [
                        'kode' => $rincian['Kode Kegiatan'] ?? null,
                        'program_id' => $program->id,
                    ],
                    [
                        'kegiatan' => $rincian['Kegiatan'] ?? '',
                    ]
                );

                $subKegiatan = SubKegiatan::firstOrCreate(
                    [
                        'kode' => $rincian['Kode Sub Kegiatan'] ?? null,
                        'kegiatan_id' => $kegiatan->id,
                    ],
                    [
                        'sub_kegiatan' => $rincian['Sub Kegiatan'] ?? '',
                    ]
                );

                $aktivitas = AktivitasSubKegiatan::firstOrCreate(
                    [
                        'kode' => $rincian['Kode Aktivitas Sub Kegiatan'] ?? null,
                        'sub_kegiatan_id' => $subKegiatan->id,
                    ],
                    [
                        'aktivitas' => $rincian['Aktivitas Sub Kegiatan'] ?? '',
                    ]
                );

                UraianRekening::firstOrCreate(
                    [
                        'kode' => $rincian['Kode Rekening'] ?? null,
                        'aktivitas_sub_kegiatan_id' => $aktivitas->id,
                    ],
                    [
                        'uraian' => $rincian['URAIAN KODE REKENING'] ?? '',
                    ]
                );
            }
        }

        // ... (lanjutan baris lain)




        // $faker = Faker::create('id_ID');

        // for ($i = 0; $i <= 15; $i++) {

        //     $user = User::inRandomOrder()->whereHas('unitKerja', function ($unit) {
        //         return $unit->where('hak', 0);
        //     })->first();


        //     $data = [
        //         'user_id' => $user->id, // atau ambil random dari User::pluck('id')->random()
        //         'program' => $faker->randomElement([
        //             'Program Pembangunan Infrastruktur',
        //             'Program Pengembangan SDM',
        //             'Program Pengadaan Barang/Jasa',
        //             'Program Kesehatan Masyarakat',
        //             'Program Teknologi dan Informasi',
        //         ]),
        //         'nama' => $faker->randomElement([
        //             'Pembangunan Gedung Serbaguna',
        //             'Pengadaan Laptop Guru',
        //             'Renovasi Puskesmas',
        //             'Pelatihan Digitalisasi',
        //             'Pemeliharaan Jalan Desa',
        //         ]),
        //         'sub_kegiatan' => $faker->randomElement([
        //             'Pekerjaan Pondasi',
        //             'Pengadaan Perangkat IT',
        //             'Rehabilitasi Atap',
        //             'Pelatihan Daring',
        //             'Peningkatan Jalan Lingkungan',
        //         ]),
        //         'rincian_sub_kegiatan' => $faker->sentence(6),
        //         'kode_rekening' => $faker->numerify('5##.##.##'),
        //         'lokasi' => $faker->address(),
        //         'mulai' => $faker->dateTimeBetween('-1 month', 'now'),
        //         'selesai' => $faker->dateTimeBetween('now', '+1 year'),
        //         'keterangan' => $faker->paragraph(),
        //     ];

        //     Rab::create($data);
        // }



        // for ($i = 0; $i < 80; $i++) {
        //     $rab = Rab::inRandomOrder()->first();
        //     // $merk = BarangStok::inRandomOrder()->where('jenis_id', 1)->first();
        //     $merk = MerkStok::whereHas('barangStok', function ($barang) {
        //         return $barang->where('jenis_id', 1);
        //     })->inRandomOrder()->first();
        //     ListRab::create([
        //         'rab_id' => $rab->id,
        //         'merk_id' => $merk->id,
        //         'jumlah' => $faker->numberBetween(100, 1000)
        //     ]);
        // }
    }
}
