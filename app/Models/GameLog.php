<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLog extends Model
{
    protected $fillable = ['player_id', 'score'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
