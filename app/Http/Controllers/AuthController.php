<?php

namespace App\Http\Controllers;

use App\Models\User;

//import validator
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login']);
    }

    public function login(Request $request)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // check validation status
        if ($validator->fails()) {
            return falseResponse("Validation error", 422, $validator->errors());
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // return falseResponse("Invalid credentials", 400);
            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ];
            return $this->sendResponse('Login berhasil.', $data);
        }

        return falseResponse("Invalid credentials", 400);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
