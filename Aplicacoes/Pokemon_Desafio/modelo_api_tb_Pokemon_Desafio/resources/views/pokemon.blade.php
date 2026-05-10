<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Ultra - {{ ucfirst($pokemon['name']) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
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

        .blob {
            position: absolute;
            width: 600px; height: 600px;
            background: linear-gradient(135deg, #1e40af 0%, #4338ca 100%);
            filter: blur(100px);
            border-radius: 50%;
            opacity: 0.2;
            animation: move 25s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(25%, 15%); }
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; overflow-y: auto; max-height: 380px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .float-poke { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }

        /* --- PESQUISA ULTRA --- */
        .search-wrapper {
            position: relative;
            padding: 2px;
            border-radius: 1.2rem;
            background: linear-gradient(90deg, rgba(59,130,246,0.3), rgba(147,51,234,0.3));
            transition: all 0.3s ease;
        }
        .search-wrapper:focus-within {
            background: linear-gradient(90deg, #3b82f6, #9333ea);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
            transform: scale(1.02);
        }
        .search-input {
            background: #0f172a;
            border: none;
            outline: none;
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 1.1rem;
            color: white;
            font-weight: 600;
        }

        /* --- HOVER HABILIDADE --- */
        .ability-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ability-card:hover {
            transform: translateX(10px);
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 25px rgba(59, 130, 246, 0.15);
        }
        .ability-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; height: 100%; width: 4px;
            background: #3b82f6;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .ability-card:hover::before { transform: scaleY(1); }

        /* --- CARD DE EVOLUÇÃO --- */
        .evo-card {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .evo-card:hover {
            transform: translateX(10px);
            background: rgba(59, 130, 246, 0.15);
        }
        
        /* Botão voltar ao original */
        .back-to-original {
            transition: all 0.3s ease;
        }
        .back-to-original:hover {
            transform: translateX(-5px);
        }
        
        /* Badge de Pokémon Oficial/Customizado */
        .official-badge {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
        }
        .custom-badge {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        /* Cards da lista de Pokémon */
        .pokemon-list-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .pokemon-list-card:hover {
            transform: translateY(-5px);
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
        }
        
        .filter-btn {
            transition: all 0.3s;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
        }

        /* Tipo badges */
        .type-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .type-normal { background: #A8A878; }
        .type-fire { background: #F08030; }
        .type-water { background: #6890F0; }
        .type-electric { background: #F8D030; color: #000; }
        .type-grass { background: #78C850; }
        .type-ice { background: #98D8D8; color: #000; }
        .type-fighting { background: #C03028; }
        .type-poison { background: #A040A0; }
        .type-ground { background: #E0C068; color: #000; }
        .type-flying { background: #A890F0; }
        .type-psychic { background: #F85888; }
        .type-bug { background: #A8B820; }
        .type-rock { background: #B8A038; }
        .type-ghost { background: #705898; }
        .type-dragon { background: #7038F8; }
        .type-dark { background: #705848; }
        .type-steel { background: #B8B8D0; color: #000; }
        .type-fairy { background: #EE99AC; }

        /* Esconde a barra de rolagem mas mantém o scroll funcionando */
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
    overflow-y: auto;
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Para o container da lista de Pokémon */
#pokemon-list-container {
    -ms-overflow-style: none;
    scrollbar-width: none;
    overflow-y: auto;
    max-height: 500px;
}

#pokemon-list-container::-webkit-scrollbar {
    display: none;
}

::-webkit-scrollbar {
    display: none;
}

/* Esconder botões de edição quando estiver na tab lista */
#lista .custom-buttons {
    display: none;
}

/* Barra de pesquisa no topo melhorada */
.search-section {
    position: sticky;
    top: 80px;
    z-index: 40;
    margin-bottom: 1.5rem;
}

.search-container {
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

@media (min-width: 768px) {
    .search-container {
        margin-left: 0;
        margin-right: 0;
    }
}

#toast {
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateX(400px) !important;
    transition: none !important;
}

#toast.show {
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateX(400px) !important;
}
    </style>
</head>
<body>

    <div class="bg-animate"><div class="blob"></div></div>

    <nav class="fixed top-0 w-full z-50 glass px-6 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('pokemon.show', 'pikachu') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded-full border-2 border-white animate-pulse"></div>
                <span class="text-xl font-extrabold tracking-tighter uppercase italic">Pokédex <span class="text-blue-400">Ultra</span></span>
            </a>
            <div class="hidden md:flex gap-8 text-sm font-semibold text-gray-400">
                <button onclick="openTab(event, 'biologia')" class="tab-link hover:text-white pb-1">Biologia</button>
                <button onclick="openTab(event, 'stats')" class="tab-link hover:text-white pb-1">Status</button>
                <button onclick="openTab(event, 'habilidades')" class="tab-link hover:text-white pb-1">Habilidades</button>
                <button onclick="openTab(event, 'evolucao')" class="tab-link hover:text-white pb-1">Evoluções</button>
                <button onclick="openTab(event, 'lista')" class="tab-link hover:text-white pb-1">Pokédex</button>
                <a href="{{ route('custom-pokemons.create') }}" 
                   class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold py-2 px-5 rounded-xl transition-all transform hover:scale-105 flex items-center gap-2">
                    ✨ Criar Pokémon
                </a>

                <a href="{{ route('battle.index') }}" 
   class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-5 rounded-xl transition-all transform hover:scale-105 flex items-center gap-2">
    ⚔️ Batalha
</a>
            </div>
        </div>
    </nav>

    <main class="relative pt-28 pb-20 px-6">
        <div class="max-w-6xl mx-auto">
            
            <!-- BARRA DE PESQUISA - Reposicionada no topo da área principal -->
            <div class="search-section">
                <div class="search-container">
                    <div class="search-wrapper flex items-center">
                        <svg class="absolute left-4 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" id="poke-search" placeholder="Buscar Pokémon por nome ou ID..." class="search-input pl-12 pr-20">
                        <div class="absolute right-4 text-gray-500 text-[10px] font-black tracking-widest bg-gray-800/50 px-2 py-1 rounded">ENTER</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                
                <div class="order-2 lg:order-1">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                        <div>
                            <h1 id="poke-name" class="text-6xl md:text-7xl font-extrabold capitalize tracking-tighter">
                                {{ $pokemon['name'] }}
                            </h1>
                            <div class="flex items-center gap-3 mt-3">
                                @if(isset($pokemon['is_custom']) && $pokemon['is_custom'])
                                    <span class="custom-badge px-3 py-1 rounded-full text-xs font-bold">⭐ CUSTOMIZADO</span>
                                    <span class="text-gray-400 text-sm">#{{ str_replace('custom_', '', $pokemon['id']) }}</span>
                                @else
                                    <span class="official-badge px-3 py-1 rounded-full text-xs font-bold">🎮 OFICIAL</span>
                                    <span class="text-gray-400 text-sm">#{{ str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button id="back-to-original-btn" class="back-to-original hidden bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white px-4 py-2 rounded-xl transition-all text-sm font-bold gap-2 items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Voltar ao Original
                        </button>
                    </div>

                    <div class="no-scrollbar pr-4">
                        <div id="stats" class="tab-content active space-y-6">
                            @foreach($pokemon['stats'] as $stat)
                            @php
                                $statName = is_array($stat) ? ($stat['stat']['name'] ?? $stat['stat'] ?? '') : '';
                                $baseStat = is_array($stat) ? ($stat['base_stat'] ?? 50) : 50;
                                $traducao = match($statName) {
                                    'hp' => 'Vida (HP)', 'attack' => 'Ataque', 'defense' => 'Defesa',
                                    'special-attack' => 'Atq. Especial', 'special-defense' => 'Def. Especial',
                                    'speed' => 'Velocidade', default => ucfirst(str_replace('-', ' ', $statName))
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium uppercase tracking-wider text-gray-400">{{ $traducao }}</span>
                                    <span class="text-sm font-bold">{{ $baseStat }}</span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all duration-1000" 
                                         style="width: {{ ($baseStat / 255) * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div id="habilidades" class="tab-content space-y-4">
                            @if(count($pokemon['abilities']) > 0)
                                @foreach($pokemon['abilities'] as $h)
                                @php
                                    $abilityName = is_array($h) ? ($h['ability']['name'] ?? $h['ability'] ?? '') : $h;
                                    $isHidden = is_array($h) ? ($h['is_hidden'] ?? false) : false;
                                @endphp
                                <div class="ability-card glass p-5 rounded-2xl flex justify-between items-center group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-black text-blue-500 uppercase tracking-[0.2em] mb-0.5">Técnica</span>
                                            <span class="text-xl font-bold capitalize">{{ str_replace('-', ' ', $abilityName) }}</span>
                                        </div>
                                    </div>
                                    @if($isHidden)
                                        <span class="text-[10px] bg-purple-500/20 text-purple-400 px-3 py-1 rounded-full font-black uppercase border border-purple-500/30">Oculta</span>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="glass p-8 rounded-2xl text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <p>Nenhuma habilidade definida</p>
                                </div>
                            @endif
                        </div>

                        <div id="evolucao" class="tab-content">
                            <div class="grid grid-cols-1 gap-4">
                                @if(isset($evolucaoDetalhes) && count($evolucaoDetalhes) > 0)
                                    @foreach($evolucaoDetalhes as $evo)
                                    <button onclick="updateDisplay('{{ $evo['nome'] }}', '{{ $evo['foto'] }}', this, true, '{{ $evo['id'] ?? '' }}', '{{ $evo['nome'] }}')" 
                                            class="evo-card glass p-4 rounded-2xl flex items-center gap-4 border-l-4 transition-all {{ $evo['nome'] == $pokemon['name'] ? 'border-blue-500 bg-blue-500/10' : 'border-transparent opacity-70 hover:opacity-100' }}">
                                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                            <img src="{{ $evo['foto'] }}" class="w-14 h-14 object-cover" onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                                        </div>
                                        <div class="flex-1 text-left">
                                            <p class="text-xs text-gray-400 uppercase tracking-wider">Evolução</p>
                                            <p class="text-xl font-bold capitalize">{{ $evo['nome'] }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                    @endforeach
                                @else
                                    <div class="glass p-8 rounded-2xl text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                        <p>Nenhuma evolução definida</p>
                                        <p class="text-xs mt-2">Este Pokémon não possui evoluções cadastradas</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- TAB DE BIOLOGIA -->
                        <div id="biologia" class="tab-content">
                            @include('components.pokemon-biology', ['pokemon' => $pokemon])
                        </div>

                        <!-- TAB: LISTA DE POKÉMON COM FILTROS -->
                        <div id="lista" class="tab-content">
                            <div class="space-y-4">
                                <!-- Filtros -->
                                <div class="glass p-4 rounded-2xl">
                                    <div class="flex gap-2 mb-4">
                                        <button onclick="filterPokemons('all')" id="filter-all" class="filter-btn flex-1 px-3 py-2 rounded-xl text-sm font-semibold bg-blue-600 text-white">
                                            Todos
                                        </button>
                                        <button onclick="filterPokemons('official')" id="filter-official" class="filter-btn flex-1 px-3 py-2 rounded-xl text-sm font-semibold glass hover:bg-white/10">
                                            Oficiais
                                        </button>
                                        <button onclick="filterPokemons('custom')" id="filter-custom" class="filter-btn flex-1 px-3 py-2 rounded-xl text-sm font-semibold glass hover:bg-white/10">
                                            Customizados ⭐
                                        </button>
                                    </div>
                                    <input type="text" id="search-pokemon-list" placeholder="Buscar Pokémon..." class="search-input w-full px-4 py-2 rounded-xl text-sm">
                                </div>
                                
                                <!-- Lista de Pokémon -->
                                <div id="pokemon-list-container" class="space-y-2 max-h-[500px] overflow-y-auto pr-2">
                                    <div class="text-center text-gray-400 py-8">
                                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-3"></div>
                                        <p>Carregando Pokémon...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex gap-4">
                        @php
                            $currentId = null;
                            $prevId = null;
                            $nextId = null;
                            $isOfficial = !(isset($pokemon['is_custom']) && $pokemon['is_custom']);
                            
                            if ($isOfficial && isset($pokemon['id']) && is_numeric($pokemon['id'])) {
                                $currentId = (int)$pokemon['id'];
                                $prevId = $currentId > 1 ? $currentId - 1 : null;
                                $nextId = $currentId < 1025 ? $currentId + 1 : null;
                            }
                        @endphp
                        
                        @if($isOfficial)
                            @if($prevId)
                            <a href="{{ route('pokemon.by-id', $prevId) }}" class="glass hover:bg-white/10 text-white font-bold py-4 px-8 rounded-2xl transition-all active:scale-95 flex items-center gap-2 border-white/5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                ANTERIOR
                            </a>
                            @else
                            <div class="glass opacity-40 py-4 px-8 rounded-2xl flex items-center gap-2 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                ANTERIOR
                            </div>
                            @endif
                            
                            @if($nextId)
                            <a href="{{ route('pokemon.by-id', $nextId) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-10 rounded-2xl transition-all shadow-xl shadow-blue-900/30 active:scale-95 flex-1 text-center">
                                PRÓXIMO
                            </a>
                            @else
                            <div class="bg-blue-600/30 opacity-50 text-white font-black py-4 px-10 rounded-2xl flex-1 text-center cursor-not-allowed">
                                PRÓXIMO
                            </div>
                            @endif
                        @else
                            <a href="{{ route('pokemon.show', 'pikachu') }}" class="glass hover:bg-white/10 text-white font-bold py-4 px-8 rounded-2xl transition-all active:scale-95 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                VOLTAR PÁGINA INICIAL
                            </a>
                        
        <a href="{{ route('custom-pokemons.edit', str_replace('custom_', '', $pokemon['id'])) }}" class="bg-purple-600 hover:bg-purple-500 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-purple-900/30 active:scale-95 flex-1 text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            EDITAR
        </a>
                        @endif
                    </div>
                </div>

                <div class="order-1 lg:order-2 flex justify-center relative">
                    <div class="absolute inset-0 bg-blue-600/10 blur-[150px] rounded-full animate-pulse"></div>
                    @php
                        $imageUrl = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png';
                        
                        if (isset($pokemon['image_url']) && $pokemon['image_url']) {
                            $imageUrl = $pokemon['image_url'];
                        } elseif (isset($pokemon['sprites']['other']['official-artwork']['front_default'])) {
                            $imageUrl = $pokemon['sprites']['other']['official-artwork']['front_default'];
                        }
                    @endphp
                    <img id="main-img" src="{{ $imageUrl }}" 
                         class="float-poke w-full max-w-[480px] drop-shadow-[0_0_80px_rgba(59,130,246,0.3)] transition-all duration-700"
                         onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                </div>
            </div>
        </div>
    </main>

    <!-- RODAPÉ REMOVIDO - As informações de altura, peso e experiência agora estão na aba Biologia -->

    <div id="toast" class="fixed bottom-20 right-5 bg-gray-800 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-all duration-300 z-50"></div>

<script>
    // Dados completos dos Pokémon (serão carregados via AJAX)
    let allPokemons = [];
    let currentFilter = 'all';
    let searchTerm = '';
    
    // Carregar lista de Pokémon
    async function loadPokemons() {
    try {
        const container = document.getElementById('pokemon-list-container');
        container.innerHTML = `
            <div class="text-center text-gray-400 py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-3"></div>
                <p>Carregando Pokémon...</p>
            </div>
        `;
        
        // Buscar Pokémon customizados via API
        let customPokemons = [];
        try {
            console.log('Buscando Pokémon customizados...');
            const customResponse = await fetch('/api/custom-pokemons');
            
            if (customResponse.ok) {
                customPokemons = await customResponse.json();
                console.log('Custom Pokémon carregados:', customPokemons.length);
                console.log('Dados:', customPokemons);
            } else {
                const errorText = await customResponse.text();
                console.error('Erro na resposta:', errorText);
            }
        } catch (e) {
            console.error('Erro na requisição custom:', e);
        }
        
        // Buscar Pokémon oficiais (apenas os primeiros 30 para teste, para não sobrecarregar)
        let officialPokemons = [];
        try {
            console.log('Buscando Pokémon oficiais...');
            const officialResponse = await fetch('https://pokeapi.co/api/v2/pokemon?limit=30');
            const officialData = await officialResponse.json();
            
            // Carregar detalhes
            for (const pkmn of officialData.results) {
                try {
                    const details = await fetch(pkmn.url).then(res => res.json());
                    officialPokemons.push({
                        id: details.id,
                        name: details.name,
                        image_url: details.sprites.other?.['official-artwork']?.front_default || details.sprites.front_default,
                        types: details.types.map(t => t.type.name),
                        is_custom: false
                    });
                } catch (err) {
                    console.error('Erro ao carregar:', pkmn.name, err);
                }
            }
            console.log('Official Pokémon carregados:', officialPokemons.length);
        } catch (e) {
            console.error('Erro ao carregar oficiais:', e);
        }
        
        // Combinar listas
        allPokemons = [...officialPokemons, ...customPokemons];
        console.log('Total de Pokémon:', allPokemons.length);
        
        if (allPokemons.length === 0) {
            container.innerHTML = `
                <div class="text-center text-yellow-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p>Nenhum Pokémon encontrado</p>
                    <p class="text-xs mt-2">Tente criar um Pokémon customizado!</p>
                    <a href="{{ route('custom-pokemons.create') }}" class="inline-block mt-4 bg-purple-600 px-4 py-2 rounded-lg text-sm">
                        ✨ Criar Pokémon
                    </a>
                </div>
            `;
            return;
        }
        
        renderPokemonList();
        
    } catch (error) {
        console.error('Erro ao carregar Pokémon:', error);
        document.getElementById('pokemon-list-container').innerHTML = `
            <div class="text-center text-red-400 py-8">
                <p>Erro ao carregar Pokémon: ${error.message}</p>
                <button onclick="loadPokemons()" class="mt-3 bg-blue-600 px-4 py-2 rounded-lg text-sm">
                    🔄 Tentar novamente
                </button>
            </div>
        `;
    }
}
    
    // Renderizar lista de Pokémon com filtros
    function renderPokemonList() {
        let filtered = [...allPokemons];
        
        // Filtrar por tipo (oficial/custom)
        if (currentFilter === 'official') {
            filtered = filtered.filter(p => !p.is_custom);
        } else if (currentFilter === 'custom') {
            filtered = filtered.filter(p => p.is_custom);
        }
        
        // Filtrar por busca
        if (searchTerm) {
            filtered = filtered.filter(p => 
                p.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }
        
        // Ordenar por nome
        filtered.sort((a, b) => a.name.localeCompare(b.name));
        
        const container = document.getElementById('pokemon-list-container');
        
        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Nenhum Pokémon encontrado</p>
                    <p class="text-xs mt-2">Tente mudar os filtros</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = filtered.map(pokemon => `
            <div onclick="goToPokemon('${pokemon.is_custom ? 'custom_' + pokemon.id : pokemon.name}', ${pokemon.is_custom})" 
                 class="pokemon-list-card glass p-3 rounded-xl flex items-center gap-3 transition-all">
                <img src="${pokemon.image_url || 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'}" 
                     class="w-12 h-12 object-contain"
                     onerror="this.src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-bold capitalize">${pokemon.name}</h4>
                        ${pokemon.is_custom ? '<span class="text-xs text-purple-400">⭐</span>' : ''}
                    </div>
                    ${pokemon.types ? `
                        <div class="flex gap-1 mt-1">
                            ${pokemon.types.slice(0, 2).map(type => `
                                <span class="type-badge type-${type} text-white text-[10px] px-2 py-0.5 rounded">${type}</span>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        `).join('');
    }
    
    // Filtrar Pokémon
    function filterPokemons(filter) {
        currentFilter = filter;
        
        // Atualizar estilo dos botões
        document.getElementById('filter-all').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('filter-all').classList.add('glass');
        document.getElementById('filter-official').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('filter-official').classList.add('glass');
        document.getElementById('filter-custom').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('filter-custom').classList.add('glass');
        
        if (filter === 'all') {
            document.getElementById('filter-all').classList.remove('glass');
            document.getElementById('filter-all').classList.add('bg-blue-600', 'text-white');
        } else if (filter === 'official') {
            document.getElementById('filter-official').classList.remove('glass');
            document.getElementById('filter-official').classList.add('bg-blue-600', 'text-white');
        } else {
            document.getElementById('filter-custom').classList.remove('glass');
            document.getElementById('filter-custom').classList.add('bg-blue-600', 'text-white');
        }
        
        renderPokemonList();
    }
    
    // Buscar na lista
    function setupSearch() {
        const searchInput = document.getElementById('search-pokemon-list');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                searchTerm = e.target.value;
                renderPokemonList();
            });
        }
    }
    
    // Ir para o Pokémon
    function goToPokemon(identifier, isCustom) {
        if (isCustom) {
            const id = identifier.replace('custom_', '');
            window.location.href = `/custom/pokemons/${id}`;
        } else {
            const query = isNaN(identifier) ? identifier.toLowerCase() : identifier;
            window.location.href = `/pokemon/${query}`;
        }
    }
    
    // Busca principal
    document.getElementById('poke-search').addEventListener('keypress', async function (e) {
        if (e.key === 'Enter') {
            const query = this.value.toLowerCase().trim();
            if (!query) return;
            
            try {
                const response = await fetch(`/custom/pokemons/search?name=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data.found) {
                    window.location.href = `/custom/pokemons/${data.id}`;
                    return;
                }
            } catch (error) {
                console.log('Não encontrado nos customizados, buscando na API');
            }
            
            window.location.href = `/pokemon/${query}`;
        }
    });

    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) tabcontent[i].classList.remove("active");
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("text-white", "border-blue-500", "border-b-2");
            tablinks[i].classList.add("text-gray-400");
        }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("text-white", "border-blue-500", "border-b-2");
        
        // Carregar lista quando abrir a tab
        if (tabName === 'lista' && allPokemons.length === 0) {
            loadPokemons();
            setupSearch();
        }
    }

    let viewingEvolution = false;
    
    function updateDisplay(nome, foto, element, isEvolution = true, id = null, evoNome = null) {
        const nameTitle = document.getElementById('poke-name');
        const mainImg = document.getElementById('main-img');
        const backButton = document.getElementById('back-to-original-btn');
        
        viewingEvolution = isEvolution;
        
        mainImg.style.filter = 'blur(20px) brightness(2)';
        mainImg.style.transform = 'scale(0.5) translateY(50px)';
        mainImg.style.opacity = '0';
        nameTitle.style.opacity = '0';
        nameTitle.style.transform = 'translateX(-20px)';

        setTimeout(() => {
            nameTitle.innerText = nome.charAt(0).toUpperCase() + nome.slice(1);
            mainImg.src = foto;

            if (isEvolution) {
                backButton.classList.remove('hidden');
                backButton.classList.add('flex');
            } else {
                backButton.classList.add('hidden');
                backButton.classList.remove('flex');
            }

            mainImg.style.filter = 'blur(0px) brightness(1)';
            mainImg.style.transform = 'scale(1.1) translateY(0)';
            mainImg.style.opacity = '1';
            nameTitle.style.opacity = '1';
            nameTitle.style.transform = 'translateX(0)';

            setTimeout(() => {
                mainImg.style.transform = '';
            }, 500);
        }, 300);

        const cards = document.querySelectorAll('.evo-card');
        cards.forEach(card => {
            card.classList.remove('border-blue-500', 'bg-blue-500/10');
            card.classList.add('border-transparent', 'opacity-70');
        });
        element.classList.add('border-blue-500', 'bg-blue-500/10');
        element.classList.remove('border-transparent', 'opacity-70');
        
        if (id && id.toString().startsWith('custom_')) {
            const customId = id.replace('custom_', '');
            window.history.pushState({}, '', `/custom/pokemons/${customId}`);
        }
    }
    
    document.getElementById('back-to-original-btn')?.addEventListener('click', () => {
        const nameTitle = document.getElementById('poke-name');
        const mainImg = document.getElementById('main-img');
        const backButton = document.getElementById('back-to-original-btn');
        
        mainImg.style.filter = 'blur(20px) brightness(2)';
        mainImg.style.transform = 'scale(0.5) translateY(50px)';
        mainImg.style.opacity = '0';
        nameTitle.style.opacity = '0';
        
        setTimeout(() => {
            nameTitle.innerText = originalPokemon.name;
            mainImg.src = originalPokemon.image;
            
            backButton.classList.add('hidden');
            backButton.classList.remove('flex');
            viewingEvolution = false;
            
            const cards = document.querySelectorAll('.evo-card');
            cards.forEach(card => {
                const cardName = card.querySelector('.font-bold')?.innerText.toLowerCase();
                if (cardName === originalPokemon.name.toLowerCase()) {
                    card.classList.add('border-blue-500', 'bg-blue-500/10');
                    card.classList.remove('border-transparent', 'opacity-70');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-500/10');
                    card.classList.add('border-transparent', 'opacity-70');
                }
            });
            
            mainImg.style.filter = 'blur(0px) brightness(1)';
            mainImg.style.transform = 'scale(1.1) translateY(0)';
            mainImg.style.opacity = '1';
            nameTitle.style.opacity = '1';
            nameTitle.style.transform = 'translateX(0)';
            
            setTimeout(() => {
                mainImg.style.transform = '';
            }, 500);
        }, 300);
    });

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        toast.classList.remove('bg-red-600', 'bg-green-600');
        if (type === 'error') {
            toast.classList.add('bg-red-600');
        } else {
            toast.classList.add('bg-green-600');
        }
        
        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.classList.remove('bg-red-600', 'bg-green-600');
            }, 300);
        }, 3000);
    }
    
    // Dados originais do Pokémon
    let originalPokemon = {
        name: '{{ $pokemon["name"] }}',
        image: '{{ $imageUrl }}',
        isCustom: {{ isset($pokemon['is_custom']) && $pokemon['is_custom'] ? 'true' : 'false' }}
    };

    // Controlar visibilidade dos botões de customizado baseado na tab ativa
    function checkCustomButtonsVisibility() {
        const customButtons = document.querySelector('.custom-buttons');
        const activeTab = document.querySelector('.tab-content.active');
        
        if (customButtons && activeTab) {
            if (activeTab.id === 'lista') {
                customButtons.style.display = 'none';
            } else {
                customButtons.style.display = 'flex';
            }
        }
    }

    // Modificar a função openTab existente
    const originalOpenTab = openTab;
    openTab = function(evt, tabName) {
        originalOpenTab(evt, tabName);
        setTimeout(checkCustomButtonsVisibility, 100);
    };

    // Executar ao carregar a página
    document.addEventListener('DOMContentLoaded', checkCustomButtonsVisibility);
</script>
</body>
</html>