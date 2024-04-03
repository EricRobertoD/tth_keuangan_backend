<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registerData = $request->all();

        $validate = Validator::make($registerData, [
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();
            $response = [
                'status' => 'error',
                'message' => 'Registrasi gagal. Silakan periksa semua bagian yang ditandai.',
                'errors' => $errors->toArray()
            ];

            return response()->json($response, 400);
        }

        $registerData['password'] = bcrypt($registerData['password']);

        $user = User::create($registerData);
        return response()->json([
            'status' => 'success',
            'message' => 'Register Berhasil!.',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first(), 'errors' => $validate->errors()], 400);
        }

        if (Auth::guard('user')->attempt($loginData)) {
            $users = Auth::user();
            $token = $users->createToken('Authentication Token', ['user'])->plainTextToken;

            return response([
                'message' => 'Authenticated',
                'data' => [
                    'status' => 'success',
                    'User' => $users,
                    'token_type' => 'Bearer',
                    'access_token' => $token,
                ],
            ]);
        } else {
            return response(['message' => 'Invalid Credentials user'], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Logout Success',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }
    }

    public function registerAdmin(Request $request)
    {
        $registerData = $request->all();

        $validate = Validator::make($registerData, [
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();
            $response = [
                'status' => 'error',
                'message' => 'Admin registration failed. Please check all marked fields.',
                'errors' => $errors->toArray()
            ];

            return response()->json($response, 400);
        }

        $registerData['password'] = bcrypt($registerData['password']);

        $admin = Admin::create($registerData);
        return response()->json([
            'status' => 'success',
            'message' => 'Admin registration successful!',
            'data' => $admin
        ], 200);
    }
    
    public function loginAdmin(Request $request)
{
    $loginData = $request->all();
    $validate = Validator::make($loginData, [
        'name' => 'required',
        'password' => 'required',
    ]);

    if ($validate->fails()) {
        return response(['message' => $validate->errors()->first(), 'errors' => $validate->errors()], 400);
    }

    if (Auth::guard('admin')->attempt($loginData)) {
        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('Authentication Token', ['admin'])->plainTextToken; // Assuming you want to generate a token for admin

        return response([
            'message' => 'Authenticated',
            'data' => [
                'status' => 'success',
                'Admin' => $admin,
                'token_type' => 'Bearer',
                'access_token' => $token,
            ],
        ]);
    } else {
        return response(['message' => 'Invalid admin credentials'], 401);
    }
}
    public function logoutAdmin(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $admin->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Logout successful',
                'admin' => $admin
            ], 200);
        } else {
            return response()->json([
                'message' => 'Admin not authenticated',
            ], 401);
        }
    }
}
