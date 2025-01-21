<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('game_scores', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->integer('score')->default(0); // 历史最高分
            $table->integer('computer_score')->default(0); // 电脑得分
            $table->integer('current_session_score')->default(0); // 当前局得分
            $table->integer('games_played')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_scores');
    }
}; 