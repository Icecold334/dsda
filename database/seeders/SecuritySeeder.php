<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Security;
use App\Models\UnitKerja;

class SecuritySeeder extends Seeder
{
    public function run(): void
    {
        // Mapping nama unit ke id
        $unitJaksel = UnitKerja::where('nama', 'like', '%Jakarta Selatan%')->first()?->id;
        $unitJakut = UnitKerja::where('nama', 'like', '%Jakarta Utara%')->first()?->id;
        $unitJakbar = UnitKerja::where('nama', 'like', '%Jakarta Barat%')->first()?->id;
        $unitJakpus = UnitKerja::where('nama', 'like', '%Jakarta Pusat%')->first()?->id;
        $unitJaktim = UnitKerja::where('nama', 'like', '%Jakarta Timur%')->first()?->id;
        $unitKepSeribu = UnitKerja::where('nama', 'like', '%Kepulauan Seribu%')->first()?->id;

        $securities = [
            // JAKSEL
            ['nama' => 'Jekson Riwu', 'unit_id' => $unitJaksel],
            ['nama' => 'Dimas Sapta Alamsyah', 'unit_id' => $unitJaksel],
            ['nama' => 'Rizki Kurnia Adinata', 'unit_id' => $unitJaksel],
            ['nama' => 'Budhi Alvino', 'unit_id' => $unitJaksel],
            ['nama' => 'Rojih', 'unit_id' => $unitJaksel],
            ['nama' => 'Arif Maulana', 'unit_id' => $unitJaksel],
            ['nama' => 'Maryadi', 'unit_id' => $unitJaksel],
            ['nama' => 'Riki', 'unit_id' => $unitJaksel],
            ['nama' => 'M. Kamaluddin Zuhdi', 'unit_id' => $unitJaksel],
            // JAKUT
            ['nama' => 'Anton Suherman', 'unit_id' => $unitJakut],
            ['nama' => 'Darfian', 'unit_id' => $unitJakut],
            ['nama' => 'Hendra Saipul Anwar', 'unit_id' => $unitJakut],
            ['nama' => 'Junaidi', 'unit_id' => $unitJakut],
            ['nama' => 'Mohamad Wahet', 'unit_id' => $unitJakut],
            ['nama' => 'Muhamad Fadli Elwuar', 'unit_id' => $unitJakut],
            ['nama' => 'Sulaeman', 'unit_id' => $unitJakut],
            ['nama' => 'Tarmidi', 'unit_id' => $unitJakut],
            ['nama' => 'Tedy Supriyanto', 'unit_id' => $unitJakut],
            ['nama' => 'Suparno', 'unit_id' => $unitJakut],
            ['nama' => 'Amdoro', 'unit_id' => $unitJakut],
            ['nama' => 'Hartarto', 'unit_id' => $unitJakut],
            // JAKBAR
            ['nama' => 'Rojali', 'unit_id' => $unitJakbar],
            ['nama' => 'Surdi Adam', 'unit_id' => $unitJakbar],
            ['nama' => 'Khoirul', 'unit_id' => $unitJakbar],
            ['nama' => 'Bambang Suharjo', 'unit_id' => $unitJakbar],
            ['nama' => 'Kukuh Kuncoro', 'unit_id' => $unitJakbar],
            ['nama' => 'Johny Satria Mirza', 'unit_id' => $unitJakbar],
            ['nama' => 'Umar Sumantri', 'unit_id' => $unitJakbar],
            ['nama' => 'Majuk', 'unit_id' => $unitJakbar],
            ['nama' => 'Miskar', 'unit_id' => $unitJakbar],
            ['nama' => 'Agus Mawardin', 'unit_id' => $unitJakbar],
            ['nama' => 'Arfan Faisal', 'unit_id' => $unitJakbar],
            ['nama' => 'Fitra Al Ramadhan', 'unit_id' => $unitJakbar],
            ['nama' => 'Adliyas', 'unit_id' => $unitJakbar],
            ['nama' => 'Ubaydillah', 'unit_id' => $unitJakbar],
            ['nama' => 'Toton Fhatoni', 'unit_id' => $unitJakbar],
            // JAKTIM, JAKPUS, KEPULAUAN SERIBU (dummy, isi sesuai kebutuhan)
        ];

        foreach ($securities as $security) {
            Security::firstOrCreate([
                'nama' => $security['nama'],
                'unit_id' => $security['unit_id'],
            ]);
        }
    }
} 