<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚔️ Arena de Batalha - Pokédex Ultra</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
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

        .blob-red {
            position: absolute;
            width: 600px; height: 600px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            filter: blur(120px);
            border-radius: 50%;
            opacity: 0.15;
            animation: moveRed 25s infinite alternate;
        }

        .blob-blue {
            position: absolute;
            width: 500px; height: 500px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            filter: blur(120px);
            border-radius: 50%;
            opacity: 0.1;
            bottom: 0;
            right: 0;
            animation: moveBlue 20s infinite alternate;
        }

        @keyframes moveRed {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(25%, 15%); }
        }

        @keyframes moveBlue {
            from { transform: translate(10%, 10%); }
            to { transform: translate(-15%, -20%); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .pokemon-card {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pokemon-card.empty {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.02);
        }

        .pokemon-card.p1-selected {
            border: 3px solid #3b82f6;
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.3);
            animation: borderPulseBlue 1.5s ease-in-out infinite;
        }

        .pokemon-card.p2-selected {
            border: 3px solid #ef4444;
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.3);
            animation: borderPulseRed 1.5s ease-in-out infinite;
        }

        @keyframes borderPulseBlue {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 50px rgba(59, 130, 246, 0.6); }
        }

        @keyframes borderPulseRed {
            0%, 100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 50px rgba(239, 68, 68, 0.6); }
        }

        .vs-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 900;
            font-style: italic;
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.5);
            animation: vsPulse 1.5s ease-in-out infinite;
        }

        @keyframes vsPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(239, 68, 68, 0.5); }
            50% { transform: scale(1.05); box-shadow: 0 0 60px rgba(239, 68, 68, 0.8); }
        }

        .battle-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .battle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .battle-btn:hover::before {
            left: 100%;
        }

        .battle-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            animation: none;
        }

        .search-result {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-result:hover {
            background: rgba(59, 130, 246, 0.15);
            transform: translateX(8px);
            border-left: 3px solid #3b82f6;
        }

        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .type-badge {
            transition: all 0.2s ease;
        }

        .type-badge:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .hp-bar {
            transition: width 0.8s ease-out;
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .search-input {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(59, 130, 246, 0.2);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Modal de Evolução 3D */
        .evolution-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .evolution-modal.active {
            display: flex;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; backdrop-filter: blur(0px); }
            to { opacity: 1; backdrop-filter: blur(20px); }
        }

        .evolution-carousel {
            position: relative;
            width: 100%;
            max-width: 900px;
            perspective: 1000px;
        }

        .evolution-cards {
            position: relative;
            width: 100%;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .evolution-card-item {
            position: absolute;
            width: 280px;
            height: 380px;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 2rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .evolution-card-item:hover {
            transform: scale(1.05);
            border-color: #3b82f6;
        }

        .evolution-card-item.center {
            transform: scale(1.1);
            z-index: 10;
            border-color: #fbbf24;
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.3);
        }

        .evolution-card-item.left {
            transform: translateX(-120%) rotateY(30deg) scale(0.85);
            filter: blur(2px);
            opacity: 0.7;
        }

        .evolution-card-item.right {
            transform: translateX(120%) rotateY(-30deg) scale(0.85);
            filter: blur(2px);
            opacity: 0.7;
        }

        .evolution-card-item.hidden {
            display: none;
        }

        .evolution-img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 1rem;
        }

        .evolution-info {
            padding: 1.5rem;
            text-align: center;
        }

        .evolution-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            text-transform: capitalize;
        }

        .evolution-stats {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 20;
        }

        .nav-arrow:hover {
            background: rgba(59, 130, 246, 0.5);
            transform: translateY(-50%) scale(1.1);
        }

        .nav-arrow.left {
            left: 10px;
        }

        .nav-arrow.right {
            right: 10px;
        }

        .evolution-close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 2rem;
            cursor: pointer;
            color: #9ca3af;
            transition: all 0.3s;
            z-index: 20;
        }

        .evolution-close:hover {
            color: #ef4444;
            transform: scale(1.1);
        }

        .evolution-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #fbbf24, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .evo-button {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            transition: all 0.3s;
        }

        .evo-button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
        }

        .btn-voltar {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s;
        }

        .btn-voltar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        /* Toast corrigido */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: rgba(17, 24, 39, 0.95);
            backdrop-filter: blur(12px);
            color: white;
            padding: 12px 20px;
            border-radius: 16px;
            border-left: 4px solid;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(120%);
            opacity: 0;
            font-weight: 500;
            font-size: 0.875rem;
            pointer-events: none;
        }

        .toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-notification.success {
            border-left-color: #10b981;
        }

        .toast-notification.error {
            border-left-color: #ef4444;
        }

        .toast-notification.info {
            border-left-color: #3b82f6;
        }

        .toast-notification.warning {
            border-left-color: #f59e0b;
        }
    </style>
</head>
<body>
    <div class="bg-animate">
        <div class="blob-red"></div>
        <div class="blob-blue"></div>
    </div>

    <nav class="fixed top-0 w-full z-50 bg-black/30 backdrop-blur-md px-6 py-3">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('pokemon.show', 'pikachu') }}" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full border-2 border-white/50 group-hover:scale-110 transition-transform"></div>
                <span class="text-xl font-extrabold tracking-tighter uppercase italic">Pokédex <span class="text-blue-400">Ultra</span></span>
            </a>
            <a href="{{ route('pokemon.show', 'pikachu') }}" class="btn-voltar text-white font-bold py-2 px-5 rounded-xl transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                VOLTAR
            </a>
        </div>
    </nav>

    <main class="relative pt-28 pb-16 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <div class="inline-block">
                    <div class="text-7xl mb-3">⚔️🔥💧</div>
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-red-400 via-orange-400 to-yellow-400 bg-clip-text text-transparent">
                    ARENA DE BATALHA
                </h1>
                <p class="text-gray-400 mt-2 text-lg">Escolha dois guerreiros e descubra quem é o mais forte!</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-1">
                    <div class="text-center mb-3">
                        <span class="text-xs font-bold text-blue-400 bg-blue-500/20 px-3 py-1 rounded-full uppercase tracking-wider">🔵 Guerreiro 1</span>
                    </div>
                    <div id="pokemon1-container" class="pokemon-card glass-card p-6 empty" onclick="openSearchModal(1)">
                        <div id="pokemon1-placeholder" class="text-center py-12">
                            <svg class="w-24 h-24 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">Clique para escolher</p>
                            <p class="text-gray-600 text-xs mt-1">Seu primeiro combatente</p>
                        </div>
                        <div id="pokemon1-selected" class="hidden"></div>
                    </div>
                </div>

                <div class="lg:col-span-1 flex items-center justify-center">
                    <div class="text-center">
                        <div class="vs-circle mx-auto mb-4">VS</div>
                        <button id="battle-btn" class="battle-btn text-white font-bold py-4 px-8 rounded-2xl transition-all transform hover:scale-105 disabled:opacity-50 disabled:scale-100 flex items-center gap-3 text-lg" disabled>
                            <span>⚡</span><span>BATALHAR!</span><span>⚡</span>
                        </button>
                        <p class="text-xs text-gray-500 mt-3">Selecione ambos os Pokémon para liberar</p>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="text-center mb-3">
                        <span class="text-xs font-bold text-red-400 bg-red-500/20 px-3 py-1 rounded-full uppercase tracking-wider">🔴 Guerreiro 2</span>
                    </div>
                    <div id="pokemon2-container" class="pokemon-card glass-card p-6 empty" onclick="openSearchModal(2)">
                        <div id="pokemon2-placeholder" class="text-center py-12">
                            <svg class="w-24 h-24 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">Clique para escolher</p>
                            <p class="text-gray-600 text-xs mt-1">Seu segundo combatente</p>
                        </div>
                        <div id="pokemon2-selected" class="hidden"></div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <p class="text-xs text-gray-600">💡 Dica: Pokémon customizados também podem batalhar! Eles são avaliados igualmente.</p>
            </div>
        </div>
    </main>

    <!-- Modal de Busca -->
    <div id="search-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 hidden" onclick="if(event.target===this) closeSearchModal()">
        <div class="modal-content glass-card p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">🔍 Escolher Pokémon</h3>
                <button onclick="closeSearchModal()" class="text-gray-400 hover:text-white text-2xl">&times;</button>
            </div>
            <div class="relative mb-4">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="pokemon-search-input" placeholder="Digite o nome do Pokémon..." class="search-input w-full px-10 py-3 rounded-xl text-white placeholder-gray-500">
            </div>
            <div id="search-results" class="max-h-96 overflow-y-auto space-y-2">
                <div class="text-center text-gray-500 py-8">
                    <div class="loading-spinner mx-auto mb-3"></div>
                    <p class="text-sm">Digite para buscar...</p>
                </div>
            </div>
            <button onclick="closeSearchModal()" class="w-full mt-4 glass-card hover:bg-white/10 py-2 rounded-xl transition font-semibold">Cancelar</button>
        </div>
    </div>

    <!-- Modal de Evolução para Pokémon Oficiais (3D Alt+Tab Style) -->
    <div id="evolution-modal" class="evolution-modal">
        <div class="evolution-close" onclick="closeEvolutionModal()">&times;</div>
        <div class="evolution-carousel">
            <h2 class="evolution-title">🔄 CADEIA DE EVOLUÇÃO</h2>
            <div class="nav-arrow left" onclick="navigateEvolution(-1)">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
            <div class="nav-arrow right" onclick="navigateEvolution(1)">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div id="evolution-cards" class="evolution-cards"></div>
            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm">← → Navegue | Clique para evoluir | ESC para fechar</p>
            </div>
        </div>
    </div>

    <!-- Modal de Evolução para Pokémon Customizados (3D Alt+Tab Style com cores diferentes) -->
    <div id="evolution-custom-modal" class="evolution-modal">
        <div class="evolution-close" onclick="closeCustomEvolutionModal()">&times;</div>
        <div class="evolution-carousel">
            <h2 class="evolution-title" style="background: linear-gradient(135deg, #a855f7, #d946ef); -webkit-background-clip: text;">⭐ EVOLUÇÕES CUSTOMIZADAS</h2>
            <div class="nav-arrow left" onclick="navigateCustomEvolution(-1)">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
            <div class="nav-arrow right" onclick="navigateCustomEvolution(1)">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div id="custom-evolution-cards" class="evolution-cards"></div>
            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm">← → Navegue | Clique para evoluir | ESC para fechar</p>
            </div>
        </div>
    </div>

    <script>
        let selectedSlot = null;
        let pokemon1 = null;
        let pokemon2 = null;
        let searchTimeout = null;
        let evolutionChain = [];
        let currentEvolutionIndex = 0;
        
        // Para customizados
        let customEvolutionChain = [];
        let currentCustomEvolutionIndex = 0;
        let currentCustomPokemonSlot = null;

        const typeColors = {
            normal: '#A8A878', fire: '#F08030', water: '#6890F0', electric: '#F8D030',
            grass: '#78C850', ice: '#98D8D8', fighting: '#C03028', poison: '#A040A0',
            ground: '#E0C068', flying: '#A890F0', psychic: '#F85888', bug: '#A8B820',
            rock: '#B8A038', ghost: '#705898', dragon: '#7038F8', dark: '#705848',
            steel: '#B8B8D0', fairy: '#EE99AC'
        };

        const darkTextTypes = ['electric', 'ice', 'normal', 'steel', 'ground', 'fairy'];

        function showToast(message, type = 'success') {
            let toast = document.querySelector('.toast-notification');
            if (!toast) {
                toast = document.createElement('div');
                toast.className = 'toast-notification';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.className = `toast-notification ${type}`;
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function openSearchModal(slot) {
            selectedSlot = slot;
            const modal = document.getElementById('search-modal');
            const input = document.getElementById('pokemon-search-input');
            modal.classList.remove('hidden');
            input.value = '';
            document.getElementById('search-results').innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-sm">Digite o nome para buscar</p>
                </div>
            `;
            input.focus();
        }

        function closeSearchModal() {
            document.getElementById('search-modal').classList.add('hidden');
            selectedSlot = null;
        }

        document.getElementById('pokemon-search-input').addEventListener('input', function(e) {
            if (searchTimeout) clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = e.target.value.trim();
                if (query.length < 2) {
                    document.getElementById('search-results').innerHTML = `<div class="text-center text-gray-500 py-8"><p class="text-sm">Digite pelo menos 2 caracteres...</p></div>`;
                    return;
                }
                
                document.getElementById('search-results').innerHTML = `<div class="text-center text-gray-500 py-8"><div class="loading-spinner mx-auto mb-3"></div><p class="text-sm">Buscando...</p></div>`;
                
                fetch(`/battle/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            document.getElementById('search-results').innerHTML = `<div class="text-center text-gray-500 py-8"><p class="text-sm">Nenhum Pokémon encontrado para "${query}"</p></div>`;
                            return;
                        }
                        
                        document.getElementById('search-results').innerHTML = data.map(p => `
                            <div onclick="selectPokemon(${selectedSlot}, '${p.id}', '${p.name}', '${p.image_url || ''}', ${p.is_custom})" 
                                 class="search-result glass-card p-3 rounded-xl flex items-center gap-3 cursor-pointer transition border-l-2 border-transparent">
                                <img src="${p.image_url || 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'}" 
                                     class="w-14 h-14 object-contain floating"
                                     onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                                <div class="flex-1">
                                    <p class="font-bold text-lg capitalize">${p.name}</p>
                                    <p class="text-xs ${p.is_custom ? 'text-purple-400' : 'text-blue-400'}">${p.is_custom ? '⭐ Customizado' : '🎮 Oficial'}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        `).join('');
                    });
            }, 300);
        });

       function selectPokemon(slot, id, name, imageUrl, isCustom) {
    showToast(`Carregando ${name}...`, 'info');
    
    fetch(`/api/pokemon/${id}`)
        .then(res => res.json())
        .then(pokemon => {
            // DEBUG - mostra no console do navegador
            console.log('========== POKEMON CARREGADO ==========');
            console.log('Nome:', pokemon.name);
            console.log('É custom?', pokemon.is_custom);
            console.log('Evoluções:', pokemon.evolutions);
            console.log('Objeto completo:', pokemon);
            console.log('=======================================');
            
            if (slot === 1) {
                pokemon1 = pokemon;
                renderSelectedPokemon(1, pokemon);
                document.getElementById('pokemon1-container').classList.add('p1-selected');
                document.getElementById('pokemon1-container').classList.remove('empty');
            } else {
                pokemon2 = pokemon;
                renderSelectedPokemon(2, pokemon);
                document.getElementById('pokemon2-container').classList.add('p2-selected');
                document.getElementById('pokemon2-container').classList.remove('empty');
            }
            
            closeSearchModal();
            showToast(`✅ ${name} entrou na arena!`, 'success');
            
            const battleBtn = document.getElementById('battle-btn');
            if (pokemon1 && pokemon2) {
                battleBtn.disabled = false;
                battleBtn.classList.add('animate-pulse');
                showToast('⚔️ Dois guerreiros estão prontos! Clique em BATALHAR!', 'success');
            }
        });
}

        function renderSelectedPokemon(slot, pokemon) {
            const placeholder = document.getElementById(`pokemon${slot}-placeholder`);
            const selected = document.getElementById(`pokemon${slot}-selected`);
            
            placeholder.classList.add('hidden');
            selected.classList.remove('hidden');
            
            const types = Array.isArray(pokemon.types) ? pokemon.types : [];
            let hpValue = 50;
            if (pokemon.stats) {
                const hpStat = pokemon.stats.find(s => s.stat?.name === 'hp' || s.stat === 'hp');
                hpValue = hpStat ? (hpStat.base_stat || 50) : 50;
            }
            const hpPercent = (hpValue / 255) * 100;
            
            const hasEvolution = !pokemon.is_custom && pokemon.id;
            const hasCustomEvolution = pokemon.is_custom && pokemon.evolutions && pokemon.evolutions.length > 0;
            
            selected.innerHTML = `
                <div class="text-center floating">
                    <div class="relative inline-block">
                        <img src="${pokemon.image_url}" class="w-36 h-36 mx-auto object-contain drop-shadow-2xl" 
                             onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                        ${pokemon.is_custom ? '<div class="absolute -top-2 -right-2 text-xl">⭐</div>' : ''}
                        ${hasEvolution ? `<button onclick="event.stopPropagation(); openEvolutionModal(${slot}, '${pokemon.id}')" class="absolute -bottom-2 -right-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg transition-all">EVO</button>` : ''}
                        ${hasCustomEvolution ? `<button onclick="event.stopPropagation(); openCustomEvolutionModal(${slot})" class="absolute -bottom-2 -right-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg transition-all">EVO</button>` : ''}
                    </div>
                    <h3 class="text-2xl font-bold capitalize mt-2">${pokemon.name}</h3>
                    <div class="flex justify-center gap-2 mt-2 flex-wrap">
                        ${types.map(t => `<span class="type-badge px-3 py-1 rounded-full text-xs font-bold capitalize" style="background: ${typeColors[t] || '#A8A878'}; color: ${darkTextTypes.includes(t) ? '#000' : '#fff'}">${t}</span>`).join('')}
                    </div>
                    <div class="mt-3 text-sm text-gray-400">
                        <span>📏 ${(pokemon.height / 10).toFixed(1)}m</span>
                        <span class="mx-2">•</span>
                        <span>⚖️ ${(pokemon.weight / 10).toFixed(1)}kg</span>
                    </div>
                    <div class="mt-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400">❤️ HP</span>
                            <span class="text-green-400 font-bold">${hpValue}</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                            <div class="hp-bar bg-gradient-to-r from-green-500 to-green-400 h-full rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    <button onclick="event.stopPropagation(); removePokemon(${slot})" class="mt-4 text-red-400 text-sm hover:text-red-300 transition flex items-center justify-center gap-1 mx-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Remover
                    </button>
                </div>
            `;
            
            setTimeout(() => {
                const hpBar = selected.querySelector('.hp-bar');
                if (hpBar) hpBar.style.width = `${hpPercent}%`;
            }, 100);
        }

        function removePokemon(slot) {
            if (slot === 1) {
                pokemon1 = null;
                document.getElementById('pokemon1-placeholder').classList.remove('hidden');
                document.getElementById('pokemon1-selected').classList.add('hidden');
                document.getElementById('pokemon1-container').classList.remove('p1-selected');
                document.getElementById('pokemon1-container').classList.add('empty');
            } else {
                pokemon2 = null;
                document.getElementById('pokemon2-placeholder').classList.remove('hidden');
                document.getElementById('pokemon2-selected').classList.add('hidden');
                document.getElementById('pokemon2-container').classList.remove('p2-selected');
                document.getElementById('pokemon2-container').classList.add('empty');
            }
            showToast('Pokémon removido da arena', 'info');
            const battleBtn = document.getElementById('battle-btn');
            battleBtn.disabled = true;
            battleBtn.classList.remove('animate-pulse');
        }

        document.getElementById('battle-btn').addEventListener('click', function() {
            if (pokemon1 && pokemon2) {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span>⏳</span><span>PREPARANDO BATALHA...</span><span>⚡</span>';
                setTimeout(() => window.location.href = `/battle/compare/${pokemon1.id}/${pokemon2.id}`, 500);
            }
        });

        // ==================== EVOLUÇÃO PARA POKÉMON OFICIAIS (3D) ====================
        
        async function openEvolutionModal(slot, pokemonId) {
            currentCustomPokemonSlot = slot;
            showToast('🔄 Carregando cadeia de evolução...', 'info');
            
            try {
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon-species/${pokemonId}`);
                const species = await response.json();
                const evolutionUrl = species.evolution_chain.url;
                const evolutionResponse = await fetch(evolutionUrl);
                const evolutionData = await evolutionResponse.json();
                
                evolutionChain = await extractEvolutionChain(evolutionData.chain);
                currentEvolutionIndex = evolutionChain.findIndex(e => e.id == pokemonId);
                if (currentEvolutionIndex === -1) currentEvolutionIndex = 0;
                
                renderEvolutionCarousel();
                const modal = document.getElementById('evolution-modal');
                modal.classList.add('active');
                document.addEventListener('keydown', handleEvolutionKeydown);
            } catch (error) {
                showToast('Não foi possível carregar a cadeia de evolução', 'error');
            }
        }
        
        async function extractEvolutionChain(chain, chainArray = []) {
            const pokemonName = chain.species.name;
            const pokemonResponse = await fetch(`https://pokeapi.co/api/v2/pokemon/${pokemonName}`);
            const pokemonData = await pokemonResponse.json();
            
            chainArray.push({
                id: pokemonData.id,
                name: pokemonData.name,
                image: pokemonData.sprites.other['official-artwork'].front_default || pokemonData.sprites.front_default,
                types: pokemonData.types.map(t => t.type.name)
            });
            
            if (chain.evolves_to && chain.evolves_to.length > 0) {
                for (const evolution of chain.evolves_to) {
                    await extractEvolutionChain(evolution, chainArray);
                }
            }
            return chainArray;
        }
        
        function renderEvolutionCarousel() {
            const container = document.getElementById('evolution-cards');
            if (!container) return;
            
            container.innerHTML = evolutionChain.map((evo, index) => {
                let positionClass = '';
                if (index === currentEvolutionIndex) positionClass = 'center';
                else if (index === currentEvolutionIndex - 1) positionClass = 'left';
                else if (index === currentEvolutionIndex + 1) positionClass = 'right';
                else positionClass = 'hidden';
                
                return `
                    <div class="evolution-card-item ${positionClass}" onclick="selectEvolution(${index})" data-index="${index}">
                        <img src="${evo.image}" class="evolution-img" onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                        <div class="evolution-info">
                            <div class="evolution-name">${evo.name}</div>
                            <div class="flex justify-center gap-1 mt-1 flex-wrap">
                                ${evo.types.map(t => `<span class="type-badge px-2 py-0.5 rounded-full text-xs capitalize" style="background: ${typeColors[t] || '#A8A878'}; color: ${darkTextTypes.includes(t) ? '#000' : '#fff'}">${t}</span>`).join('')}
                            </div>
                            <div class="evolution-stats mt-2">#${String(evo.id).padStart(3, '0')}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function navigateEvolution(direction) {
            let newIndex = currentEvolutionIndex + direction;
            if (newIndex < 0) newIndex = 0;
            if (newIndex >= evolutionChain.length) newIndex = evolutionChain.length - 1;
            if (newIndex !== currentEvolutionIndex) {
                currentEvolutionIndex = newIndex;
                renderEvolutionCarousel();
            }
        }
        
        function selectEvolution(index) {
            const selectedEvo = evolutionChain[index];
            if (selectedEvo) {
                showToast(`✨ Evoluindo para ${selectedEvo.name}!`, 'success');
                closeEvolutionModal();
                replacePokemonInSlot(currentCustomPokemonSlot, selectedEvo.id, selectedEvo.name, selectedEvo.image);
            }
        }
        
        function closeEvolutionModal() {
            const modal = document.getElementById('evolution-modal');
            modal.classList.remove('active');
            document.removeEventListener('keydown', handleEvolutionKeydown);
        }

        // ==================== EVOLUÇÃO PARA POKÉMON CUSTOMIZADOS (3D com cores diferentes) ====================
        
        function openCustomEvolutionModal(slot) {
            const pokemon = slot === 1 ? pokemon1 : pokemon2;
            currentCustomPokemonSlot = slot;
            
            if (!pokemon.evolutions || pokemon.evolutions.length === 0) {
                showToast('❌ Este Pokémon não tem evoluções cadastradas!', 'error');
                return;
            }
            
            customEvolutionChain = pokemon.evolutions.map((evo, idx) => ({
                id: idx,
                name: evo.name,
                image: evo.image || 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png',
            }));
            
            currentCustomEvolutionIndex = 0;
            renderCustomEvolutionCarousel();
            
            const modal = document.getElementById('evolution-custom-modal');
            modal.classList.add('active');
            document.addEventListener('keydown', handleCustomEvolutionKeydown);
        }
        
        function renderCustomEvolutionCarousel() {
            const container = document.getElementById('custom-evolution-cards');
            if (!container) return;
            
            container.innerHTML = customEvolutionChain.map((evo, index) => {
                let positionClass = '';
                if (index === currentCustomEvolutionIndex) positionClass = 'center';
                else if (index === currentCustomEvolutionIndex - 1) positionClass = 'left';
                else if (index === currentCustomEvolutionIndex + 1) positionClass = 'right';
                else positionClass = 'hidden';
                
                return `
                    <div class="evolution-card-item ${positionClass}" onclick="selectCustomEvolution(${index})" data-index="${index}" style="background: linear-gradient(135deg, #4c1d95, #2e1065); border-color: rgba(168, 85, 247, 0.5);">
                        <img src="${evo.image}" class="evolution-img" onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                        <div class="evolution-info">
                            <div class="evolution-name" style="color: #d8b4fe;">${evo.name}</div>
                            <div class="evolution-stats mt-2">⭐ Pokémon Customizado</div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function navigateCustomEvolution(direction) {
            let newIndex = currentCustomEvolutionIndex + direction;
            if (newIndex < 0) newIndex = 0;
            if (newIndex >= customEvolutionChain.length) newIndex = customEvolutionChain.length - 1;
            if (newIndex !== currentCustomEvolutionIndex) {
                currentCustomEvolutionIndex = newIndex;
                renderCustomEvolutionCarousel();
            }
        }
function selectCustomEvolution(index) {
    const selectedEvo = customEvolutionChain[index];
    if (selectedEvo) {
        showToast(`✨ Evoluindo para ${selectedEvo.name}!`, 'success');
        closeCustomEvolutionModal();
        
        // Pega o Pokémon atual
        const currentPokemon = currentCustomPokemonSlot === 1 ? pokemon1 : pokemon2;
        
        // CRIA UM POKÉMON VIRTUAL COMPLETO para batalha
        const evolvedPokemon = {
            id: currentPokemon.id,  // ← USA O MESMO ID do original (importante!)
            name: selectedEvo.name,
            is_custom: true,
            image_url: selectedEvo.image,
            // Mantém TODOS os status do original
            stats: currentPokemon.stats,
            types: currentPokemon.types,
            height: currentPokemon.height,
            weight: currentPokemon.weight,
            base_experience: currentPokemon.base_experience,
            abilities: currentPokemon.abilities,
            evolutions: currentPokemon.evolutions,
            // Adiciona a imagem original para fallback
            original_image: currentPokemon.image_url
        };
        
        // Substitui o Pokémon no slot
        if (currentCustomPokemonSlot === 1) {
            pokemon1 = evolvedPokemon;
            renderSelectedPokemon(1, pokemon1);
        } else {
            pokemon2 = evolvedPokemon;
            renderSelectedPokemon(2, pokemon2);
        }
        
        showToast(`✨ ${selectedEvo.name} está pronto para a batalha!`, 'success');
    }
}

        function closeCustomEvolutionModal() {
            const modal = document.getElementById('evolution-custom-modal');
            modal.classList.remove('active');
            document.removeEventListener('keydown', handleCustomEvolutionKeydown);
        }
        
        function replacePokemonInSlot(slot, newId, newName, newImage) {
    showToast(`🔄 Evoluindo para ${newName}!`, 'info');
    
    // Se o ID começa com 'virtual_', cria o Pokémon na hora
    if (newId && newId.toString().startsWith('virtual_')) {
        const virtualPokemon = {
            id: newId,
            name: newName,
            is_custom: true,
            image_url: newImage,
            stats: [],
            types: [],
            height: 17,
            weight: 905
        };
        
        if (slot === 1) {
            pokemon1 = virtualPokemon;
            renderSelectedPokemon(1, pokemon1);
        } else {
            pokemon2 = virtualPokemon;
            renderSelectedPokemon(2, pokemon2);
        }
        showToast(`✨ ${newName} entrou na arena! (Virtual)`, 'success');
        return;
    }
    
    // Comportamento normal para Pokémon reais
    fetch(`/api/pokemon/${newId}`)
        .then(res => res.json())
        .then(newPokemon => {
            if (slot === 1) {
                pokemon1 = newPokemon;
                renderSelectedPokemon(1, pokemon1);
            } else {
                pokemon2 = newPokemon;
                renderSelectedPokemon(2, pokemon2);
            }
            showToast(`✨ ${newName} entrou na arena!`, 'success');
        })
        .catch(() => {
            // Fallback
            showToast(`❌ Erro ao evoluir para ${newName}`, 'error');
        });
}

        function handleEvolutionKeydown(e) {
            if (e.key === 'ArrowLeft') navigateEvolution(-1);
            else if (e.key === 'ArrowRight') navigateEvolution(1);
            else if (e.key === 'Enter') selectEvolution(currentEvolutionIndex);
            else if (e.key === 'Escape') closeEvolutionModal();
        }

        function handleCustomEvolutionKeydown(e) {
            if (e.key === 'ArrowLeft') navigateCustomEvolution(-1);
            else if (e.key === 'ArrowRight') navigateCustomEvolution(1);
            else if (e.key === 'Enter') selectCustomEvolution(currentCustomEvolutionIndex);
            else if (e.key === 'Escape') closeCustomEvolutionModal();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearchModal();
                closeEvolutionModal();
                closeCustomEvolutionModal();
            }
        });

        
    </script>
</body>
</html>