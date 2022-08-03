<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::factory()
            ->count(2)
            ->hasNotes(20)
            ->create();

        Category::factory()
            ->count(3)
            ->hasNotes(5)
            ->create();
    }
}
