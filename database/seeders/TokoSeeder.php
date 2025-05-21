<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $tokos = [
        //     [
        //         'nama' => 'Toko Elektronik Canggih',
        //         'alamat' => 'Jl. Teknologi No. 20, Bandung',
        //         'telepon' => '022-1234567',
        //         'email' => 'elektronik.canggih@example.com',
        //         'petugas' => 'Andi',
        //         'keterangan' => 'Pusat teknologi dan gadget terbaru',
        //     ],
        //     [
        //         'nama' => 'Bengkel Otomotif Modern',
        //         'alamat' => 'Jl. Mekanik No. 15, Semarang',
        //         'telepon' => '024-9876543',
        //         'email' => 'otomotif.modern@example.com',
        //         'petugas' => 'Budi',
        //         'keterangan' => 'Spesialis kendaraan roda empat dan dua',
        //     ],
        //     [
        //         'nama' => 'Sentra Furnitur Elegan',
        //         'alamat' => 'Jl. Interior No. 9, Surabaya',
        //         'telepon' => '031-5554321',
        //         'email' => 'furnitur.elegan@example.com',
        //         'petugas' => 'Citra',
        //         'keterangan' => 'Distributor furnitur rumah dan kantor',
        //     ],
        //     [
        //         'nama' => 'Toko Fashion Hits',
        //         'alamat' => 'Jl. Mode No. 7, Yogyakarta',
        //         'telepon' => '0274-123456',
        //         'email' => 'fashion.hits@example.com',
        //         'petugas' => 'Dewi',
        //         'keterangan' => 'Supplier pakaian trendy dan kekinian',
        //     ],
        //     [
        //         'nama' => 'Toko Buku Pintar',
        //         'alamat' => 'Jl. Literasi No. 1, Malang',
        //         'telepon' => '0341-678901',
        //         'email' => 'buku.pintar@example.com',
        //         'petugas' => 'Eka',
        //         'keterangan' => 'Pusat buku dan alat tulis sekolah',
        //     ],
        //     [
        //         'nama' => 'Sentra Kuliner Nusantara',
        //         'alamat' => 'Jl. Rasa No. 3, Makassar',
        //         'telepon' => '0411-876543',
        //         'email' => 'kuliner.nusantara@example.com',
        //         'petugas' => 'Fajar',
        //         'keterangan' => 'Distributor makanan khas nusantara',
        //     ],
        //     [
        //         'nama' => 'Pusat Elektronik Terbaru',
        //         'alamat' => 'Jl. Digital No. 8, Jakarta',
        //         'telepon' => '021-3334445',
        //         'email' => 'elektronik.terbaru@example.com',
        //         'petugas' => 'Gilang',
        //         'keterangan' => 'Pusat peralatan elektronik dan gadget',
        //     ],
        //     [
        //         'nama' => 'Toko Perkakas Teknik',
        //         'alamat' => 'Jl. Besi No. 10, Solo',
        //         'telepon' => '0271-654321',
        //         'email' => 'perkakas.teknik@example.com',
        //         'petugas' => 'Hendra',
        //         'keterangan' => 'Supplier alat teknik dan mesin industri',
        //     ],
        //     [
        //         'nama' => 'Toko Sepeda Aktif',
        //         'alamat' => 'Jl. Pedal No. 5, Bandung',
        //         'telepon' => '022-8765432',
        //         'email' => 'sepeda.aktif@example.com',
        //         'petugas' => 'Iwan',
        //         'keterangan' => 'Spesialis sepeda dan perlengkapannya',
        //     ],
        //     [
        //         'nama' => 'Gudang Sembako Murah',
        //         'alamat' => 'Jl. Grosir No. 25, Tangerang',
        //         'telepon' => '021-7890123',
        //         'email' => 'sembako.murah@example.com',
        //         'petugas' => 'Joko',
        //         'keterangan' => 'Distributor bahan pokok untuk kebutuhan rumah tangga',
        //     ],
        // ];

        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 179; $i++) {
            $nama = $faker->company;
            Toko::firstOrCreate(['nama_nospace' => Str::slug($nama),], [
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $nama,
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'petugas' => $faker->name,
                'keterangan' => $faker->paragraph,
                'status' => 1,
            ]);
        }
    }
}
