<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends BaseApiController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        
    }

    public function register(RegisterRequest $request){
        try{
            $result = $this->authService->register($request->validated());
            return $this->sendResponse([
                'user'=> new UserResource($result['user']),
                'token'=> $result['token']
            ], 'User registered successfully',201);
        }catch(\Exception $e){
            return $this->sendError($e->getMessage());

        }
    }

    public function login(LoginRequest $request){
        try{
            $result = $this->authService->login($request->validated());

            return $this->sendResponse([
                'user'=> new UserResource($result['user']),
                'token'=> $result['token']
            ], 'User logged in successfully');
        }catch(\Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(Request $request){
        try{
            $this->authService->logout($request->user());
            return $this->sendResponse(null,'User logged out successfully');
        }catch(\Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
    
    public function me(Request $request){
        return $this->sendResponse(
            new UserResource($request->user()),
            'User profile retrieved successfully'
        );
    }
}
