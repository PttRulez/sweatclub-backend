<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;

class StatService
{

    public function allUsersWithStats()
    {
        return User::with('games.boardgame')->get()->map(function($user) {
            return $this->getStatsFromUserWithGames($user);
        })->sortBy([
            ['overall.gamesWon', 'desc'],
            ['overall.winrate', 'desc'],
        ])->values();
    }

    public function userStats($id)
    {
        return $this->getStatsFromUserWithGames(User::with('games.boardgame')->findOrFail($id));
    }

    private function getStatsFromUserWithGames(User $user)
    {
        $games = $user->games;
        $gamesWon = $user->gamesWon;

        $byGames = $games->groupBy('boardgame.name')->map(function ($item, $key) {
            $played = $item->count();
            $won = $item->where('pivot.winner', 1)->count();
            $winrate = floor($won/$played * 100) . '%';
            return [
                'played' => $item->count(),
                'won' => $item->where('pivot.winner', 1)->count(),
                'winrate' => $winrate,
                ];
        });

        return [
            'user' => new UserResource($user),
            'overall' => [
                'gamesPlayed' => $games->count(),
                'gamesWon' => $gamesWon->count(),
                'winrate' => $games->count() > 0 ? floor($gamesWon->count()/$games->count() * 100) . '%' : '0%',
            ],
            'byBoardGames' => $byGames->toArray(),
        ];
    }
}
