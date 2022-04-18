<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\UserResource;
use App\Models\Game;
use App\Models\User;
use App\Services\FileService;
use App\Services\StatService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    public function index()
    {
        return GameResource::collection(Game::orderBy('date_played', 'desc')->orderBy('id', 'desc')->get());
    }

    public function show($id)
    {
        $game = Game::find($id);
        $users = User::whereNotIn('id', $game->players->pluck('id'))->get();
        return [
            'game' => new GameResource(Game::find($id)),
            'users' => UserResource::collection($users),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'boardgame_id' => 'required|integer|exists:boardgames,id',
            'players' => 'required',
            'photo' => 'nullable',
            'date_played' => 'required'
        ]);

        $game = Game::create([
            'boardgame_id' => $request->input('boardgame_id'),
            'date_played' => Carbon::parse($request->input('date_played'))
        ]);

        $players = json_decode($request->players);
        foreach ($players as $player) {
            $game->players()->save(User::find($player->id), ['winner' => $player->winner, 'points' => $player->points]);
        }

        if ($request->hasFile('photo')) {
            $photo_path = (new FileService())->storePublicImageFromInput('photo', 'img/games/', $game->id . '_game');
            $game->photo_path = $photo_path;
            $game->save();
        }

    }

    public function update($id)
    {
        request()->validate([
            'boardgame_id' => 'required|integer|exists:boardgames,id',
            'players' => 'required'
        ]);

        $game = Game::find($id);

        if (request()->hasFile('photo')) {
            Log::info('s');
            (new FileService())->updatePublicImageFromInput('photo', 'img/games/', $game->id . '_game', $game, 'photo_path');
        }

        $game->fill(request()->all());
        $game->save();
        $players = json_decode(request()->players);
        $sync = [];
        foreach ($players as $player) {
            $sync[$player->id] = ['winner' => $player->winner, 'points' => $player->points];
        }
        $game->players()->sync($sync);

    }
}
