<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUser($data)
    {
        return User::query()->where('email', $data['email'])->first();
    }

    public function generateUserToken($user)
    {
        return $user->createToken()->plainTextToken;
    }

public function createUser($userData): User
     {
        $user = new User($userData);
        $user->password = Hash::make($userData['password']);
        $user->save();

        return $user;
    }

}
