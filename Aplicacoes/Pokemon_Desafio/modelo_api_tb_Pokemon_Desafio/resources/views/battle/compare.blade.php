<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏆 Resultado da Batalha - Pokédex Ultra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        *::-webkit-scrollbar {
            display: none;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0a0f1e; 
            color: white;
            overflow-x: hidden;
        }

        .bg-animate {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, #0f172a 0%, #070a13 100%);
            z-index: -1;
        }

        .blob-winner {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(234,179,8,0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2rem;
            transition: all 0.3s ease;
        }

        .winner-card {
            animation: winnerGlow 2s ease-in-out infinite;
        }

        @keyframes winnerGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(234, 179, 8, 0.3); border-color: rgba(234, 179, 8, 0.5); }
            50% { box-shadow: 0 0 60px rgba(234, 179, 8, 0.6); border-color: rgba(234, 179, 8, 0.8); }
        }

        .stat-bar {
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .vs-flame {
            animation: flamePulse 1s ease-in-out infinite;
        }

        @keyframes flamePulse {
            0%, 100% { text-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }
            50% { text-shadow: 0 0 30px rgba(239, 68, 68, 0.8); }
        }

        .type-badge {
            transition: all 0.2s ease;
        }

        .type-badge:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .floating-img {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .btn-rematch {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s;
        }

        .btn-rematch:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .pulse-text {
            animation: textPulse 1s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; text-shadow: 0 0 10px currentColor; }
        }
    </style>
</head>
<body>
    <div class="bg-animate"></div>

    <nav class="fixed top-0 w-full z-50 bg-black/30 backdrop-blur-md px-6 py-3">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('pokemon.show', 'pikachu') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full border-2 border-white/50"></div>
                <span class="text-xl font-bold tracking-tighter">⚡ Pokédex Ultra</span>
            </a>
            <a href="{{ route('battle.index') }}" class="btn-rematch px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2">
                <span>🔄</span> NOVA BATALHA
            </a>
        </div>
    </nav>

    <main class="relative pt-24 pb-16 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- VS Header -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 items-center">
                <!-- Pokémon 1 -->
                <div class="glass-card p-6 text-center" id="pokemon1-card">
                    <div class="floating-img">
                        <img src="{{ $pokemon1['image_url'] ?? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png' }}" 
                             class="w-40 h-40 mx-auto object-contain drop-shadow-2xl"
                             onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                    </div>
                    <h2 class="text-2xl font-bold capitalize mt-3">{{ ucfirst($pokemon1['name'] ?? 'Pokémon') }}</h2>
                    <div class="flex justify-center gap-2 mt-2 flex-wrap">
                        @if(isset($pokemon1['types']) && is_array($pokemon1['types']))
                            @foreach($pokemon1['types'] as $type)
                            <span class="type-badge px-3 py-1 rounded-full text-xs font-bold capitalize"
                                  style="background: {{ $typeColors[$type] ?? '#A8A878' }}; color: {{ in_array($type, ['electric', 'ice', 'normal', 'steel', 'ground', 'fairy']) ? '#000' : '#fff' }}">
                                {{ $type }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                    @if(isset($battleResult['winner']) && $battleResult['winner'] == 1)
                    <div class="mt-3 inline-block px-4 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-bold pulse-text">
                        🏆 CAMPEÃO
                    </div>
                    @endif
                </div>

                <!-- VS Central -->
                <div class="text-center">
                    <div class="text-7xl font-black vs-flame">
                        ⚔️
                    </div>
                    <div class="text-3xl font-black text-red-500 my-2">VS</div>
                    <div class="glass-card p-4 mt-2">
                        <div class="text-4xl font-bold {{ isset($battleResult['winner']) && $battleResult['winner'] == 1 ? 'text-yellow-400' : 'text-red-400' }}">
                            {{ ucfirst($battleResult['winner_name'] ?? '?') }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1">VENCEU!</p>
                    </div>
                </div>

                <!-- Pokémon 2 -->
                <div class="glass-card p-6 text-center" id="pokemon2-card">
                    <div class="floating-img">
                        <img src="{{ $pokemon2['image_url'] ?? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png' }}" 
                             class="w-40 h-40 mx-auto object-contain drop-shadow-2xl"
                             onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                    </div>
                    <h2 class="text-2xl font-bold capitalize mt-3">{{ ucfirst($pokemon2['name'] ?? 'Pokémon') }}</h2>
                    <div class="flex justify-center gap-2 mt-2 flex-wrap">
                        @if(isset($pokemon2['types']) && is_array($pokemon2['types']))
                            @foreach($pokemon2['types'] as $type)
                            <span class="type-badge px-3 py-1 rounded-full text-xs font-bold capitalize"
                                  style="background: {{ $typeColors[$type] ?? '#A8A878' }}; color: {{ in_array($type, ['electric', 'ice', 'normal', 'steel', 'ground', 'fairy']) ? '#000' : '#fff' }}">
                                {{ $type }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                    @if(isset($battleResult['winner']) && $battleResult['winner'] == 2)
                    <div class="mt-3 inline-block px-4 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-bold pulse-text">
                        🏆 CAMPEÃO
                    </div>
                    @endif
                </div>
            </div>

            <!-- Radar Chart -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-xl font-bold mb-4 text-center bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                    📊 COMPARAÇÃO DE STATUS
                </h3>
                <canvas id="radarChart" style="max-height: 380px; width: 100%"></canvas>
            </div>

            <!-- Stats Comparison -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Pokémon 1 Stats -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold mb-4 text-blue-400 flex items-center gap-2">
                        <span>🔵</span> {{ ucfirst($pokemon1['name'] ?? 'Pokémon 1') }}
                    </h3>
                    <div class="space-y-3">
                        @php
                            $statLabels = ['hp' => '❤️ HP', 'attack' => '⚔️ Ataque', 'defense' => '🛡️ Defesa', 
                                           'special-attack' => '🔮 Atq. Especial', 'special-defense' => '✨ Def. Especial', 
                                           'speed' => '💨 Velocidade'];
                            $stats1 = [];
                            if(isset($pokemon1['stats']) && is_array($pokemon1['stats'])) {
                                foreach($pokemon1['stats'] as $stat) {
                                    if(is_array($stat)) {
                                        $statName = $stat['stat']['name'] ?? $stat['stat'] ?? '';
                                        $statValue = $stat['base_stat'] ?? 50;
                                        $stats1[$statName] = $statValue;
                                    }
                                }
                            }
                        @endphp
                        @foreach($statLabels as $key => $label)
                            @php
                                $value = $stats1[$key] ?? 50;
                                $percent = ($value / 255) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>{{ $label }}</span>
                                    <span class="font-bold text-blue-400">{{ $value }}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="stat-bar bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full" style="width: 0%" data-width="{{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pokémon 2 Stats -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold mb-4 text-red-400 flex items-center gap-2">
                        <span>🔴</span> {{ ucfirst($pokemon2['name'] ?? 'Pokémon 2') }}
                    </h3>
                    <div class="space-y-3">
                        @php
                            $stats2 = [];
                            if(isset($pokemon2['stats']) && is_array($pokemon2['stats'])) {
                                foreach($pokemon2['stats'] as $stat) {
                                    if(is_array($stat)) {
                                        $statName = $stat['stat']['name'] ?? $stat['stat'] ?? '';
                                        $statValue = $stat['base_stat'] ?? 50;
                                        $stats2[$statName] = $statValue;
                                    }
                                }
                            }
                        @endphp
                        @foreach($statLabels as $key => $label)
                            @php
                                $value = $stats2[$key] ?? 50;
                                $percent = ($value / 255) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>{{ $label }}</span>
                                    <span class="font-bold text-red-400">{{ $value }}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="stat-bar bg-gradient-to-r from-red-500 to-orange-500 h-full rounded-full" style="width: 0%" data-width="{{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Efetividade e Score -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-card p-6">
                    <h3 class="font-bold mb-3 text-center">⚔️ EFETIVIDADE</h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-xs text-gray-400">{{ ucfirst($pokemon1['name'] ?? 'P1') }} vs {{ ucfirst($pokemon2['name'] ?? 'P2') }}</p>
                            <div class="text-3xl font-bold" id="effectiveness1">
                                {{ isset($battleResult['effectiveness1']) ? number_format($battleResult['effectiveness1'], 2) : '1.00' }}x
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">{{ ucfirst($pokemon2['name'] ?? 'P2') }} vs {{ ucfirst($pokemon1['name'] ?? 'P1') }}</p>
                            <div class="text-3xl font-bold" id="effectiveness2">
                                {{ isset($battleResult['effectiveness2']) ? number_format($battleResult['effectiveness2'], 2) : '1.00' }}x
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h3 class="font-bold mb-3 text-center">🏆 SCORE DE BATALHA</h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-4xl font-bold text-blue-400" id="score1">
                                {{ isset($battleResult['score1']) ? number_format($battleResult['score1'], 1) : '50.0' }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ ucfirst($pokemon1['name'] ?? 'P1') }}</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-red-400" id="score2">
                                {{ isset($battleResult['score2']) ? number_format($battleResult['score2'], 1) : '50.0' }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ ucfirst($pokemon2['name'] ?? 'P2') }}</p>
                        </div>
                    </div>
                    <div class="mt-3 h-2 bg-gray-700 rounded-full overflow-hidden">
                        @php
                            $score1 = $battleResult['score1'] ?? 50;
                            $score2 = $battleResult['score2'] ?? 50;
                            $totalScore = $score1 + $score2;
                            $percent1 = $totalScore > 0 ? ($score1 / $totalScore) * 100 : 50;
                        @endphp
                        <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" style="width: {{ $percent1 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Dica -->
            <div class="text-center mt-8">
                <p class="text-xs text-gray-600">
                    💡 A vitória é calculada com base em: Stats base + Efetividade de tipos + Velocidade + Fator sorte
                </p>
            </div>
        </div>
    </main>

    <script>
        // Cores dos tipos
        const typeColors = {
            normal: '#A8A878', fire: '#F08030', water: '#6890F0', electric: '#F8D030',
            grass: '#78C850', ice: '#98D8D8', fighting: '#C03028', poison: '#A040A0',
            ground: '#E0C068', flying: '#A890F0', psychic: '#F85888', bug: '#A8B820',
            rock: '#B8A038', ghost: '#705898', dragon: '#7038F8', dark: '#705848',
            steel: '#B8B8D0', fairy: '#EE99AC'
        };

        // Aplicar classe winner-card após carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar classe winner-card ao vencedor
            const winner = {{ $battleResult['winner'] ?? 0 }};
            if (winner === 1) {
                document.getElementById('pokemon1-card')?.classList.add('winner-card');
            } else if (winner === 2) {
                document.getElementById('pokemon2-card')?.classList.add('winner-card');
            }

            // Aplicar cores nas efetividades
            const eff1 = {{ $battleResult['effectiveness1'] ?? 1 }};
            const eff2 = {{ $battleResult['effectiveness2'] ?? 1 }};
            
            const eff1El = document.getElementById('effectiveness1');
            const eff2El = document.getElementById('effectiveness2');
            
            if (eff1El) {
                if (eff1 > 1) eff1El.classList.add('text-green-400');
                else if (eff1 < 1) eff1El.classList.add('text-red-400');
                else eff1El.classList.add('text-gray-400');
            }
            
            if (eff2El) {
                if (eff2 > 1) eff2El.classList.add('text-green-400');
                else if (eff2 < 1) eff2El.classList.add('text-red-400');
                else eff2El.classList.add('text-gray-400');
            }
        });

        // Gráfico Radar
        const radarData = {!! json_encode($comparisonData ?? ['labels' => ['HP', 'Ataque', 'Defesa', 'Atq. Especial', 'Def. Especial', 'Velocidade'], 'datasets' => []]) !!};
        
        if (radarData && radarData.datasets && radarData.datasets.length > 0) {
            const ctx = document.getElementById('radarChart').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: radarData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 255,
                            ticks: { stepSize: 51, color: '#9ca3af', backdropColor: 'transparent' },
                            grid: { color: 'rgba(255, 255, 255, 0.08)' },
                            angleLines: { color: 'rgba(255, 255, 255, 0.08)' },
                            pointLabels: { color: '#9ca3af', font: { size: 11 } }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#fff', font: { size: 12, weight: 'bold' } }, position: 'top' },
                        tooltip: { backgroundColor: 'rgba(0,0,0,0.9)', titleColor: '#fff', bodyColor: '#ccc' }
                    }
                }
            });
        }

        // Animar barras
        document.querySelectorAll('.stat-bar').forEach(bar => {
            const width = bar.getAttribute('data-width');
            if (width) {
                setTimeout(() => { bar.style.width = width; }, 200);
            }
        });

        // Confetes para o vencedor
        @if(isset($battleResult['winner']) && ($battleResult['winner'] == 1 || $battleResult['winner'] == 2))
        (function() {
            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#ffaa00', '#ff44aa', '#ff6600', '#ff0066'];
            
            for(let i = 0; i < 150; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = Math.random() * 12 + 4 + 'px';
                confetti.style.height = Math.random() * 12 + 4 + 'px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = '-20px';
                confetti.style.opacity = '0.9';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '999';
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                confetti.style.boxShadow = '0 0 5px rgba(255,255,255,0.5)';
                confetti.style.animation = `fall ${Math.random() * 3 + 2}s linear forwards`;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 5000);
            }
            
            // Se for vencedor, adicionar um brilho extra
            const winnerCard = document.querySelector('.winner-card');
            if (winnerCard) {
                const glow = document.createElement('div');
                glow.className = 'blob-winner';
                glow.style.top = winnerCard.offsetTop + 'px';
                glow.style.left = winnerCard.offsetLeft + 'px';
                document.body.appendChild(glow);
                setTimeout(() => glow.remove(), 3000);
            }
        })();
        @endif
    </script>
</body>
</html>