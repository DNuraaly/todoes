<?php

namespace App\Http\ Controllers\Api\V1;


use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $response_data = Category::all();
        return response()->json($response_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'title' => ['required', 'string', 'unique:categories', 'max:30']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['messages' => $validator->errors()]);
        else {
            $category = Category::create($validator->validate());
            return response()->json($category);
        }


        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $category = Category::find($id);
        if ($category)
            return response()->json($category);
        else
            return response()->json(['message' => 'Category not found.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'category not found.']);
        }

        $rules = [
            'title' => ['required', 'string', 'unique:categories,title,'.$id, 'max:30']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['messages' => $validator->errors()]);
        else {
            $category = Category::find($id);
            $category->update($validator->validate());
            return response()->json($category);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['message' => 'success']);
        } else
            return response()->json(['message' => 'category not found. ']);
        //
    }
}
