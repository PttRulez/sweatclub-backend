<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'boardgame' => new BoardGameResource($this->boardgame),
            'players' => $this->players->sortByDesc('pivot.winner')->sortByDesc('pivot.points')->map(function ($player) {
                return [
                    'id' => $player->id,
                    'nickname' => $player->nickname,
                    'avatarUrl' => config('app.url') . $player->avatar_path,
                    'winner' => $player->pivot->winner,
                    'points' => $player->pivot->points,
                ];
            })->toArray(),
            'photoUrl' => gamePhotoUrl($this),
            'date_played' => $this->date_played->format('d-m-Y'),
        ];
    }
}
