<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoriesService
{
    public function getAllCategories($user)
    {
        return $user->categories()->get();
    }

    public function createCategory($data, $user): Category
    {
        $category = new Category($data);
        $category->user()->associate($user);
        $category->save();

        return $category;
    }

    public function updateCategory($category, $data): Category {
        return $category->update($data);
    }

    public function getCategory($id, $user){
        return $user->categories()->find($id);
    }
}
