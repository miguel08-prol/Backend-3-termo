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

        /* Remove as setas dos inputs numéricos */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield; /* Firefox */
    appearance: textfield;
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
                           placeholder="NOME DO POKÉMON" value="MEU POKÉMON">
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
                            <div class="evolution-card glass-card p-5 rounded-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <img id="evo-preview-0" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png" 
                                             class="w-20 h-20 rounded-full object-cover bg-black/30 cursor-pointer image-upload-area p-1">
                                        <input type="file" class="evo-image-input hidden" accept="image/*" data-target="evo-preview-0" data-index="0">
                                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs cursor-pointer hover:bg-blue-600 transition" 
                                             onclick="this.parentElement.parentElement.querySelector('.evo-image-input').click()">📷</div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" name="evolutions[0][name]" class="edit-input text-lg font-bold" placeholder="Nome da evolução">
                                        <input type="hidden" name="evolutions[0][image]" class="evolution-image-input" value="">
                                    </div>
                                    <button type="button" class="remove-evolution text-red-400 text-2xl hover:text-red-300">×</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-evolution" class="text-blue-400 text-sm hover:text-blue-300 font-semibold mt-3">+ Adicionar Evolução</button>
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
                    <!-- IMAGEM PRINCIPAL -->
                    <div class="relative group">
                        <img id="main-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png" 
                             class="float-poke w-full max-w-[480px] drop-shadow-[0_0_80px_rgba(59,130,246,0.3)] cursor-pointer image-upload-area rounded-2xl p-2">
                        <input type="file" id="main-image-input" class="hidden" accept="image/*">
                        <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-3">
                            <button onclick="document.getElementById('main-image-input').click()" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-xl text-sm font-bold transition">📷 Upload</button>
                            <button id="open-ai-modal" class="bg-purple-600 hover:bg-purple-500 px-4 py-2 rounded-xl text-sm font-bold transition">🤖 Gerar IA</button>
                        </div>
                    </div>

                    <!-- INFO DO POKÉMON -->
                    <div class="glass rounded-2xl p-6 mt-8">
                        <div class="flex justify-around text-center">
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] mb-1 tracking-wider">ALTURA</p>
                                <div class="flex items-center gap-2 justify-center">
                                    <input type="number" id="height-input" class="bg-transparent text-2xl font-bold text-center w-20" value="17" step="1">
                                    <span class="text-gray-400">/10</span>
                                </div>
                                <p class="text-sm text-gray-400"><span id="height-m">1.7</span> metros</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] mb-1 tracking-wider">PESO</p>
                                <div class="flex items-center gap-2 justify-center">
                                    <input type="number" id="weight-input" class="bg-transparent text-2xl font-bold text-center w-20" value="905" step="1">
                                    <span class="text-gray-400">/10</span>
                                </div>
                                <p class="text-sm text-gray-400"><span id="weight-kg">90.5</span> kg</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase font-bold text-[10px] mb-1 tracking-wider">EXPERIÊNCIA</p>
                                <input type="number" id="exp-input" class="bg-transparent text-2xl font-bold text-center w-20" value="100">
                                <p class="text-sm text-gray-400">base</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL DA IA -->
    <div id="ai-modal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden" onclick="if(event.target===this) closeAIModal()">
        <div class="glass p-6 rounded-2xl max-w-md w-full mx-4 transform transition-all">
            <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Gerar Imagem com IA</h3>
            <p class="text-gray-400 text-sm mb-3">Descreva seu Pokémon em detalhes:</p>
            <textarea id="ai-prompt-modal" rows="4" class="edit-input mb-4" placeholder="Ex: Um dragão azul com asas de fogo, estilo Pokémon oficial, cores vibrantes, detalhes em neon"></textarea>
            <div class="flex gap-3">
                <button onclick="closeAIModal()" class="flex-1 glass hover:bg-white/10 py-3 rounded-xl font-semibold transition">Cancelar</button>
                <button id="generate-ai-btn" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 py-3 rounded-xl font-bold transition">Gerar Imagem</button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script>
        // ATUALIZAR BARRA DE STATUS
        function updateStat(slider, spanId) {
            const val = slider.value;
            document.getElementById(spanId).innerText = val;
            const statKey = slider.getAttribute('data-stat');
            const barId = 'bar-' + statKey.replace('-', '_');
            const bar = document.getElementById(barId);
            if(bar) bar.style.width = (val / 255 * 100) + '%';
        }

        // TABS
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

        // PREVIEW ALTURA/PESO
        document.getElementById('height-input')?.addEventListener('input', function() {
            document.getElementById('height-m').innerText = (this.value / 10).toFixed(1);
        });
        document.getElementById('weight-input')?.addEventListener('input', function() {
            document.getElementById('weight-kg').innerText = (this.value / 10).toFixed(1);
        });

        // UPLOAD DA IMAGEM PRINCIPAL
        let selectedImageFile = null;
        
        document.getElementById('main-image-input')?.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                selectedImageFile = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('main-img').src = event.target.result;
                    showToast('Imagem selecionada!', 'success');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // MODAL DA IA
        const aiModal = document.getElementById('ai-modal');
        const openAiModal = document.getElementById('open-ai-modal');
        const closeAIModal = () => aiModal.classList.add('hidden');
        
        openAiModal?.addEventListener('click', () => {
            aiModal.classList.remove('hidden');
        });

        // GERAR IMAGEM COM IA
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
                const response = await fetch('{{ route("custom-pokemons.generate-image") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ prompt: prompt })
                });

                const data = await response.json();
                
                if (data.success && data.image_url) {
                    selectedImageFile = null;
                    document.getElementById('main-image-input').value = '';
                    
                    const img = new Image();
                    img.onload = function() {
                        document.getElementById('main-img').src = data.image_url;
                        showToast('✨ Imagem gerada com sucesso!', 'success');
                        closeAIModal();
                        aiPromptModal.value = '';
                    };
                    img.onerror = function() {
                        showToast('Erro ao carregar a imagem gerada', 'error');
                    };
                    img.src = data.image_url;
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

        // FUNÇÃO PARA UPLOAD DE IMAGEM DE EVOLUÇÃO (converte para Base64)
        function setupEvolutionImageUpload(inputElement, imgId, index) {
            inputElement.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const base64Image = event.target.result;
                        const img = document.getElementById(imgId);
                        if (img) img.src = base64Image;
                        
                        // Salvar a imagem Base64 no campo hidden
                        const hiddenInput = document.querySelector(`input[name="evolutions[${index}][image]"]`);
                        if (hiddenInput) {
                            hiddenInput.value = base64Image;
                        }
                        showToast('Imagem da evolução selecionada!', 'success');
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        // HABILIDADES DINÂMICAS
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

        // EVOLUÇÕES DINÂMICAS
        let evolutionIndex = 1;
        document.getElementById('add-evolution')?.addEventListener('click', () => {
            const container = document.getElementById('evolutions-container');
            const currentIndex = evolutionIndex;
            const div = document.createElement('div');
            div.className = 'evolution-card glass-card p-5 rounded-2xl';
            div.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img id="evo-preview-${currentIndex}" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png" 
                             class="w-20 h-20 rounded-full object-cover bg-black/30 cursor-pointer image-upload-area p-1">
                        <input type="file" class="evo-image-input hidden" accept="image/*" data-target="evo-preview-${currentIndex}" data-index="${currentIndex}">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs cursor-pointer hover:bg-blue-600 transition" 
                             onclick="this.parentElement.parentElement.querySelector('.evo-image-input').click()">📷</div>
                    </div>
                    <div class="flex-1">
                        <input type="text" name="evolutions[${currentIndex}][name]" class="edit-input text-lg font-bold" placeholder="Nome da evolução">
                        <input type="hidden" name="evolutions[${currentIndex}][image]" class="evolution-image-input" value="">
                    </div>
                    <button type="button" class="remove-evolution text-red-400 text-2xl hover:text-red-300">×</button>
                </div>
            `;
            container.appendChild(div);
            
            const fileInput = div.querySelector('.evo-image-input');
            const imgId = `evo-preview-${currentIndex}`;
            setupEvolutionImageUpload(fileInput, imgId, currentIndex);
            evolutionIndex++;
        });

        // CONFIGURAR UPLOAD DAS EVOLUÇÕES EXISTENTES
        document.querySelectorAll('.evo-image-input').forEach(input => {
            const targetId = input.getAttribute('data-target');
            const index = input.getAttribute('data-index');
            if(targetId && index) {
                setupEvolutionImageUpload(input, targetId, parseInt(index));
            }
        });

        // REMOVER ITENS
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-ability')) {
                e.target.closest('.ability-card')?.remove();
            }
            if (e.target.classList.contains('remove-evolution')) {
                e.target.closest('.evolution-card')?.remove();
            }
        });

        // SALVAR POKÉMON
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

            // Evoluções - coletar nome e imagem Base64
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

            // Criar FormData
            const formData = new FormData();
            formData.append('name', name);
            formData.append('height', parseInt(document.getElementById('height-input')?.value) || 17);
            formData.append('weight', parseInt(document.getElementById('weight-input')?.value) || 905);
            formData.append('base_experience', parseInt(document.getElementById('exp-input')?.value) || 100);
            formData.append('stats', JSON.stringify(stats));
            formData.append('abilities', JSON.stringify(abilities));
            formData.append('evolutions', JSON.stringify(evolutions));
            formData.append('types', JSON.stringify([]));
            
            // Imagem principal
            if (selectedImageFile) {
                formData.append('image', selectedImageFile);
            } else {
                const imageUrl = document.getElementById('main-img').src;
                formData.append('image_url', imageUrl);
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

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast toast-${type} show`;
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !aiModal.classList.contains('hidden')) {
                closeAIModal();
            }
        });
    </script>
</body>
</html>