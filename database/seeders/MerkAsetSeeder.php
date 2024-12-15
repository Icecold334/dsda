<?php

namespace Database\Seeders;

use App\Models\Merk;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MerkAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merkData = [
            'Elektronik' => [
                'Samsung',
                'LG',
                'Sony',
                'Panasonic',
                'Sharp',
                'Philips',
                'Toshiba',
                'Canon',
                'Epson',
                'Lenovo',
                'Asus',
                'Acer',
                'Apple',
                'Xiaomi',
                'Oppo',
                'Vivo',
                'Realme',
            ],
            'Otomotif' => [
                'Toyota',
                'Honda',
                'Suzuki',
                'Yamaha',
                'Daihatsu',
                'Mitsubishi',
                'Nissan',
                'Ford',
                'Chevrolet',
                'Mazda',
                'Hyundai',
                'Kia',
                'BMW',
                'Mercedes-Benz',
                'Lexus',
                'Isuzu',
                'Hino',
            ],
            'Furniture' => [
                'IKEA',
                'Informa',
                'Ace Hardware',
                'Chitose',
                'Olympic',
                'VIVERE',
                'Ligna',
                'King Koil',
                'Spring Air',
                'Serta',
                'Florence',
                'Dunlopillo',
                'Ferro',
                'Ligna',
                'Alcano',
                'Dekoruma',
                'JYSK',
            ],
        ];

        foreach ($merkData as $kategori => $merkList) {
            foreach ($merkList as $namaMerk) {
                Merk::create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'nama' => $namaMerk,
                    'nama_nospace' => Str::slug($namaMerk),
                    'keterangan' => 'Merek kategori ' . $kategori,
                    'status' => 1,
                ]);
            }
        }
    }
}
