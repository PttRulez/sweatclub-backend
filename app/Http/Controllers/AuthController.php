<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nickname' => 'required|min:3|max:191',
            'password' => 'required'
        ]);

        $user = User::where('nickname', $request->nickname)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Неправильные почта / пароль'
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
                'user' => $user,
                'auth_token' => [
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

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatar->move('img/user_avatars/', $avatar->getClientOriginalName());
            $avatar_path = 'img/user_avatars/' . $avatar->getClientOriginalName();
        } else {
            $avatar_path = null;
        }

        $user = User::create([
            'nickname' => $request->nickname,
            'password' => Hash::make($request->password),
            'avatar_path' => $avatar_path
        ]);

        $token = $user->createToken($user->nickname . '_token');

        return response()->json([
            'status' => 200,
            'user' => $user,
            'auth_token' => [
                'token' => $token->plainTextToken,
                'abilities' => $token->accessToken->abilities
            ],
            'message' => 'You have been registered successfully'
        ]);

    }
}
