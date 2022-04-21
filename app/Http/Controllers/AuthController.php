<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\FileService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nickname' => 'required|max:191',
            'password' => 'required'
        ]);

        $user = User::where('nickname', $request->nickname)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Неправильные ник / пароль'
            ], 401);
        } else {
            $user->tokens()->delete();
            if ($user->role === 'admin') {
                $token = $user->createToken($user->nickname . '_token', ['admin', 'user']);
            } else {
                $token = $user->createToken($user->nickname . '_token', ['user']);
            }

            return response()->json([
                'status' => 200,
                'user' => new UserResource($user),
                'authToken' => [
                    'token' => $token->plainTextToken,
                    'abilities' => $token->accessToken->abilities
                ],
                'message' => 'Login succeeded'
            ]);
        }

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged Out Successfully'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nickname' => 'required|max:191|unique:users',
            'password' => 'required|confirmed',
            'avatar_path' => 'nullable'
        ]);

        $user = User::create([
            'nickname' => $request->nickname,
            'password' => Hash::make($request->password),
            'avatar_path' => (new FileService())->storePublicImageFromInput('avatar', 'img/user_avatars/', $request->nickname . '_avatar')
        ]);

        $token = $user->createToken($user->nickname . '_token', ['user']);
        return response()->json([
            'status' => 200,
            'user' => new UserResource($user),
            'authToken' => [
                'token' => $token->plainTextToken,
                'abilities' => $token->accessToken->abilities
            ],
            'message' => 'You have been registered successfully'
        ]);

    }
}
