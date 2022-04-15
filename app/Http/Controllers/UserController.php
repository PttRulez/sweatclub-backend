<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\StatService;
use App\Services\FileService;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function allUsersWithStats()
    {
        return (new StatService())->allUsersWithStats()->toArray();
    }

    public function show($id)
    {
        return (new StatService())->userStats($id);
    }

    public function update($id)
    {
        $user = User::find($id);

        request()->validate([
            'nickname' => 'required|max:191|unique:users,nickname,' . $id,
            'avatar' => 'nullable'
        ]);

        $user->nickname = request()->input('nickname');
        (new FileService())->updatePublicImageFromInput('avatar', 'img/user_avatars/', $user->nickname . '_avatar', $user, 'avatar_path');

        $user->save();
        return new UserResource($user);
    }
}
