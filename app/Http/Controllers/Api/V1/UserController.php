<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'unique:users,email', 'email:filter'],
            'email_verified_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'password' => ['required', 'string']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['messages' => $validator->errors()]);

        $user = new User($validator->validate());
        $user->email_verified_at = $request->email_verified_at;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json($user);
    }

    public function logIn(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password))
            return response()->json(['message' => 'user in not auth or not found'], 404);

        $token = $user->createToken('user-token')->plainTextToken;
        return response()->json([
            "message" => "Successful",
            "user" => $user,
            "token" => $token
        ], 201);
    }

    //

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "log out successful"]);
    }

    public function logOutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(["message" => "log out all successful"]);
    }
}
