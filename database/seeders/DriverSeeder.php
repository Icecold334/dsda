<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\UnitKerja;

class DriverSeeder extends Seeder
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

        $drivers = [
            // JAKSEL
            ['nama' => 'Supriyadi', 'unit_id' => $unitJaksel],
            ['nama' => 'Hari Triatna', 'unit_id' => $unitJaksel],
            ['nama' => 'Dedi Andriansah', 'unit_id' => $unitJaksel],
            ['nama' => 'Dimas Aditya Putra', 'unit_id' => $unitJaksel],
            ['nama' => 'Ferry Sormin', 'unit_id' => $unitJaksel],
            ['nama' => 'Sidik', 'unit_id' => $unitJaksel],
            ['nama' => 'Jasno', 'unit_id' => $unitJaksel],
            ['nama' => 'Deni Haryanto', 'unit_id' => $unitJaksel],
            ['nama' => 'Zulian Agus', 'unit_id' => $unitJaksel],
            ['nama' => 'Maulan Malik', 'unit_id' => $unitJaksel],
            ['nama' => 'M. Fadillah Tigana', 'unit_id' => $unitJaksel],
            ['nama' => 'Rian Saputra', 'unit_id' => $unitJaksel],
            ['nama' => 'Cosmas Silen', 'unit_id' => $unitJaksel],
            ['nama' => 'Wawan', 'unit_id' => $unitJaksel],
            ['nama' => 'Marhali', 'unit_id' => $unitJaksel],
            ['nama' => 'Muhamad Topik', 'unit_id' => $unitJaksel],
            ['nama' => 'Didit Bastian', 'unit_id' => $unitJaksel],
            ['nama' => 'Andri Sentosa', 'unit_id' => $unitJaksel],
            ['nama' => 'Jatmiko Nugroho', 'unit_id' => $unitJaksel],
            ['nama' => 'Hairudin', 'unit_id' => $unitJaksel],
            ['nama' => 'Jutidharo Endro', 'unit_id' => $unitJaksel],
            ['nama' => 'Abdol Rouf', 'unit_id' => $unitJaksel],
            ['nama' => 'Aji Sekar Bawono', 'unit_id' => $unitJaksel],
            // JAKUT
            ['nama' => 'Hisyam Anshori', 'unit_id' => $unitJakut],
            ['nama' => 'Absalon Wahyudin Tarihoran', 'unit_id' => $unitJakut],
            ['nama' => 'Saripudin', 'unit_id' => $unitJakut],
            ['nama' => 'Dadang', 'unit_id' => $unitJakut],
            ['nama' => 'Syarifudin', 'unit_id' => $unitJakut],
            ['nama' => 'Diding', 'unit_id' => $unitJakut],
            ['nama' => 'Satria', 'unit_id' => $unitJakut],
            ['nama' => 'Margono', 'unit_id' => $unitJakut],
            // JAKBAR
            ['nama' => 'Abdul Mutholib', 'unit_id' => $unitJakbar],
            ['nama' => 'M Agus Saleh', 'unit_id' => $unitJakbar],
            ['nama' => 'Muhadi', 'unit_id' => $unitJakbar],
            ['nama' => 'William Andreas Wuisan', 'unit_id' => $unitJakbar],
            ['nama' => 'Daman Huri', 'unit_id' => $unitJakbar],
            ['nama' => 'Feri Irawan', 'unit_id' => $unitJakbar],
            ['nama' => 'Guntur', 'unit_id' => $unitJakbar],
            ['nama' => 'Hadi Sumarno', 'unit_id' => $unitJakbar],
            ['nama' => 'Yusri Andani', 'unit_id' => $unitJakbar],
            ['nama' => 'Ely Johani', 'unit_id' => $unitJakbar],
            ['nama' => 'Edi Sismanto', 'unit_id' => $unitJakbar],
            ['nama' => 'Usep Matius', 'unit_id' => $unitJakbar],
            ['nama' => 'Adi Sukarjo', 'unit_id' => $unitJakbar],
            ['nama' => 'Syaifullah', 'unit_id' => $unitJakbar],
            ['nama' => 'Nuri Hendriansyah', 'unit_id' => $unitJakbar],
            ['nama' => 'Satria Bahari', 'unit_id' => $unitJakbar],
            ['nama' => 'Curyinato', 'unit_id' => $unitJakbar],
            ['nama' => 'Encin', 'unit_id' => $unitJakbar],
            ['nama' => 'Arif Supanji', 'unit_id' => $unitJakbar],
            ['nama' => 'Saripudin', 'unit_id' => $unitJakbar],
            ['nama' => 'Ahmad Hasyim Baihaqi Y', 'unit_id' => $unitJakbar],
            ['nama' => 'Jhon Edward G', 'unit_id' => $unitJakbar],
            ['nama' => 'M. Irhim Hadi', 'unit_id' => $unitJakbar],
            ['nama' => 'Budi Sugiarto', 'unit_id' => $unitJakbar],
            ['nama' => 'Endang Supriatna', 'unit_id' => $unitJakbar],
            ['nama' => 'M Firmansyah', 'unit_id' => $unitJakbar],
            ['nama' => 'Johan Wahyudin', 'unit_id' => $unitJakbar],
            ['nama' => 'Fahru Salam', 'unit_id' => $unitJakbar],
            ['nama' => 'Jonatan Sembiring', 'unit_id' => $unitJakbar],
            ['nama' => 'Suryadi', 'unit_id' => $unitJakbar],
            // JAKPUS
            ['nama' => 'Iwam Hanapi', 'unit_id' => $unitJakpus],
            ['nama' => 'Agus Supriyanto', 'unit_id' => $unitJakpus],
            ['nama' => 'Bambang Riyanto', 'unit_id' => $unitJakpus],
            ['nama' => 'Eko Sugianto', 'unit_id' => $unitJakpus],
            ['nama' => 'Arga Seftiyan Wijaya', 'unit_id' => $unitJakpus],
            ['nama' => 'Bambang Sunarto', 'unit_id' => $unitJakpus],
            ['nama' => 'Suryanto', 'unit_id' => $unitJakpus],
            // JAKTIM (dummy, isi sesuai kebutuhan)
            // KEPULAUAN SERIBU (dummy, isi sesuai kebutuhan)
        ];

        foreach ($drivers as $driver) {
            Driver::firstOrCreate([
                'nama' => $driver['nama'],
                'unit_id' => $driver['unit_id'],
            ]);
        }
    }
} 