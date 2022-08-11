<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id'=> rand(1, 15),
            'title'=> $this->faker->city(),
            'description' => $this->faker->company(),
            'due_date' => $this->faker->dateTime(),
        ];
    }
}
