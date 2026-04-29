<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Ultra - {{ ucfirst($pokemon['name']) }}</title>
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
    </style>
</head>
<body>

    <div class="bg-animate"><div class="blob"></div></div>

    <nav class="fixed top-0 w-full z-50 glass px-6 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded-full border-2 border-white animate-pulse"></div>
                <span class="text-xl font-extrabold tracking-tighter uppercase italic">Pokédex <span class="text-blue-400">Ultra</span></span>
            </div>
            <div class="hidden md:flex gap-8 text-sm font-semibold text-gray-400">
                <button onclick="openTab(event, 'stats')" class="tab-link text-white border-b-2 border-blue-500 pb-1">Status</button>
                <button onclick="openTab(event, 'habilidades')" class="tab-link hover:text-white pb-1">Habilidades</button>
                <button onclick="openTab(event, 'evolucao')" class="tab-link hover:text-white pb-1">Evoluções</button>
            </div>
        </div>
    </nav>

    <main class="relative pt-32 pb-20 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <div class="order-2 lg:order-1">
                <div class="mb-8 max-w-sm">
                    <div class="search-wrapper flex items-center">
                        <input type="text" id="poke-search" placeholder="Pesquisar nome ou ID..." class="search-input">
                        <div class="absolute right-4 text-gray-500 text-[10px] font-black tracking-widest">ENTER</div>
                    </div>
                </div>
                
                <h1 id="poke-name" class="text-6xl md:text-8xl font-extrabold capitalize mb-8 tracking-tighter">
                    {{ $pokemon['name'] }}
                </h1>

                <div class="no-scrollbar pr-4">
                    <div id="stats" class="tab-content active space-y-6">
                        @foreach($pokemon['stats'] as $stat)
                        @php
                            $traducao = match($stat['stat']['name']) {
                                'hp' => 'Vida (HP)', 'attack' => 'Ataque', 'defense' => 'Defesa',
                                'special-attack' => 'Atq. Especial', 'special-defense' => 'Def. Especial',
                                'speed' => 'Velocidade', default => $stat['stat']['name']
                            };
                        @endphp
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium uppercase tracking-wider text-gray-400">{{ $traducao }}</span>
                                <span class="text-sm font-bold">{{ $stat['base_stat'] }}</span>
                            </div>
                            <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all duration-1000" 
                                     style="width: {{ ($stat['base_stat'] / 255) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="habilidades" class="tab-content space-y-4">
                        @foreach($pokemon['abilities'] as $h)
                        <div class="ability-card glass p-5 rounded-2xl flex justify-between items-center group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-black text-blue-500 uppercase tracking-[0.2em] mb-0.5">Técnica</span>
                                    <span class="text-xl font-bold capitalize">{{ str_replace('-', ' ', $h['ability']['name']) }}</span>
                                </div>
                            </div>
                            @if($h['is_hidden'])
                                <span class="text-[10px] bg-purple-500/20 text-purple-400 px-3 py-1 rounded-full font-black uppercase border border-purple-500/30">Oculta</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div id="evolucao" class="tab-content">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($evolucaoDetalhes as $evo)
                            <button onclick="updateDisplay('{{ $evo['nome'] }}', '{{ $evo['foto'] }}', this)" 
                                    class="evo-card glass p-4 rounded-2xl flex items-center gap-4 border-l-4 {{ $evo['nome'] == $pokemon['name'] ? 'border-blue-500 bg-blue-500/10' : 'border-transparent opacity-60' }}">
                                <img src="{{ $evo['foto'] }}" class="w-14 h-14">
                                <span class="text-xl font-bold capitalize">{{ $evo['nome'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <a href="{{ url('/pokemon/' . ($pokemon['id'] - 1)) }}" class="glass hover:bg-white/10 text-white font-bold py-4 px-8 rounded-2xl transition-all active:scale-95 flex items-center gap-2 border-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        VOLTAR
                    </a>
                    <a href="{{ url('/pokemon/' . ($pokemon['id'] + 1)) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-10 rounded-2xl transition-all shadow-xl shadow-blue-900/30 active:scale-95 flex-1 text-center">
                        PRÓXIMO
                    </a>
                </div>
            </div>

            <div class="order-1 lg:order-2 flex justify-center relative">
                <div class="absolute inset-0 bg-blue-600/10 blur-[150px] rounded-full animate-pulse"></div>
                <img id="main-img" src="{{ $pokemon['sprites']['other']['official-artwork']['front_default'] }}" 
                     class="float-poke w-full max-w-[480px] drop-shadow-[0_0_80px_rgba(59,130,246,0.3)] transition-all duration-700">
            </div>
        </div>
    </main>

    <footer class="fixed bottom-0 w-full hidden md:block py-6 glass border-t-0 border-white/5">
        <div class="max-w-6xl mx-auto flex justify-around text-center">
            <div><p class="text-gray-500 uppercase font-bold text-[10px] mb-1">Altura</p><p class="text-xl font-bold">{{ $pokemon['height'] / 10 }}m</p></div>
            <div><p class="text-gray-500 uppercase font-bold text-[10px] mb-1">Peso</p><p class="text-xl font-bold">{{ $pokemon['weight'] / 10 }}kg</p></div>
            <div><p class="text-gray-500 uppercase font-bold text-[10px] mb-1">Experiência</p><p class="text-xl font-bold">{{ $pokemon['base_experience'] }}</p></div>
        </div>
    </footer>

<script>
    document.getElementById('poke-search').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const query = this.value.toLowerCase().trim();
            if(query) window.location.href = `/pokemon/${query}`;
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
    }

    function updateDisplay(nome, foto, element) {
        const nameTitle = document.getElementById('poke-name');
        const mainImg = document.getElementById('main-img');
        
        // --- ANIMAÇÃO DE SAÍDA ---
        mainImg.style.filter = 'blur(20px) brightness(2)';
        mainImg.style.transform = 'scale(0.5) translateY(50px)';
        mainImg.style.opacity = '0';
        nameTitle.style.opacity = '0';
        nameTitle.style.transform = 'translateX(-20px)';

        setTimeout(() => {
            // Troca os dados
            nameTitle.innerText = nome;
            mainImg.src = foto;

            // --- ANIMAÇÃO DE ENTRADA ---
            mainImg.style.filter = 'blur(0px) brightness(1)';
            mainImg.style.transform = 'scale(1.1) translateY(0)';
            mainImg.style.opacity = '1';
            
            nameTitle.style.opacity = '1';
            nameTitle.style.transform = 'translateX(0)';

            // Ajuste final para a animação flutuante original não bugar
            setTimeout(() => {
                mainImg.style.transform = ''; // Devolve o controle para a classe .float-poke
            }, 500);
        }, 300);

        // Estilo dos cards de evolução
        const cards = document.querySelectorAll('.evo-card');
        cards.forEach(card => {
            card.classList.remove('border-blue-500', 'bg-blue-500/10');
            card.classList.add('border-transparent', 'opacity-60');
        });
        element.classList.add('border-blue-500', 'bg-blue-500/10');
        element.classList.remove('border-transparent', 'opacity-60');
    }
</script>
</body>
</html>