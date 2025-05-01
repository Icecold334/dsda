<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KegiatanFactory extends Factory
{
    public function definition()
    {
        return [
            'program_id' => \App\Models\Program::factory(),
            'kegiatan' => $this->faker->sentence(4)
        ];
    }
}
