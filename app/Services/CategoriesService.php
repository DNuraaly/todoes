<?php

namespace App\Services;

use App\Models\Category;

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

    public function updateCategory($category, $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function getCategory($id, $user)
    {
        $category = $user->categories()->find($id);
        return !$category ? null : $category;
    }
}
