<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class UserController extends BaseController
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(StoreUserRequest $request): JsonResponse
    {
        $validator = $request->getValidator();

        if ($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }

        return response()->json($this->userService->createUser($validator->validated()),201);
    }

    public function logIn(LogInRequest $request): JsonResponse
    {
        $validator = $request->getValidator();

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->first());
        }

        $validated =  $validator->validated();
        $user = $this->userService->getUser($validated);

        if (!$user && !Hash::check($validated['password'], $user->password)){
            return response()->json(['message' => 'These credentials are incorrect']);
        }

        $token = $this->userService->generateUserToken($user);

        return response()->json([
            "message" => "Success",
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

    public function index(Request $request): JsonResponse {
        return response()->json($request->user());
    }

    public function edit(UpdateUserRequest $request){
        $validator = $request->getValidator();

        if ($validator->fails()){
            return $this->validationError($validator->errors()->first());
        }

        $user = $request->user();

        $user_profile_photo = $user->profile_photo;

        if ($request->hasFile('profile_photo')){

            if ($user_profile_photo){

                $old_path = public_path().'/profile_images/'.$user_profile_photo;

                if (File::Exists($old_path)){
                    File::delete($old_path);
                }
            }

            $user_profile_photo = 'profile-image-'.now().'.'.$request->profile_photo->extension();
            $request->profile_photo->move(public_path('/profile_images'), $user_profile_photo);
        }


        $user->fill($request->validated());
        $user->profile_photo = $user_profile_photo;
        $user->save();

        return response()->json($user);
    }
}
