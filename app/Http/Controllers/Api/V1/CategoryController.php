<?php

namespace App\Http\ Controllers\Api\V1;


use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->User();
//        $response =  Category::where('user_id',$user->id)->get();
        $categories = $user->categories()->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return Response
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->User();
        $rules = [
            'title' => [
                'required',
                'string',
                'max:30',
                Rule::unique('categories','title')
                    ->where('user_id',$user->id)
                ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()]);
        }

        $category = new Category($validator->validate());
        $category->user()->associate($user);
        $category->save();
        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        /**
         * @var $user User
         */
        $user = $request->User();
        $category = $user->categories()->where('id', $id)->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.']);
        }

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        /**
         * @var $user User
         */
        $user = $request->User();
        $category = $user->categories()->where('id', $id)->first();

        if (!$category) {
            return response()->json(['message' => 'category not found.']);
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()]);
        }

        $category->update($validator->validate());
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->User();
        $category = $user->categories()->find($id);


        if (!$category) {
            return response()->json(['message' => 'category not found. ']);
        }

        $category->delete();
        return response()->json(['message' => 'successfully, category deleted.']);
    }
}
