<?php

function userAvatarUrl($user) {
    return $user->thumbnail ? config('app.url') . $user->thumbnail : null;
}

function gamePhotoUrl($game) {
    return $game->photo_path ? config('app.url') . $game->photo_path : null;
}

