<?php

namespace App\Models;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['boardgame_id', 'photo_path', 'date_played', 'club_id', 'thumbnail'];
    protected $dates = ['date_played'];

    public function players()
    {
        return $this->belongsToMany(User::class)->withPivot('winner', 'points');
    }

    public function boardgame()
    {
        return $this->belongsTo(Boardgame::class);
    }
}
