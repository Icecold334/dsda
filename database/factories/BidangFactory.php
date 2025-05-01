<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BidangFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_bidang' => $this->faker->company
        ];
    }
}
