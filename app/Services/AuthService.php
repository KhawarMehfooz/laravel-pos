<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService{

    /**
     * Register user 
     * 
     * @param array $data user's data
     * @return array
     */
    public function register(array $data){
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>'superadmin'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'=>$user,
            'token'=> $token
        ];
    }

    /**
     * Login user 
     * 
     * @param array $credentials user credentials
     * @return array
     */
    public function login(array $credentials){
        if(!Auth::attempt($credentials)){
            throw ValidationException::withMessages([
                'email'=>['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email',$credentials['email'])->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'=>$user,
            'token'=>$token
        ];
    }

    /**
     * Logout user 
     * @param $user
     */
    public function logout($user){
        $user->currentAccessToken()->delete();
    }
}