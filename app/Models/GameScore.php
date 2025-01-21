<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $fillable = [
        'player_name', 
        'score', 
        'computer_score',
        'current_session_score',
        'games_played'
    ];
} 