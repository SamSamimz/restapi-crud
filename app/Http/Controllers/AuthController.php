<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //__Register user
    public function register(RegisterRequest $request) {

        try {
            $user = User::create($request->validated());
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'User registration successful',
            ]);
        }catch(ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ],422);
        }

    }

    // __ Login user
    public function login(LoginRequest $request) {

        try{
            $cred = $request->validated();
            $user = User::where('email',$cred['email'])->first();

            if(!$user || !Hash::check($cred['password'],$user->password)) {
                return response()->json([
                    'error' => 'Invalid email or password',
                ],401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'auth_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);


        }catch(ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json(['message' => 'Login successful']);
    }

    // ___ Logout 
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

}