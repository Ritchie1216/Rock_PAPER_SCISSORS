<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private $choices = ['rock', 'paper', 'scissors'];
    private $maxGames = 15;

    public function index()
    {
        $topPlayers = GameScore::orderBy('score', 'desc')
            ->take(10)
            ->get();
            
        return view('game.index', compact('topPlayers'));
    }

    public function play(Request $request)
    {
        $playerChoice = $request->input('choice');
        $computerChoice = $this->choices[array_rand($this->choices)];
        $playerName = $request->input('player_name', 'Guest');
        
        // 获取或创建玩家记录
        $score = GameScore::firstOrCreate(
            ['player_name' => $playerName],
            [
                'score' => 0,
                'computer_score' => 0,
                'current_session_score' => 0,
                'games_played' => 0
            ]
        );

        // 如果已经玩了15次，重置当前分数和游戏次数，保留最高分
        if ($score->games_played >= $this->maxGames) {
            $score->update([
                'current_session_score' => 0,
                'computer_score' => 0,
                'games_played' => 0
            ]);
        }

        $result = $this->determineWinner($playerChoice, $computerChoice);
        
        // 计算本次得分
        $currentScore = 0;
        $computerScore = 0;
        if (str_contains($result, 'You Win')) {
            $currentScore = 3; // 玩家赢了得3分
        } elseif (str_contains($result, 'Computer Wins')) {
            $computerScore = 3; // 电脑赢了得3分
        } else {
            $currentScore = 1; // 平局各得1分
            $computerScore = 1;
        }

        // 更新当前局得分和游戏次数
        $score->increment('current_session_score', $currentScore);
        $score->increment('computer_score', $computerScore);
        $score->increment('games_played');

        // 如果当前局得分超过历史最高分，更新最高分
        if ($score->current_session_score > $score->score) {
            $score->score = $score->current_session_score;
            $score->save();
        }
        
        $remainingGames = $this->maxGames - $score->games_played;
        
        // 刷新分数数据
        $score->refresh();
        
        return response()->json([
            'computerChoice' => $computerChoice,
            'result' => $result,
            'currentScore' => $score->current_session_score,
            'computerScore' => $score->computer_score,
            'highestScore' => $score->score,
            'gamesPlayed' => $score->games_played,
            'remainingGames' => $remainingGames
        ]);
    }

    public function getLeaderboard()
    {
        $leaderboard = GameScore::orderBy('score', 'desc')
            ->take(10)
            ->get();
            
        return response()->json($leaderboard);
    }

    public function resetSession(Request $request)
    {
        $playerName = $request->input('player_name');
        $score = GameScore::where('player_name', $playerName)->first();
        
        if ($score) {
            $score->update([
                'current_session_score' => 0,
                'computer_score' => 0,
                'games_played' => 0
            ]);
            
            return response()->json([
                'success' => true,
                'highestScore' => $score->score
            ]);
        }
        
        return response()->json(['success' => false]);
    }

    private function determineWinner($playerChoice, $computerChoice)
    {
        if ($playerChoice === $computerChoice) {
            return 'Draw!';
        }

        $winningCombos = [
            'rock' => 'scissors',
            'paper' => 'rock',
            'scissors' => 'paper'
        ];

        return ($winningCombos[$playerChoice] === $computerChoice) 
            ? 'You Win!' 
            : 'Computer Wins!';
    }
} 