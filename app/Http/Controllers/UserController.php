<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Validators\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->store($request->validateRegister());

        return ApiResponse::success($user, 201);
    }

    public function login(UserRequest $validator)
    {
        $request = $validator->validateLogin();

        $result = $this->userService->login($request);

        return ApiResponse::success($result);
    }
}
