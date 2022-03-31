<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['boardgame_id'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('winner');
    }

    public function boardgame()
    {
        return $this->belongsTo(Boardgame::class);
    }
}
