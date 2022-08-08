<?php

namespace App\Http\ Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Show all categories
     * @param Request $request
     * @param CategoryService $category
     * @return JsonResponse
     */
    public function index(Request $request, CategoryService $category): JsonResponse
    {
        $allCategories = $category->getAllCategories($request);

        return response()->json($allCategories);
    }


    /**
     *  Store a newly created category in storage.
     * @param Request $request
     * @param CategoryService $category
     * @return JsonResponse
     */
    public function store(Request $request, CategoryService $category): JsonResponse
    {
        $new_category = $category->createCategory($request);

        return response()->json($new_category);
    }



    /**
     * Display the specified resource.
     * @param Request $request
     * @param CategoryService $category
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, CategoryService $category, $id): JsonResponse
    {
        return response()->json($category->getCategory($request, $id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CategoryService $category
     * @param  $id
     * @return Response
     */
    public function update(Request $request, CategoryService $category, $id): JsonResponse
    {
        return response()->json($category->updateCategory($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param CategoryService $category
     * @param  $id
     * @return Response
     */
    public function destroy(Request $request, CategoryService $category, $id): JsonResponse
    {
        return response()->json($category->deleteCategory($request, $id));
    }
}
