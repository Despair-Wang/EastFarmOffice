<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(10),
            'content' => $this->faker->realText(20),
            'cover' => "https://picsum.photos/640/480?random=" . $this->faker->numberBetween(0, 200),
            'created_at' => $this->faker->dateTimeBetween($starDate = '-7 years', $endDate = 'now', $timezone = date_default_timezone_get()),
        ];
    }
}