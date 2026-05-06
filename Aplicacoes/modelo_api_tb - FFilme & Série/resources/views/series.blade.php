<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineDex — Galeria de Séries</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #05070a; 
            color: white;
            background-image: radial-gradient(circle at 50% -10%, #1e1b4b 0%, #05070a 50%);
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .series-card:hover .glass-card {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(225, 29, 72, 0.5);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(225, 29, 72, 0.1);
        }

        .btn-fav {
            position: absolute; top: 12px; right: 12px; z-index: 20;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
            border-radius: 50%; width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; border: 1px solid rgba(255,255,255,0.1);
            opacity: 0; transform: translateY(-5px);
        }
        .series-card:hover .btn-fav { opacity: 1; transform: translateY(0); }
        .btn-fav.is-saved svg { fill: #e11d48; stroke: #e11d48; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .pop-active { animation: pop 0.4s ease; }
    </style>
</head>
<body class="pb-32">

    <header class="w-full px-6 md:px-12 py-10 flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="reveal">
            <h1 class="text-4xl font-black italic tracking-tighter uppercase leading-none">
                EXPLORE <span class="text-rose-600">SÉRIES</span>
            </h1>
            <div class="flex items-center gap-2 mt-2">
                <div class="h-1 w-12 bg-rose-600 rounded-full"></div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">{{ $tituloPagina ?? 'Melhores Produções' }}</p>
            </div>
        </div>

        <form action="{{ route('series.index') }}" method="GET" class="w-full md:w-[450px]">
            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar série ou gênero..." 
                    class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-14 focus:outline-none focus:border-rose-600/50 focus:bg-white/10 transition-all font-medium placeholder:text-gray-600">
                <button type="submit" class="absolute right-2 top-2 bottom-2 px-4 bg-rose-600 rounded-xl text-white hover:bg-rose-700 transition-colors shadow-lg shadow-rose-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/></svg>
                </button>
            </div>
        </form>
    </header>

    <div class="px-6 md:px-12 mb-12 flex gap-4 overflow-x-auto no-scrollbar py-2">
        @foreach(['Todas', 'Drama', 'Sci-Fi & Fantasy', 'Mistério', 'Animação', 'Ação', 'Documentário'] as $cat)
            <button class="glass-card px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-rose-600 hover:border-rose-600 transition-all whitespace-nowrap {{ $loop->first ? 'bg-rose-600 border-rose-600' : '' }}">
                {{ $cat }}
            </button>
        @endforeach
    </div>

    <main class="px-6 md:px-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
            @forelse($series as $serie)
                <div class="series-card relative">
                    <button onclick="toggleFav('{{ $serie['id'] }}', '{{ addslashes($serie['name']) }}', '{{ $serie['poster_path'] }}', this)" 
                            class="btn-fav fav-btn-check" data-id="{{ $serie['id'] }}">
                        <svg class="w-5 h-5" fill="none" stroke="white" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke-width="2"/></svg>
                    </button>

                    <a href="{{ route('detalhes', ['type' => 'tv', 'id' => $serie['id']]) }}" class="block glass-card rounded-[2rem] overflow-hidden">
                        <div class="relative aspect-[2/3] overflow-hidden">
                            @if($serie['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $serie['poster_path'] }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            @else
                                <div class="w-full h-full bg-white/5 flex items-center justify-center text-gray-700 font-bold uppercase text-xs">Sem Poster</div>
                            @endif
                            
                            <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10">
                                <span class="text-yellow-400 font-black text-[11px] flex items-center gap-1">
                                    <svg class="w-3 h-3 fill-yellow-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    {{ number_format($serie['vote_average'], 1) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-extrabold text-sm truncate uppercase tracking-tighter leading-tight group-hover:text-rose-500 transition-colors">
                                {{ $serie['name'] }}
                            </h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[9px] text-rose-500 font-black uppercase tracking-tighter bg-rose-500/10 px-2 py-0.5 rounded">
                                    {{ substr($serie['first_air_date'] ?? '----', 0, 4) }}
                                </span>
                                <span class="text-[9px] text-gray-500 font-bold uppercase">• Série</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center opacity-40">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="1.5"/></svg>
                    <p class="font-black uppercase italic tracking-widest">Nenhuma série encontrada</p>
                </div>
            @endforelse
        </div>
    </main>

    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-black/40 backdrop-blur-2xl border border-white/10 px-10 py-5 rounded-[2.5rem] flex gap-12 z-50 shadow-2xl">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-white transition-all flex flex-col items-center gap-1 group">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-width="2.5"/></svg>
            <span class="text-[9px] font-black tracking-widest uppercase">Home</span>
        </a>
        <a href="{{ route('filmes.index') }}" class="text-gray-500 hover:text-white transition-all flex flex-col items-center gap-1 group">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 4v16l13-8z" stroke-width="2"/></svg>
            <span class="text-[9px] font-black tracking-widest uppercase">Filmes</span>
        </a>
        <a href="#" class="text-rose-500 flex flex-col items-center gap-1 group">
            <svg class="w-6 h-6 scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M21 6h-7.59l3.29-3.29L16 2l-4 4-4-4-.71.71L10.59 6H3a2 2 0 00-2 2v12c0 1.1.9 2 2 2h18a2 2 0 002-2V8a2 2 0 00-2-2zm0 14H3V8h18v12zM9 10v8l7-4z"/></svg>
            <span class="text-[9px] font-black tracking-widest uppercase">Séries</span>
        </a>
    </div>

    <script>
        function toggleFav(id, title, poster, btn) {
            let favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
            const index = favs.findIndex(f => f.id == id);

            if (index === -1) {
                favs.push({ id, title, poster, type: 'tv' });
                btn.classList.add('is-saved', 'pop-active');
            } else {
                favs.splice(index, 1);
                btn.classList.remove('is-saved');
            }

            localStorage.setItem('cinedex_favorites', JSON.stringify(favs));
            setTimeout(() => btn.classList.remove('pop-active'), 400);
        }

        function checkFavs() {
            const favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
            document.querySelectorAll('.fav-btn-check').forEach(btn => {
                const id = btn.getAttribute('data-id');
                if (favs.some(f => f.id == id)) {
                    btn.classList.add('is-saved');
                }
            });
        }

        window.onload = checkFavs;
    </script>
</body>
</html>