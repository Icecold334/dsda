<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UraianRekeningFactory extends Factory
{
    public function definition()
    {
        return [
            'aktivitas_sub_kegiatan_id' => \App\Models\AktivitasSubKegiatan::factory(),
            'uraian' => $this->faker->sentence(7)
        ];
    }
}
