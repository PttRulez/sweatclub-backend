<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class StatService
{
    public function allUsersWithStats()
    {
        if (request()->has('club_id')) {
            $req = User::whereHas('games', function (Builder $query) {
                $query->where('club_id', request('club_id'));
            })->with(['games' => function ($q) {
                $q->where('club_id', request('club_id'))->with('boardgame');
            }]);
        } else {
            $req = User::whereHas('games')->with('games.boardgame');
        }

        return $req->get()->map(function ($user) {
            return $this->getStatsFromUserWithGames($user);
        })->sortBy([
            ['overall.gamesWon', 'desc'],
            ['overall.winrate', 'desc'],
        ])->map(function ($item) {
            $item['user']->place = 1;
            $item['overall']['winrate'] .= '%';
            return $item;
        })->values();
    }

    public function userStats($id)
    {
        return $this->getStatsFromUserWithGames(User::with('games.boardgame')->findOrFail($id));
    }

    private function getStatsFromUserWithGames(User $user)
    {
        if (request()->has('club_id')) {
            $games = $user->games->where('club_id', request('club_id'));
            $gamesWon = $user->gamesWon->where('club_id', request('club_id'));
        } else {
            $games = $user->games;
            $gamesWon = $user->gamesWon;
        }


        $byGames = $games->groupBy('boardgame.name')->map(function ($item, $key) {
            $played = $item->count();
            $won = $item->where('pivot.winner', 1)->count();
            $winrate = floor($won / $played * 100) . '%';
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
                'winrate' => $games->count() > 0 ? round($gamesWon->count() / $games->count() * 100) : 0,
            ],
            'byBoardGames' => $byGames->toArray(),
        ];
    }
}
