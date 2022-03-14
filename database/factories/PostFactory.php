<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realTextBetween(10, 20),
            'content' => $this->faker->realTextBetween(150, 300),
            'creator' => $this->faker->numberBetween(1, 5),
            'category' => $this->faker->numberBetween(1, 10),
            'state' => 1,
        ];
    }
}