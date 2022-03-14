<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PediaTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->lexify('標籤??'),
        ];
    }
}
