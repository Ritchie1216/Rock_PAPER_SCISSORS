<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors Game</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .game-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .score-board {
            display: flex;
            justify-content: space-around;
            margin: 2rem 0;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .score-item {
            text-align: center;
            padding: 0.5rem 1.5rem;
        }

        .score-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffd700;
        }

        .choices {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .choice-btn {
            background: none;
            border: 3px solid white;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .choice-btn:hover {
            transform: scale(1.1);
            border-color: #ffd700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        }

        .choice-btn:active {
            transform: scale(0.95);
        }

        .battle-area {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            margin: 2rem 0;
            min-height: 150px;
        }

        .player-choice, .computer-choice {
            font-size: 3rem;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .vs-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffd700;
        }

        #result {
            font-size: 2rem;
            text-align: center;
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 10px;
            opacity: 0;
        }

        .win {
            background: rgba(40, 167, 69, 0.3);
        }

        .lose {
            background: rgba(220, 53, 69, 0.3);
        }

        .draw {
            background: rgba(255, 193, 7, 0.3);
        }

        .history {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            max-height: 200px;
            overflow-y: auto;
        }

        .history-item {
            padding: 0.5rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.05);
        }

        .leaderboard {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2));
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .leaderboard h3 {
            color: #ffd700;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .leaderboard-table th {
            color: #fff;
            font-size: 1.1rem;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
        }

        .leaderboard-table td {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: none;
            transition: all 0.3s ease;
        }

        .leaderboard-row {
            transition: transform 0.3s ease;
        }

        .leaderboard-row:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.1);
        }

        .rank-1, .rank-2, .rank-3 {
            font-weight: bold;
            font-size: 1.1em;
        }

        .rank-1 td {
            background: linear-gradient(145deg, rgba(255, 215, 0, 0.15), rgba(255, 215, 0, 0.05));
        }

        .rank-2 td {
            background: linear-gradient(145deg, rgba(192, 192, 192, 0.15), rgba(192, 192, 192, 0.05));
        }

        .rank-3 td {
            background: linear-gradient(145deg, rgba(205, 127, 50, 0.15), rgba(205, 127, 50, 0.05));
        }

        .medal {
            font-size: 1.5em;
            margin-right: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .player-score {
            font-weight: bold;
            color: #ffd700;
        }

        .games-count {
            color: #a0a0a0;
            font-size: 0.9em;
        }

        .crown-icon {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2rem;
            color: #ffd700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .player-info {
            margin-bottom: 20px;
        }
        .score-display {
            font-size: 24px;
            color: #ffd700;
            margin: 10px 0;
        }
        #reset-game {
            margin-top: 1rem;
            width: 100%;
            padding: 0.5rem;
            font-size: 1.2rem;
        }

        /* 添加响应式设计 */
        @media (max-width: 768px) {
            .game-container {
                margin: 1rem;
                padding: 1rem;
            }

            .choices {
                gap: 1rem;
            }

            .choice-btn {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .battle-area {
                gap: 1.5rem;
                margin: 1rem 0;
            }

            .player-choice, .computer-choice {
                font-size: 2.5rem;
            }

            .score-board {
                flex-direction: column;
                gap: 1rem;
                padding: 0.5rem;
            }

            .score-item {
                width: 100%;
                padding: 0.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 8px;
            }

            .score-item div:first-child {
                margin-right: 1rem;
            }

            .score-number {
                font-size: 1.5rem;
            }

            .score-display {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                font-size: 1.2rem;
            }

            .leaderboard {
                padding: 15px;
                margin-top: 20px;
            }

            .leaderboard h3 {
                font-size: 1.5rem;
            }

            .leaderboard-table th,
            .leaderboard-table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .history {
                max-height: 150px;
            }

            .history-item {
                font-size: 0.9rem;
                padding: 0.3rem;
            }

            #result {
                font-size: 1.5rem;
                padding: 0.5rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .player-info {
                margin-bottom: 1rem;
            }

            #reset-game {
                padding: 0.3rem;
                font-size: 1rem;
            }

            .medal {
                font-size: 1.2em;
            }

            .crown-icon {
                font-size: 1.5rem;
                top: -10px;
            }
        }

        /* 添加更小屏幕的优化 */
        @media (max-width: 480px) {
            .choice-btn {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .player-choice, .computer-choice {
                font-size: 2rem;
            }

            .vs-text {
                font-size: 1.2rem;
            }

            .score-display {
                font-size: 1rem;
            }

            .leaderboard-table th,
            .leaderboard-table td {
                padding: 6px;
                font-size: 0.8rem;
            }

            .history-item {
                font-size: 0.8rem;
            }

            #result {
                font-size: 1.3rem;
            }

            .score-board {
                margin: 1rem 0;
            }

            .choices {
                margin: 1rem 0;
            }
        }

        /* 添加横屏模式优化 */
        @media (max-height: 600px) and (orientation: landscape) {
            .game-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                align-items: start;
            }

            h1 {
                grid-column: 1 / -1;
            }

            .player-info {
                grid-column: 1 / -1;
            }

            .score-board {
                margin: 0;
            }

            .battle-area {
                min-height: 100px;
            }

            .history {
                max-height: 200px;
            }

            .leaderboard {
                margin-top: 0;
            }
        }

        /* 添加动画优化 */
        @media (prefers-reduced-motion: reduce) {
            .animate__animated {
                animation: none !important;
                transition: none !important;
            }
        }

        /* 添加暗色模式支持 */
        @media (prefers-color-scheme: dark) {
            .game-container {
                background: rgba(0, 0, 0, 0.2);
            }

            .score-item {
                background: rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</head>
<body>
    <div class="game-container">
        <h1 class="text-center mb-4 animate__animated animate__fadeInDown">Rock Paper Scissors</h1>
        
        <div class="player-info">
            <input type="text" id="player-name" class="form-control" placeholder="Enter your name" required>
            <div class="score-display">
                Current Score: <span id="current-score">0</span>
                Best Score: <span id="highest-score">0</span>
                Games Left: <span id="remaining-games">15</span>
            </div>
            <button id="reset-game" class="btn btn-warning mt-2">Restart Game</button>
        </div>
        
        <div class="score-board animate__animated animate__fadeIn">
            <div class="score-item">
                <div>Player Score</div>
                <div class="score-number" id="player-score">0</div>
            </div>
            <div class="score-item">
                <div>Draws</div>
                <div class="score-number" id="draw-score">0</div>
            </div>
            <div class="score-item">
                <div>Computer Score</div>
                <div class="score-number" id="computer-score">0</div>
            </div>
        </div>

        <div class="choices">
            <button class="choice-btn animate__animated animate__bounceIn" data-choice="rock">✊</button>
            <button class="choice-btn animate__animated animate__bounceIn animate__delay-1s" data-choice="paper">✋</button>
            <button class="choice-btn animate__animated animate__bounceIn animate__delay-2s" data-choice="scissors">✌️</button>
        </div>

        <div class="battle-area">
            <div class="player-choice"></div>
            <div class="vs-text">VS</div>
            <div class="computer-choice"></div>
        </div>

        <div id="result"></div>

        <div class="history">
            <h4>Game History</h4>
            <div id="history-list"></div>
        </div>

        <div class="leaderboard">
            <h3>🏆 Leaderboard</h3>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Score</th>
                        <th>Games</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-body">
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let scores = {
            player: 0,
            computer: 0,
            draw: 0
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getMedalEmoji(rank) {
            switch(rank) {
                case 0: return '🥇';
                case 1: return '🥈';
                case 2: return '🥉';
                default: return `${rank + 1}.`;
            }
        }

        function updateLeaderboard() {
            $.get('/game/leaderboard', function(data) {
                const tbody = $('#leaderboard-body');
                tbody.empty();
                
                data.forEach((player, index) => {
                    const medal = getMedalEmoji(index);
                    const rankClass = index < 3 ? `rank-${index + 1}` : '';
                    
                    tbody.append(`
                        <tr class="leaderboard-row ${rankClass}" data-rank="${index + 1}">
                            <td>
                                <span class="medal">${medal}</span>
                            </td>
                            <td>
                                <span class="player-name">${player.player_name}</span>
                                ${index === 0 ? '<span class="crown-icon">👑</span>' : ''}
                            </td>
                            <td>
                                <span class="player-score">${player.score}</span>
                            </td>
                            <td>
                                <span class="games-count">${player.games_played} 局</span>
                            </td>
                        </tr>
                    `);
                });

                // 添加动画效果
                $('.leaderboard-row').each(function(index) {
                    $(this).css({
                        'animation': `fadeInUp ${0.3 + index * 0.1}s ease-out`
                    });
                });
            });
        }

        // 添加动画关键帧
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);

        // 初始加载排行榜
        updateLeaderboard();

        $('.choice-btn').click(function() {
            const playerName = $('#player-name').val() || 'Guest';
            const choice = $(this).data('choice');
            const playerEmoji = $(this).text();
            
            // 禁用按钮，防止重复点击
            $('.choice-btn').prop('disabled', true);
            
            // 显示玩家选择的动画
            $('.player-choice').text(playerEmoji)
                .css('opacity', 1)
                .addClass('animate__animated animate__bounceIn');

            $.post('/play', { 
                choice: choice,
                player_name: playerName
            }, function(response) {
                let computerChoiceText = '';
                switch(response.computerChoice) {
                    case 'rock': computerChoiceText = '✊'; break;
                    case 'paper': computerChoiceText = '✋'; break;
                    case 'scissors': computerChoiceText = '✌️'; break;
                }
                
                // 显示电脑选择的动画
                setTimeout(() => {
                    $('.computer-choice').text(computerChoiceText)
                        .css('opacity', 1)
                        .addClass('animate__animated animate__bounceIn');
                }, 500);

                // 显示结果
                setTimeout(() => {
                    const resultDiv = $('#result');
                    resultDiv.removeClass('win lose draw')
                        .text(response.result)
                        .css('opacity', 1)
                        .addClass('animate__animated animate__fadeIn');

                    // 更新分数显示
                    $('#current-score').text(response.currentScore);
                    $('#computer-score').text(response.computerScore);
                    $('#highest-score').text(response.highestScore);
                    $('#remaining-games').text(response.remainingGames);

                    // 更新游戏统计
                    scores.player = response.currentScore;
                    scores.computer = response.computerScore;
                    updateScores();

                    addToHistory(playerEmoji, computerChoiceText, response.result);
                    updateLeaderboard();
                    
                    // 重新启用按钮
                    $('.choice-btn').prop('disabled', false);

                    // 如果达到游戏次数上限
                    if (response.remainingGames <= 0) {
                        $('.choice-btn').prop('disabled', true);
                        alert(`Game Over!\nCurrent Score: ${response.currentScore}\nBest Score: ${response.highestScore}\nClick "Restart Game" to play again!`);
                    }
                }, 1000);
            }).fail(function(response) {
                if (response.status === 403) {
                    alert(response.responseJSON.error + 
                          '\nBest Score: ' + response.responseJSON.highestScore + 
                          '\nPlease enter a new name or reset the game.');
                    $('.choice-btn').prop('disabled', true);
                }
            });
        });

        function updateScores() {
            $('#player-score').text(scores.player);
            $('#computer-score').text(scores.computer);
        }

        function addToHistory(playerChoice, computerChoice, result) {
            const historyItem = $('<div>')
                .addClass('history-item animate__animated animate__fadeIn')
                .text(`Player ${playerChoice} vs Computer ${computerChoice} - ${result}`);
            
            $('#history-list').prepend(historyItem);
        }

        // 添加玩家名字输入监听
        $('#player-name').on('change', function() {
            const newPlayerName = $(this).val() || 'Guest';
            
            // 重置显示的分数
            scores = {
                player: 0,
                computer: 0,
                draw: 0
            };
            
            // 重置显示
            $('#current-score').text('0');
            $('#computer-score').text('0');
            $('#highest-score').text('0');
            $('#remaining-games').text('15');
            $('#result').css('opacity', 0);
            $('.player-choice').css('opacity', 0);
            $('.computer-choice').css('opacity', 0);
            
            // 清空历史记录
            $('#history-list').empty();
            
            // 启用按钮
            $('.choice-btn').prop('disabled', false);
            
            // 更新分数显示
            updateScores();
        });

        // 修改 reset-game 按钮处理
        $('#reset-game').click(function() {
            const playerName = $('#player-name').val();
            if (!playerName) {
                alert('Please enter your name first!');
                return;
            }

            $.post('/game/reset', { player_name: playerName }, function(response) {
                if (response.success) {
                    // 重置显示的分数
                    scores = {
                        player: 0,
                        computer: 0,
                        draw: 0
                    };
                    
                    // 重置显示
                    $('#current-score').text('0');
                    $('#computer-score').text('0');
                    $('#remaining-games').text('15');
                    $('#result').css('opacity', 0);
                    $('.player-choice').css('opacity', 0);
                    $('.computer-choice').css('opacity', 0);
                    
                    // 清空历史记录
                    $('#history-list').empty();
                    
                    // 启用按钮
                    $('.choice-btn').prop('disabled', false);
                    
                    // 更新分数显示
                    updateScores();
                    
                    alert(`Game Reset!\nBest Score: ${response.highestScore}`);
                }
            });
        });
    </script>
</body>
</html> 