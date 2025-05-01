<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubKegiatanFactory extends Factory
{
    public function definition()
    {
        return [
            'kegiatan_id' => \App\Models\Kegiatan::factory(),
            'sub_kegiatan' => $this->faker->sentence(5)
        ];
    }
}
