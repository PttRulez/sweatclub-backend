<?php

function userAvatarUrl($user) {
    return $user->avatar_path ? config('app.url') . $user->avatar_path : null;
}

function gamePhotoUrl($game) {
    return $game->photo_path ? config('app.url') . $game->photo_path : null;
}

