<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'albumId' => $this->faker->numberBetween(1, 20),
            'title' => $this->faker->realText(10),
            'content' => $this->faker->realText(20),
            'url' => "https://picsum.photos/640/480?random=" . $this->faker->numberBetween(0, 400),
        ];
    }
}