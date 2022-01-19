<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function store(array $request): User
    {
        $request['password']  = Hash::make($request['password']);
        $request['api_token'] = Str::random();

        $user = User::create($request);
        $user->api_token = Hash::make($user->api_token);

        return $user;
    }

    public function login(array $request): User
    {
        $user = User::whereEmail($request['email'])->first();

        if (!Hash::check($request['password'], $user->password)){
            throw new UserException("Email ou Senha inválida");
        }

        $user->api_token = Str::random();
        $user->save();

        $user->api_token = Hash::make($user->api_token);

        return $user;
    }
}
