<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryService
{
    public function getAllCategories($request)
    {
        $user = $request->user();
        return $user->categories()->get();
    }

    public function createCategory($request)
    {
        $user = $request->user();
        $rules = [
            'title' => [
                'required',
                'string',
                'max:30',
                Rule::unique('categories', 'title')
                    ->where('user_id', $user->id)
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['messages' => $validator->errors()];
        }

        $category = new Category($validator->validated());
        $category->user()->associate($user);
        $category->save();

        return $category;
    }

    public function getCategory($request, $id)
    {
        $user = $request->user();
        $category = $user->categories()->find($id);

        if (!$category)
        {
            return ['message' => "Category not found."];
        }

        return $category;
    }

    public function updateCategory($request, $id)
    {
        $user = $request->user();
        $category = $user->categories()->find($id);

        if (!$category)
        {
            return ['message' => 'Category not found.'];
        }

        $rules = [
            'title' => [
                'required',
                'string',
                'max:30',
                Rule::unique('categories', 'title')
                    ->where('user_id', $user->id)
                    ->ignore($id),
            ]
        ];
        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails())
        {
            return ['messages' => $validator->errors()];
        }

        $category->update($validator->validated());

        return $category;
    }

    public function deleteCategory($request, $id)
    {
        $user = $request->user();
        $category = $user->categories()->find($id);

        if (!$category)
        {
            return ['message' => 'Category not found.'];
        }

        $category->delete();

        return ['message' => 'Successfully, category is deleted.'];
    }




}
