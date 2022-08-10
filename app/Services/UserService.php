<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUser($data)
    {
        $user = User::query()->where('email', $data['email'])->first();

        return !$user ? null : $user;
    }

    public function getUserToken($user,$data)
    {
        $pass_access = Hash::check($data['password'], $user->password);

        return !$pass_access ? null : $user->createToken('user-token-2022')->plainTextToken;
    }

    public function createUser($userData): User
    {
        $user = new User($userData);
        $user->password = Hash::make($userData['password']);
        $user->save();

        return $user;
    }

}
