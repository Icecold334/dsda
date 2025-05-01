<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AktivitasSubKegiatanFactory extends Factory
{
    public function definition()
    {
        return [
            'sub_kegiatan_id' => \App\Models\SubKegiatan::factory(),
            'aktivitas' => $this->faker->sentence(6)
        ];
    }
}
