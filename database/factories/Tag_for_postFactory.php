<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Tag_for_postFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postId' => $this->faker->numberBetween(1, 50),
            'tagId' => $this->faker->numberBetween(1, 16),
            'state' => 1,
        ];
    }
}