<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Pokémon Customizado - Pokédex Ultra</title>
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

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
        
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(10px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        .float-poke { animation: float 6s ease-in-out infinite; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }

        .edit-input {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        
        .edit-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .stat-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            outline: none;
        }
        
        .stat-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: #3b82f6;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px #3b82f6;
        }

        .ability-card, .evolution-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .ability-card:hover, .evolution-card:hover {
            transform: translateX(10px);
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
        }
        
        .ability-card::before, .evolution-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; height: 100%; width: 4px;
            background: #3b82f6;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .ability-card:hover::before, .evolution-card:hover::before { transform: scaleY(1); }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            background: #1e293b;
            border-left: 4px solid;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .toast.show { transform: translateX(0); }
        .toast-success { border-left-color: #10b981; }
        .toast-error { border-left-color: #ef4444; }

        .image-upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .image-upload-area:hover {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; overflow-y: auto; max-height: 380px; }
        
        .tab-link {
            transition: all 0.3s;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .type-option {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .type-option:hover {
            transform: scale(1.05);
        }
        .type-option.selected {
            box-shadow: 0 0 0 2px white, 0 0 0 4px #3b82f6;
        }
        
        .image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            max-width: 480px;
            margin: 0 auto;
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .empty-image {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-image svg {
            opacity: 0.3;
        }

        /* Modal da IA */
        .modal {
            transition: all 0.3s ease;
        }
        .modal-enter {
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
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
                <button onclick="openTab(event, 'stats')" class="tab-link text-white border-b-2 border-blue-500 pb-1">Status</button>
                <button onclick="openTab(event, 'habilidades')" class="tab-link hover:text-white pb-1">Habilidades</button>
                <button onclick="openTab(event, 'evolucao')" class="tab-link hover:text-white pb-1">Evoluções</button>
            </div>
        </div>
    </nav>

    <main class="relative pt-32 pb-20 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- COLUNA ESQUERDA -->
            <div>
                <div class="mb-8">
                    <input type="text" id="poke-name-input" 
                           class="text-6xl md:text-7xl font-extrabold capitalize bg-transparent border-b-2 border-blue-500/50 focus:border-blue-500 outline-none w-full"
                           placeholder="NOME DO POKÉMON" value="">
                </div>

                <div class="no-scrollbar pr-4">
                    <!-- STATUS -->
                    <div id="stats" class="tab-content active space-y-6">
                        @php
                            $statsList = [
                                'hp' => 'VIDA (HP)',
                                'attack' => 'ATAQUE',
                                'defense' => 'DEFESA',
                                'special-attack' => 'ATQ. ESPECIAL',
                                'special-defense' => 'DEF. ESPECIAL',
                                'speed' => 'VELOCIDADE'
                            ];
                        @endphp
                        @foreach($statsList as $key => $label)
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium uppercase tracking-wider text-gray-400">{{ $label }}</span>
                                <span id="{{ str_replace('-', '_', $key) }}-val" class="text-sm font-bold text-blue-400">50</span>
                            </div>
                            <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                                <div id="bar-{{ str_replace('-', '_', $key) }}" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all" style="width: 19.6%"></div>
                            </div>
                            <input type="range" data-stat="{{ $key }}" class="stat-slider mt-2" min="1" max="255" value="50" 
                                   oninput="updateStat(this, '{{ str_replace('-', '_', $key) }}-val')">
                        </div>
                        @endforeach
                    </div>

                    <!-- HABILIDADES -->
                    <div id="habilidades" class="tab-content space-y-4">
                        <div id="abilities-container" class="space-y-3">
                            <div class="ability-card glass-card p-5 rounded-2xl flex justify-between items-center gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        <span class="text-xs font-black text-blue-500 uppercase tracking-[0.2em]">Técnica</span>
                                    </div>
                                    <input type="text" name="abilities[0][name]" class="edit-input font-bold text-lg" placeholder="Nome da habilidade">
                                </div>
                                <select name="abilities[0][is_hidden]" class="bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm">
                                    <option value="0">Normal</option>
                                    <option value="1">Oculta</option>
                                </select>
                                <button type="button" class="remove-ability text-red-400 text-2xl hover:text-red-300">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-ability" class="text-blue-400 text-sm hover:text-blue-300 font-semibold">+ Adicionar Habilidade</button>
                    </div>

                    <!-- EVOLUÇÕES -->
                    <div id="evolucao" class="tab-content">
                        <div id="evolutions-container" class="space-y-3">
                            <!-- Template de evolução será adicionado via JS -->
                        </div>
                        <button type="button" id="add-evolution" class="text-blue-400 text-sm hover:text-blue-300 font-semibold mt-3">+ Adicionar Evolução</button>
                    </div>

                    <!-- BIOLOGIA COMPLETA EDITÁVEL -->
                    <div id="biologia" class="tab-content">
                        <div class="space-y-6">
                            <!-- Descrição -->
                            <div class="glass-card p-6 rounded-2xl">
                                <label class="block text-sm font-medium text-gray-400 mb-2">📖 Descrição / História do Pokémon</label>
                                <textarea id="description-input" rows="5" class="edit-input w-full" 
                                          placeholder="Conte a história deste Pokémon, suas características especiais, hábitat, comportamento..."></textarea>
                                <p class="text-xs text-gray-500 mt-2">Uma descrição detalhada ajuda a dar vida ao seu Pokémon!</p>
                            </div>

                            <!-- Tipos do Pokémon -->
                            <div class="glass-card p-6 rounded-2xl">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold">🔮 Tipos do Pokémon</h3>
                                </div>
                                <p class="text-sm text-gray-400 mb-3">Selecione até 2 tipos:</p>
                                <div id="types-container" class="flex flex-wrap gap-2">
                                    @php
                                        $allTypes = ['normal', 'fire', 'water', 'electric', 'grass', 'ice', 'fighting', 'poison', 'ground', 'flying', 'psychic', 'bug', 'rock', 'ghost', 'dragon', 'dark', 'steel', 'fairy'];
                                        $typeNames = [
                                            'normal' => 'Normal', 'fire' => 'Fogo', 'water' => 'Água', 'electric' => 'Elétrico',
                                            'grass' => 'Grama', 'ice' => 'Gelo', 'fighting' => 'Lutador', 'poison' => 'Venenoso',
                                            'ground' => 'Terra', 'flying' => 'Voador', 'psychic' => 'Psíquico', 'bug' => 'Inseto',
                                            'rock' => 'Pedra', 'ghost' => 'Fantasma', 'dragon' => 'Dragão', 'dark' => 'Sombrio',
                                            'steel' => 'Aço', 'fairy' => 'Fada'
                                        ];
                                        $typeColors = [
                                            'normal' => '#A8A878', 'fire' => '#F08030', 'water' => '#6890F0', 'electric' => '#F8D030',
                                            'grass' => '#78C850', 'ice' => '#98D8D8', 'fighting' => '#C03028', 'poison' => '#A040A0',
                                            'ground' => '#E0C068', 'flying' => '#A890F0', 'psychic' => '#F85888', 'bug' => '#A8B820',
                                            'rock' => '#B8A038', 'ghost' => '#705898', 'dragon' => '#7038F8', 'dark' => '#705848',
                                            'steel' => '#B8B8D0', 'fairy' => '#EE99AC'
                                        ];
                                        $darkTextTypes = ['electric', 'ice', 'normal', 'steel', 'ground', 'fairy'];
                                    @endphp
                                    @foreach($allTypes as $type)
                                    @php
                                        $bgColor = $typeColors[$type] ?? '#A8A878';
                                        $textColor = in_array($type, $darkTextTypes) ? '#000' : '#fff';
                                    @endphp
                                    <button type="button" data-type="{{ $type }}" onclick="toggleType(this)" 
                                            class="type-option px-4 py-2 rounded-full text-sm font-bold capitalize transition-all"
                                            style="background: {{ $bgColor }}; color: {{ $textColor }};">
                                        {{ $typeNames[$type] }}
                                    </button>
                                    @endforeach
                                </div>
                                <input type="hidden" id="selected-types" value='[]'>
                            </div>

                            <!-- Informações Gerais Editáveis -->
                            <div class="glass-card p-6 rounded-2xl">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold">📊 Informações Gerais</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center p-3 rounded-xl bg-white/5">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider">Altura</p>
                                        <div class="flex items-center gap-2 justify-center mt-1">
                                            <input type="number" id="height-input" class="bg-transparent text-2xl font-bold text-center w-20" value="17" step="1">
                                            <span class="text-gray-400 text-sm">/10</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1"><span id="height-m">1.7</span> metros</p>
                                    </div>
                                    <div class="text-center p-3 rounded-xl bg-white/5">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider">Peso</p>
                                        <div class="flex items-center gap-2 justify-center mt-1">
                                            <input type="number" id="weight-input" class="bg-transparent text-2xl font-bold text-center w-20" value="905" step="1">
                                            <span class="text-gray-400 text-sm">/10</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1"><span id="weight-kg">90.5</span> kg</p>
                                    </div>
                                    <div class="text-center p-3 rounded-xl bg-white/5">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider">Experiência</p>
                                        <input type="number" id="exp-input" class="bg-transparent text-2xl font-bold text-center w-20" value="100">
                                        <p class="text-xs text-gray-500 mt-1">base</p>
                                    </div>
                                    <div class="text-center p-3 rounded-xl bg-white/5">
                                        <p class="text-gray-400 text-xs uppercase tracking-wider">Categoria</p>
                                        <p class="text-2xl font-bold">Custom</p>
                                        <p class="text-xs text-gray-500 mt-1">Personalizado</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview de Fraquezas/Resistências -->
                            <div id="weaknesses-preview" class="glass-card p-6 rounded-2xl hidden">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold">⚠️ Análise de Tipo</h3>
                                </div>
                                <div id="weaknesses-content" class="text-gray-400 text-sm">
                                    Selecione os tipos do Pokémon para ver as fraquezas e resistências.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÕES -->
                <div class="mt-10 flex gap-4">
                    <a href="{{ route('pokemon.show', 'pikachu') }}" class="glass hover:bg-white/10 text-white font-bold py-4 px-8 rounded-2xl transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        VOLTAR
                    </a>
                    <button id="save-btn" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-blue-900/30 active:scale-95 flex-1 text-center">
                        ✨ CRIAR POKÉMON
                    </button>
                </div>
            </div>

            <!-- COLUNA DIREITA - IMAGEM PRINCIPAL -->
            <div class="flex justify-center relative">
                <div class="absolute inset-0 bg-blue-600/10 blur-[150px] rounded-full animate-pulse"></div>
                <div class="text-center w-full">
                    <div class="image-container relative group">
                        <div id="main-img-container" class="w-full h-full rounded-2xl image-upload-area empty-image flex items-center justify-center bg-black/20 cursor-pointer" onclick="document.getElementById('main-image-input').click()">
                            <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="absolute text-sm text-gray-400 mt-28">Clique para adicionar imagem</span>
                        </div>
                        <img id="main-img" class="hidden w-full h-full object-contain rounded-2xl">
                        <input type="file" id="main-image-input" class="hidden" accept="image/*">
                        <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-3 pointer-events-none group-hover:pointer-events-auto">
                            <button onclick="document.getElementById('main-image-input').click()" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-xl text-sm font-bold transition pointer-events-auto">📷 Upload</button>
                            <button id="open-ai-modal" class="bg-purple-600 hover:bg-purple-500 px-4 py-2 rounded-xl text-sm font-bold transition pointer-events-auto">🤖 Gerar IA</button>
                        </div>
                    </div>

                    <div class="glass rounded-2xl p-4 mt-6">
                        <div class="flex justify-around text-center">
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] tracking-wider">ALTURA</p>
                                <p class="text-xl font-bold"><span id="quick-height">1.7</span>m</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] tracking-wider">PESO</p>
                                <p class="text-xl font-bold"><span id="quick-weight">90.5</span>kg</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] tracking-wider">EXP</p>
                                <p class="text-xl font-bold"><span id="quick-exp">100</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL DA IA PRINCIPAL -->
    <div id="ai-modal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden modal" onclick="if(event.target===this) closeAIModal()">
        <div class="glass p-6 rounded-2xl max-w-md w-full mx-4 transform transition-all modal-enter">
            <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Gerar Imagem com IA</h3>
            <p class="text-gray-400 text-sm mb-3">Descreva seu Pokémon em detalhes:</p>
            <textarea id="ai-prompt-modal" rows="4" class="edit-input mb-4" placeholder="Ex: Um dragão azul com asas de fogo, estilo Pokémon oficial, cores vibrantes, detalhes em neon"></textarea>
            <div class="flex gap-3">
                <button onclick="closeAIModal()" class="flex-1 glass hover:bg-white/10 py-3 rounded-xl font-semibold transition">Cancelar</button>
                <button id="generate-ai-btn" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 py-3 rounded-xl font-bold transition">Gerar Imagem</button>
            </div>
        </div>
    </div>

    <!-- MODAL DA IA PARA EVOLUÇÃO (dinâmico) -->
    <div id="evo-ai-modal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden modal" onclick="if(event.target===this) closeEvoAIModal()">
        <div class="glass p-6 rounded-2xl max-w-md w-full mx-4 transform transition-all modal-enter">
            <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Gerar Imagem da Evolução com IA</h3>
            <p class="text-gray-400 text-sm mb-3">Descreva a evolução em detalhes:</p>
            <textarea id="evo-ai-prompt-modal" rows="4" class="edit-input mb-4" placeholder="Ex: Uma versão evoluída, maior, com asas enormes e chamas azuis"></textarea>
            <input type="hidden" id="current-evo-index" value="">
            <div class="flex gap-3">
                <button onclick="closeEvoAIModal()" class="flex-1 glass hover:bg-white/10 py-3 rounded-xl font-semibold transition">Cancelar</button>
                <button id="generate-evo-ai-btn" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 py-3 rounded-xl font-bold transition">Gerar Imagem</button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script>
        // Variáveis globais
        let selectedTypes = [];
        let selectedImageFile = null;
        let evolutionImageFiles = {};
        let evolutionIndex = 0;

        // ==================== FUNÇÕES GERAIS ====================
        
        function updateStat(slider, spanId) {
            const val = slider.value;
            document.getElementById(spanId).innerText = val;
            const statKey = slider.getAttribute('data-stat');
            const barId = 'bar-' + statKey.replace('-', '_');
            const bar = document.getElementById(barId);
            if(bar) bar.style.width = (val / 255 * 100) + '%';
        }

        function openTab(evt, tabName) {
            const tabcontents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabcontents.length; i++) tabcontents[i].classList.remove("active");
            const tablinks = document.getElementsByClassName("tab-link");
            for (let i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("text-white", "border-blue-500", "border-b-2");
                tablinks[i].classList.add("text-gray-400");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("text-white", "border-blue-500", "border-b-2");
        }

        // Preview altura/peso
        document.getElementById('height-input')?.addEventListener('input', function() {
            const meters = (this.value / 10).toFixed(1);
            document.getElementById('height-m').innerText = meters;
            document.getElementById('quick-height').innerText = meters;
        });
        document.getElementById('weight-input')?.addEventListener('input', function() {
            const kgs = (this.value / 10).toFixed(1);
            document.getElementById('weight-kg').innerText = kgs;
            document.getElementById('quick-weight').innerText = kgs;
        });
        document.getElementById('exp-input')?.addEventListener('input', function() {
            document.getElementById('quick-exp').innerText = this.value;
        });

        // ==================== FUNÇÕES DE TIPO ====================
        
        function toggleType(button) {
            const type = button.getAttribute('data-type');
            const index = selectedTypes.indexOf(type);
            
            if (index === -1 && selectedTypes.length < 2) {
                selectedTypes.push(type);
                button.classList.add('selected');
                button.style.boxShadow = '0 0 0 2px white, 0 0 0 4px #3b82f6';
            } else if (index !== -1) {
                selectedTypes.splice(index, 1);
                button.classList.remove('selected');
                button.style.boxShadow = 'none';
            } else if (selectedTypes.length >= 2) {
                showToast('Você pode selecionar no máximo 2 tipos!', 'error');
            }
            
            document.getElementById('selected-types').value = JSON.stringify(selectedTypes);
            updateWeaknessesPreview();
        }

        async function updateWeaknessesPreview() {
            const previewDiv = document.getElementById('weaknesses-preview');
            const contentDiv = document.getElementById('weaknesses-content');
            
            if (selectedTypes.length === 0) {
                previewDiv.classList.add('hidden');
                return;
            }
            
            previewDiv.classList.remove('hidden');
            
            try {
                const response = await fetch('/api/type-analysis', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ types: selectedTypes })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    let html = '';
                    
                    if (data.weaknesses && data.weaknesses.length > 0) {
                        html += '<div class="mb-4"><strong class="text-red-400">Fraquezas:</strong><div class="flex flex-wrap gap-2 mt-2">';
                        data.weaknesses.forEach(w => {
                            html += `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30">${w.name} (${w.multiplier}x)</span>`;
                        });
                        html += '</div></div>';
                    }
                    
                    if (data.resistances && data.resistances.length > 0) {
                        html += '<div class="mb-4"><strong class="text-blue-400">Resistências:</strong><div class="flex flex-wrap gap-2 mt-2">';
                        data.resistances.forEach(r => {
                            html += `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">${r.name} (${r.multiplier}x)</span>`;
                        });
                        html += '</div></div>';
                    }
                    
                    if (data.immunities && data.immunities.length > 0) {
                        html += '<div class="mb-4"><strong class="text-yellow-400">Imunidades:</strong><div class="flex flex-wrap gap-2 mt-2">';
                        data.immunities.forEach(i => {
                            html += `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">${i}</span>`;
                        });
                        html += '</div></div>';
                    }
                    
                    if (data.strongAgainst && data.strongAgainst.length > 0) {
                        html += '<div><strong class="text-green-400">Forte contra:</strong><div class="flex flex-wrap gap-2 mt-2">';
                        data.strongAgainst.forEach(s => {
                            html += `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">${s}</span>`;
                        });
                        html += '</div></div>';
                    }
                    
                    contentDiv.innerHTML = html;
                }
            } catch (error) {
                console.error('Erro ao calcular fraquezas:', error);
                contentDiv.innerHTML = '<p class="text-gray-400">Selecione os tipos para ver as fraquezas e resistências.</p>';
            }
        }

        // ==================== FUNÇÕES DE IMAGEM PRINCIPAL ====================
        
        document.getElementById('main-image-input')?.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                selectedImageFile = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('main-img');
                    const container = document.getElementById('main-img-container');
                    img.src = event.target.result;
                    img.classList.remove('hidden');
                    container.classList.add('hidden');
                    showToast('Imagem selecionada!', 'success');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // ==================== MODAL DA IA PRINCIPAL ====================
        
        const aiModal = document.getElementById('ai-modal');
        const openAiModal = document.getElementById('open-ai-modal');
        const closeAIModal = () => aiModal.classList.add('hidden');
        
        openAiModal?.addEventListener('click', () => {
            aiModal.classList.remove('hidden');
        });

        let isGenerating = false;
        const generateAiBtn = document.getElementById('generate-ai-btn');
        const aiPromptModal = document.getElementById('ai-prompt-modal');

        generateAiBtn?.addEventListener('click', async () => {
            if (isGenerating) return;
            
            const prompt = aiPromptModal.value.trim();
            if (!prompt) {
                showToast('Digite uma descrição do Pokémon!', 'error');
                return;
            }

            isGenerating = true;
            generateAiBtn.disabled = true;
            generateAiBtn.textContent = '⏳ Gerando...';

            try {
                const response = await fetch('/custom/generate-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prompt: prompt })
                });

                const data = await response.json();
                
                if (data.success && data.image_url) {
                    // Baixar a imagem para salvar como arquivo
                    const imageResponse = await fetch(data.image_url);
                    const imageBlob = await imageResponse.blob();
                    const fileName = 'ai_generated_' + Date.now() + '.png';
                    selectedImageFile = new File([imageBlob], fileName, { type: 'image/png' });
                    
                    const img = document.getElementById('main-img');
                    const container = document.getElementById('main-img-container');
                    img.src = data.image_url;
                    img.classList.remove('hidden');
                    container.classList.add('hidden');
                    
                    showToast('✨ Imagem gerada com sucesso!', 'success');
                    closeAIModal();
                    aiPromptModal.value = '';
                } else {
                    showToast(data.message || 'Erro ao gerar imagem', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro de conexão com o servidor', 'error');
            } finally {
                isGenerating = false;
                generateAiBtn.disabled = false;
                generateAiBtn.textContent = 'Gerar Imagem';
            }
        });

        // ==================== FUNÇÕES DE EVOLUÇÃO ====================
        
        // Modal da IA para evolução
        const evoAiModal = document.getElementById('evo-ai-modal');
        const closeEvoAIModal = () => evoAiModal.classList.add('hidden');
        const openEvoAIModal = (index) => {
            document.getElementById('current-evo-index').value = index;
            document.getElementById('evo-ai-prompt-modal').value = '';
            evoAiModal.classList.remove('hidden');
        };

        // Gerar imagem da evolução com IA
        const generateEvoAiBtn = document.getElementById('generate-evo-ai-btn');
        
        generateEvoAiBtn?.addEventListener('click', async () => {
            const index = document.getElementById('current-evo-index').value;
            const prompt = document.getElementById('evo-ai-prompt-modal').value.trim();
            
            if (!prompt) {
                showToast('Digite uma descrição da evolução!', 'error');
                return;
            }
            
            generateEvoAiBtn.disabled = true;
            generateEvoAiBtn.textContent = '⏳ Gerando...';
            
            try {
                const response = await fetch('/custom/generate-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prompt: prompt })
                });
                
                const data = await response.json();
                
                if (data.success && data.image_url) {
                    // Baixar a imagem
                    const imageResponse = await fetch(data.image_url);
                    const imageBlob = await imageResponse.blob();
                    const fileName = 'evo_ai_' + Date.now() + '.png';
                    const file = new File([imageBlob], fileName, { type: 'image/png' });
                    
                    // Salvar no objeto de evoluções
                    evolutionImageFiles[index] = file;
                    
                    // Converter para base64 para preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById(`evo-preview-${index}`);
                        if (img) {
                            img.src = e.target.result;
                        }
                        // Atualizar hidden input
                        const hiddenInput = document.querySelector(`input[name="evolutions[${index}][image]"]`);
                        if (hiddenInput) {
                            // Salvar como base64 temporariamente
                            hiddenInput.value = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                    
                    showToast('✨ Imagem da evolução gerada!', 'success');
                    closeEvoAIModal();
                } else {
                    showToast(data.message || 'Erro ao gerar imagem', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro de conexão com o servidor', 'error');
            } finally {
                generateEvoAiBtn.disabled = false;
                generateEvoAiBtn.textContent = 'Gerar Imagem';
            }
        });

        // Upload de imagem da evolução
        function setupEvolutionImageUpload(inputElement, imgId, index) {
            inputElement.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    evolutionImageFiles[index] = file;
                    
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = document.getElementById(imgId);
                        if (img) img.src = event.target.result;
                        
                        const hiddenInput = document.querySelector(`input[name="evolutions[${index}][image]"]`);
                        if (hiddenInput) {
                            hiddenInput.value = event.target.result;
                        }
                        showToast('Imagem da evolução selecionada!', 'success');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Adicionar nova evolução
        function addEvolution() {
            const container = document.getElementById('evolutions-container');
            const currentIndex = evolutionIndex;
            
            const div = document.createElement('div');
            div.className = 'evolution-card glass-card p-5 rounded-2xl';
            div.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="relative group">
                            <img id="evo-preview-${currentIndex}" 
                                 src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' fill='%231a1a2e'/%3E%3Ctext x='40' y='45' text-anchor='middle' fill='%23666' font-size='12'%3ESem imagem%3C/text%3E%3C/svg%3E"
                                 class="w-20 h-20 rounded-full object-cover bg-black/30 cursor-pointer image-upload-area p-1">
                            <div class="absolute inset-0 bg-black/60 rounded-full opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-1">
                                <button type="button" class="upload-evo-btn text-white text-xs bg-blue-600 px-2 py-1 rounded" data-index="${currentIndex}">📷</button>
                                <button type="button" class="ai-evo-btn text-white text-xs bg-purple-600 px-2 py-1 rounded" data-index="${currentIndex}">🤖</button>
                            </div>
                            <input type="file" class="evo-image-input hidden" accept="image/*" data-index="${currentIndex}">
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="text" name="evolutions[${currentIndex}][name]" class="edit-input text-lg font-bold" placeholder="Nome da evolução">
                        <input type="hidden" name="evolutions[${currentIndex}][image]" class="evolution-image-input" value="">
                    </div>
                    <button type="button" class="remove-evolution text-red-400 text-2xl hover:text-red-300">×</button>
                </div>
            `;
            container.appendChild(div);
            
            // Configurar eventos dos botões
            const uploadBtn = div.querySelector('.upload-evo-btn');
            const aiBtn = div.querySelector('.ai-evo-btn');
            const fileInput = div.querySelector('.evo-image-input');
            
            uploadBtn?.addEventListener('click', () => {
                fileInput.click();
            });
            
            aiBtn?.addEventListener('click', () => {
                openEvoAIModal(currentIndex);
            });
            
            setupEvolutionImageUpload(fileInput, `evo-preview-${currentIndex}`, currentIndex);
            evolutionIndex++;
        }

        // Adicionar primeira evolução ao carregar
        addEvolution();

        // Botão de adicionar evolução
        document.getElementById('add-evolution')?.addEventListener('click', addEvolution);

        // ==================== HABILIDADES ====================
        
        let abilityIndex = 1;
        document.getElementById('add-ability')?.addEventListener('click', () => {
            const container = document.getElementById('abilities-container');
            const div = document.createElement('div');
            div.className = 'ability-card glass-card p-5 rounded-2xl flex justify-between items-center gap-3';
            div.innerHTML = `
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-xs font-black text-blue-500 uppercase tracking-[0.2em]">Técnica</span>
                    </div>
                    <input type="text" name="abilities[${abilityIndex}][name]" class="edit-input font-bold text-lg" placeholder="Nome da habilidade">
                </div>
                <select name="abilities[${abilityIndex}][is_hidden]" class="bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm">
                    <option value="0">Normal</option>
                    <option value="1">Oculta</option>
                </select>
                <button type="button" class="remove-ability text-red-400 text-2xl hover:text-red-300">×</button>
            `;
            container.appendChild(div);
            abilityIndex++;
        });

        // Remover itens
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-ability')) {
                e.target.closest('.ability-card')?.remove();
            }
            if (e.target.classList.contains('remove-evolution')) {
                e.target.closest('.evolution-card')?.remove();
            }
        });

        // ==================== SALVAR POKÉMON ====================
        
        const saveBtn = document.getElementById('save-btn');
        
        saveBtn?.addEventListener('click', async () => {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div> Salvando...';
            saveBtn.classList.add('opacity-70');

            const name = document.getElementById('poke-name-input').value;
            if (!name.trim()) {
                showToast('Digite um nome para o Pokémon!', 'error');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '✨ CRIAR POKÉMON';
                saveBtn.classList.remove('opacity-70');
                return;
            }
            
            // Stats
            const stats = {};
            document.querySelectorAll('.stat-slider').forEach(slider => {
                const statName = slider.getAttribute('data-stat');
                stats[statName] = parseInt(slider.value);
            });

            // Habilidades
            const abilities = [];
            document.querySelectorAll('.ability-card').forEach(card => {
                const nameInput = card.querySelector('input[name*="[name]"]');
                const hiddenSelect = card.querySelector('select');
                if (nameInput && nameInput.value.trim()) {
                    abilities.push({
                        name: nameInput.value.trim(),
                        is_hidden: hiddenSelect ? hiddenSelect.value === '1' : false
                    });
                }
            });

            // Evoluções
            const evolutions = [];
            document.querySelectorAll('.evolution-card').forEach((card, idx) => {
                const nameInput = card.querySelector('input[name*="[name]"]');
                const imageHidden = card.querySelector('input[name*="[image]"]');
                if (nameInput && nameInput.value.trim()) {
                    evolutions.push({
                        name: nameInput.value.trim(),
                        image: imageHidden ? imageHidden.value : null
                    });
                }
            });

            const formData = new FormData();
            formData.append('name', name);
            formData.append('height', parseInt(document.getElementById('height-input')?.value) || 17);
            formData.append('weight', parseInt(document.getElementById('weight-input')?.value) || 905);
            formData.append('base_experience', parseInt(document.getElementById('exp-input')?.value) || 100);
            formData.append('stats', JSON.stringify(stats));
            formData.append('abilities', JSON.stringify(abilities));
            formData.append('evolutions', JSON.stringify(evolutions));
            formData.append('description', document.getElementById('description-input')?.value || '');
            formData.append('types', JSON.stringify(selectedTypes));
            
            if (selectedImageFile) {
                formData.append('image', selectedImageFile);
            }

            try {
                const response = await fetch('{{ route("custom-pokemons.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    showToast('✅ Pokémon criado com sucesso! Redirecionando...', 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect_url;
                    }, 1500);
                } else {
                    showToast(result.message || 'Erro ao criar Pokémon', 'error');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '✨ CRIAR POKÉMON';
                    saveBtn.classList.remove('opacity-70');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro ao salvar Pokémon: ' + error.message, 'error');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '✨ CRIAR POKÉMON';
                saveBtn.classList.remove('opacity-70');
            }
        });

        // ==================== TOAST ====================
        
        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast toast-${type} show`;
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Fechar modais com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!aiModal.classList.contains('hidden')) {
                    closeAIModal();
                }
                if (!evoAiModal.classList.contains('hidden')) {
                    closeEvoAIModal();
                }
            }
        });
    </script>
</body>
</html>