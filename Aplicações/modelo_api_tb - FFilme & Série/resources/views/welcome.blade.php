@extends('layouts.app')

@section('styles')
<style>
    .hero-mask {
        background: linear-gradient(to top, #02040a 10%, transparent 60%),
                    linear-gradient(to right, #02040a 20%, transparent 70%);
    }

    .carousel-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
    
    .scroll-container { 
        display: flex; gap: 1.2rem; overflow-x: auto; scroll-behavior: smooth; 
        padding: 1.5rem 0 3.5rem 0; width: 100%;
    }
    .scroll-container::-webkit-scrollbar { display: none; }
    
    .movie-card { 
        min-width: 200px; position: relative; border-radius: 14px; 
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        cursor: pointer; overflow: hidden;
    }
    .movie-card:hover { transform: scale(1.1) translateY(-10px); z-index: 40; box-shadow: 0 20px 40px rgba(0,0,0,0.6); }

    /* Overlay */
    .card-overlay {
        background: rgba(0,0,0,0.7);
        border-radius: 12px; opacity: 0;
        transition: all 0.3s ease;
    }
    .movie-card:hover .card-overlay { opacity: 1; }

    /* Botão de Favoritar (Topo Direito) */
    .btn-fav-top {
        position: absolute; top: 10px; right: 10px; z-index: 60;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
        border-radius: 50%; width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-fav-top:hover { transform: scale(1.2); background: rgba(0,0,0,0.8); }
    .btn-fav-top svg { width: 20px; height: 20px; transition: all 0.3s; fill: none; stroke: white; stroke-width: 2; }
    
    /* Estado Ativo (Salvo) */
    .btn-fav-top.is-saved svg { fill: #e11d48; stroke: #e11d48; }
    .btn-fav-top.bounce { animation: heartPop 0.4s linear; }

    @keyframes heartPop {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1.2); }
    }

    /* Setas Carrossel */
    .nav-btn {
        position: absolute; z-index: 50; background: rgba(0,0,0,0.6);
        color: white; border: none; width: 45px; height: 45px; border-radius: 50%;
        cursor: pointer; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s; opacity: 0;
    }
    .carousel-wrapper:hover .nav-btn { opacity: 1; }
    .nav-btn:hover { background: #e11d48; }
    .btn-prev { left: 15px; }
    .btn-next { right: 15px; }

    /* Toast */
    #toast {
        position: fixed; bottom: 30px; right: 30px; z-index: 1000;
        background: #e11d48; color: white; padding: 14px 28px;
        border-radius: 16px; font-weight: 800; transform: translateX(150%);
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 10px 40px rgba(225, 29, 72, 0.4);
        display: flex; align-items: center; gap: 10px;
    }
    #toast.show { transform: translateX(0); }

    .section-title {
        display: flex; align-items: center; gap: 12px;
        padding-left: 4rem; font-size: 1.4rem; font-weight: 900; 
        text-transform: uppercase; font-style: italic; letter-spacing: -1px;
    }
    .section-title span { color: #e11d48; }
    .indicator { width: 4px; height: 24px; background: #e11d48; border-radius: 10px; }
</style>
@endsection

@section('content')

    <div id="toast">
        <span id="toast-icon">🍿</span>
        <span id="toast-text">Atualizado!</span>
    </div>

    @if(isset($isSearch) && $isSearch)
        <div class="pt-32 px-8 md:px-16 pb-20 reveal min-h-[70vh]">
            <h2 class="text-3xl font-black uppercase italic mb-10 border-l-4 border-rose-600 pl-4">
                Busca: <span class="text-rose-600">"{{ $searchTerm ?? '' }}"</span>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                @forelse($filmes as $item)
                    <div class="movie-card group" id="card-{{ $item['id'] }}">
                        <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}" class="w-full h-auto object-cover">
                        
                        <button onclick="saveFav('{{ $item['id'] }}', '{{ addslashes($item['title'] ?? $item['name'] ?? 'Título') }}', '{{ $item['poster_path'] }}', this)" 
                                class="btn-fav-top fav-btn-check opacity-0 group-hover:opacity-100" data-id="{{ $item['id'] }}">
                            <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>

                        <div class="card-overlay absolute inset-0 flex items-center justify-center">
                            <button onclick="openTrailer('{{ $item['id'] }}', '{{ $item['media_type'] ?? 'movie' }}')" class="bg-rose-600 p-4 rounded-full hover:scale-110 transition shadow-lg shadow-rose-600/40">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center opacity-50">Nenhum resultado encontrado.</div>
                @endforelse
            </div>
        </div>
    @else
        {{-- HOME --}}
        @if(isset($destaque) && $destaque)
        <header class="relative w-full h-[85vh] flex items-center px-16 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="https://image.tmdb.org/t/p/original{{ $destaque['backdrop_path'] ?? '' }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 hero-mask"></div>
            </div>
            <div class="relative z-10 max-w-3xl">
                <span class="bg-rose-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Destaque</span>
                <h2 class="text-7xl font-black mt-4 mb-6 leading-[0.9] tracking-tighter uppercase italic">{{ $destaque['title'] ?? $destaque['name'] ?? '' }}</h2>
                <div class="flex gap-4">
                    <button onclick="openTrailer('{{ $destaque['id'] }}', 'movie')" class="bg-white text-black px-10 py-4 rounded-xl font-black uppercase text-sm hover:bg-rose-600 hover:text-white transition-all flex items-center gap-2">
                        ▶ Play Trailer
                    </button>
                    <button onclick="saveFav('{{ $destaque['id'] }}', '{{ addslashes($destaque['title'] ?? $destaque['name'] ?? '') }}', '{{ $destaque['poster_path'] ?? '' }}', this)" 
                            class="bg-white/10 fav-btn-check backdrop-blur-md border border-white/20 px-10 py-4 rounded-xl font-black uppercase text-sm hover:bg-white/20 transition-all" data-id="{{ $destaque['id'] }}">
                        ♥ Minha Lista
                    </button>
                </div>
            </div>
        </header>
        @endif

        <div class="relative z-20 -mt-20 pb-20 space-y-16">
            <section id="fav-section" class="hidden">
                <div class="section-title"><div class="indicator"></div> Minha <span>Lista</span></div>
                <div id="fav-grid" class="grid grid-cols-2 md:grid-cols-6 gap-6 px-16 py-8"></div>
            </section>

            @php
                $rows = [
                    ['title' => 'Filmes <span>Populares</span>', 'data' => $filmes ?? [], 'type' => 'movie', 'id' => 'row1'],
                    ['title' => 'Séries <span>Premium</span>', 'data' => $series ?? [], 'type' => 'tv', 'id' => 'row2'],
                    ['title' => 'Ação & <span>Adrenalina</span>', 'data' => $acao ?? [], 'type' => 'movie', 'id' => 'row3'],
                    ['title' => 'Melhores <span>Avaliados</span>', 'data' => $topRated ?? [], 'type' => 'movie', 'id' => 'row4'],
                    ['title' => 'Noite de <span>Terror</span>', 'data' => $terror ?? [], 'type' => 'movie', 'id' => 'row5'],
                    ['title' => 'Universo <span>Sci-Fi</span>', 'data' => $scifi ?? [], 'type' => 'movie', 'id' => 'row6'],
                ];
            @endphp

            @foreach($rows as $row)
                @if(count($row['data']) > 0)
                <section>
                    <div class="section-title"><div class="indicator"></div> {!! $row['title'] !!}</div>
                    <div class="carousel-wrapper px-16">
                        <button class="nav-btn btn-prev" onclick="scrollRow('{{ $row['id'] }}', -1)">❮</button>
                        <div class="scroll-container" id="{{ $row['id'] }}">
                            @foreach($row['data'] as $f)
                                <div class="movie-card group">
                                    <img src="https://image.tmdb.org/t/p/w500{{ $f['poster_path'] }}" class="rounded-xl w-full h-auto object-cover">
                                    
                                    <button onclick="saveFav('{{ $f['id'] }}', '{{ addslashes($f['title'] ?? $f['name'] ?? 'Título') }}', '{{ $f['poster_path'] }}', this)" 
                                            class="btn-fav-top fav-btn-check opacity-0 group-hover:opacity-100" data-id="{{ $f['id'] }}">
                                        <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </button>

                                    <div class="card-overlay absolute inset-0 flex items-center justify-center">
                                        <button onclick="openTrailer('{{ $f['id'] }}', '{{ $row['type'] }}')" class="bg-rose-600 p-4 rounded-full hover:scale-110 transition shadow-lg shadow-rose-600/40">
                                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="nav-btn btn-next" onclick="scrollRow('{{ $row['id'] }}', 1)">❯</button>
                    </div>
                </section>
                @endif
            @endforeach
        </div>
    @endif
@endsection

@section('scripts')
<script>
    // Gerenciador do Toast
    function showToast(msg, icon = "🍿") {
        const toast = document.getElementById('toast');
        document.getElementById('toast-text').innerText = msg;
        document.getElementById('toast-icon').innerText = icon;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // Carousel
    function scrollRow(rowId, direction) {
        const row = document.getElementById(rowId);
        const scrollAmount = row.clientWidth * 0.8; 
        row.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
    }

    // Favoritos com Animação e Check de Ícone
    function saveFav(id, title, poster, btn) {
        if(!id) return;
        let favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
        const index = favs.findIndex(f => f.id == id);

        if (index === -1) {
            favs.push({ id, title, poster });
            localStorage.setItem('cinedex_favorites', JSON.stringify(favs));
            showToast(`"${title}" salvo!`, "💖");
        } else {
            favs.splice(index, 1);
            localStorage.setItem('cinedex_favorites', JSON.stringify(favs));
            showToast(`Removido da lista.`, "❌");
        }
        
        // Efeito de Pulo no botão
        if(btn) {
            btn.classList.add('bounce');
            setTimeout(() => btn.classList.remove('bounce'), 400);
        }

        updateFavIcons();
        renderFavs();
    }

    // Atualiza visualmente todos os botões se o filme está salvo ou não
    function updateFavIcons() {
        const favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
        const buttons = document.querySelectorAll('.fav-btn-check');
        
        buttons.forEach(btn => {
            const id = btn.getAttribute('data-id');
            const isSaved = favs.some(f => f.id == id);
            if(isSaved) {
                btn.classList.add('is-saved');
            } else {
                btn.classList.remove('is-saved');
            }
        });
    }

    function renderFavs() {
        const favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
        const section = document.getElementById('fav-section');
        const grid = document.getElementById('fav-grid');
        if(!section || !grid) return;

        if (favs.length > 0) {
            section.classList.remove('hidden');
            grid.innerHTML = favs.map(f => `
                <div class="movie-card group">
                    <img src="https://image.tmdb.org/t/p/w500${f.poster}" class="rounded-xl w-full border border-white/5">
                    <div class="card-overlay absolute inset-0 flex items-center justify-center">
                        <button onclick="saveFav('${f.id}', '', '', null)" class="bg-rose-600 px-6 py-2 rounded-full text-xs font-black uppercase hover:scale-110 transition">Remover</button>
                    </div>
                </div>
            `).join('');
        } else {
            section.classList.add('hidden');
        }
    }

    window.onload = () => {
        updateFavIcons();
        renderFavs();
    };
</script>
@endsection