<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;


class BaseInventaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Seed example Persons
        Person::create([
            'user_id' => 1,
            'nama' => 'Andi Setiawan',
            'nama_nospace' => Str::slug('Andi Setiawan'),
            'jabatan' => 'Manager Operasional',
            'alamat' => 'Jl. Merdeka No. 45, Jakarta',
            'telepon' => '081234567890',
            'email' => 'andi.setiawan@example.com',
            'keterangan' => 'Bertanggung jawab atas operasional harian',
            'status' => 1
        ]);

        Person::create([
            'user_id' => 1,
            'nama' => 'Budi Prasetyo',
            'nama_nospace' => Str::slug('Budi Prasetyo'),
            'jabatan' => 'Kepala Divisi IT',
            'alamat' => 'Jl. Informatika No. 12, Surabaya',
            'telepon' => '081987654321',
            'email' => 'budi.prasetyo@example.com',
            'keterangan' => 'Bertanggung jawab atas sistem informasi perusahaan',
            'status' => 1
        ]);



        // Seeder for History (Riwayat)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('history')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'jumlah' => rand(1, 10),
                'kondisi' => rand(80, 100),
                'kelengkapan' => rand(80, 100),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }

        // Seeder for Financial Records (Keuangan)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('keuangan')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'tipe' => $faker->randomElement(['in', 'out']),
                'keterangan' => $faker->sentence,
                'nominal' => $faker->numberBetween(1000, 10000),
                'status' => 1,
            ]);
        }

        // Seeder for Agendas
        for ($i = 1; $i <= 10; $i++) {
            DB::table('agenda')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tipe' => $faker->randomElement(['mingguan', 'bulanan']),
                'hari' => $faker->numberBetween(1, 7),
                'tanggal' => strtotime('now'),
                'bulan' => $faker->numberBetween(1, 12),
                'tahun' => $faker->numberBetween(2020, 2023),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }

        // Seeder for Journals (Jurnal)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('jurnal')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }
        DB::table('option')->insert([
            'user_id' => 1,
            'kodeaset' => '[nomor]/INV/[tahun]',
            'qr_judul' => 'perusahaan',
            'qr_judul_other' => 'PT SAD',
            'qr_baris1' => 'nama',
            'qr_baris1_other' => 'Joko Wiyono',
            'qr_baris2' => 'kode',
            'qr_baris2_other' => null,
            'scan_qr_history' => 'enabled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
