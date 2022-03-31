<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return GameResource::collection(Game::all());
    }

    public function store(Request $request)
    {
        $request->validate([
           'boardgame_id' => 'required|integer|exists:boardgames,id',
           'players' => 'required'
        ]);

        $game = Game::create([ 'boardgame_id' => $request->input('boardgame_id') ]);
        $players = json_decode($request->players);
        foreach ($players as $player) {
            $game->users()->save(User::find($player->id), ['winner' => $player->winner]);
        }

    }
}
