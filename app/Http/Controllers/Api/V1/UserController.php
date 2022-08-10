<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function createUser(StoreUserRequest $request, UserService $userService): JsonResponse
    {
        $validator = $request->getValidator();

        if ($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }

        return response()->json($userService->createUser($validator->validated()),201);
    }

    public function logIn(LogInRequest $request, UserService $userService): JsonResponse
    {
        $validator = $request->getValidator();

        if ($validator->fails())
        {
            return response()->json(['messages' => $validator->errors()]);
        }

        $validated =  $validator->validated();
        $user = $userService->getUser($validated);

        if (!$user){
            return response()->json(['message' => 'User not found.']);
        }

        $token = $userService->getUserToken($user,$validated);

        if (!$token)
        {
            return response()->json(['message' => 'Password is wrong.']);
        }

        return response()->json(
            [
            "message" => "Successfully",
            "user" => $user,
            "token" => $token
            ], 201);
    }

    public function logOut(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "log out successfully."]);
    }

    public function logOutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(["message" => "log out all successful"]);
    }
}
