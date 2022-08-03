<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
            'category_id'=>Category::factory(),
            'title'=> $this->faker->city(),
            'description' => $this->faker->company(),
            'due_date' => $this->faker->dateTime()
            //
        ];
    }
}
