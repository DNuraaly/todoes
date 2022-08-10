<?php

namespace App\Http\ Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoriesService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    private const MESSAGES = [
        'notFound' => 'Category not found',
        'deleted'  => 'Successfully, category deleted'
    ];

    /**
     * Show all categories
     * @param Request $request
     * @param CategoriesService $categoryService
     * @return JsonResponse
     */
    public function index(Request $request, CategoriesService $categoryService): JsonResponse
    {
        $user = $request->user();

        return response()->json($categoryService->getAllCategories($user));
    }

    /**
     *  Store a newly created category in storage.
     * @param StoreCategoryRequest $request
     * @param CategoriesService $categoriesService
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request, CategoriesService $categoriesService): JsonResponse
    {
        $user = $request->user();
        $validator = $request->getValidator();
//        $validated_data = $request->validated();  // or $validator->validated() ??? ask Kubanych

        if ($validator->fails())
        {
            return response()->json(['messages' => $validator->errors()],422);
        }

        $new_category = $categoriesService->createCategory($validator->validated(),$user);

        return response()->json($new_category, 201);
    }

    /**
     * Display the specified category.
     * @param Request $request
     * @param CategoriesService $categoriesService
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, CategoriesService $categoriesService, $id): JsonResponse
    {
        $user = $request->user();
        $category = $categoriesService->getCategory($id, $user);

        if (!$category)
        {
            return response()->json(['message' => self::MESSAGES['notFound']], 404);
        }

        return response()->json($category,200);
    }

    /**
     * Update the specified category in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param CategoriesService $categoriesService
     * @param  $id
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, CategoriesService $categoriesService, $id): JsonResponse
    {
        $user = $request->user();
        $category = $categoriesService->getCategory($id, $user);

        if (!$category)
        {
            return response()->json(['message' => self::MESSAGES['notFound']], 404);
        }

        $validator = $request->getValidator();

        if ($validator->fails())
        {
            return response()->json(['messages' => $validator->errors()],422);
        }

        $updated_category = $categoriesService->updateCategory($category, $validator->validated());

        return response()->json($updated_category);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Request $request
     * @param CategoriesService $categoriesService
     * @param  $id
     * @return JsonResponse
     */
    public function destroy(Request $request, CategoriesService $categoriesService, $id): JsonResponse
    {
        $user = $request->user();
        $category = $categoriesService->getCategory($id,$user);

        if (!$category)
        {
            return response()->json(['message' => self::MESSAGES['notFound']], 404);
        }

        $category->delete();

        return response()->json(['message' => self::MESSAGES['deleted']], 200);
    }
}
