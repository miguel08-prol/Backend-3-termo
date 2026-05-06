<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineDex Ultra — Streaming Experience</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos Globais */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #02040a; 
            color: #f8fafc; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar com desfoque */
        .nav-blur { 
            backdrop-filter: blur(20px); 
            background: rgba(2, 4, 10, 0.8); 
            border-bottom: 1px solid rgba(255,255,255,0.05); 
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #02040a; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e11d48; }

        /* Animações suaves */
        .fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Estilo para links ativos */
        .nav-link-active {
            color: #f8fafc !important;
            position: relative;
        }
        .nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #e11d48;
            border-radius: 10px;
        }

        /* Modal de Trailer */
        #modal-trailer { 
            display: none; 
            position: fixed; 
            inset: 0; 
            z-index: 200; 
            background: rgba(0,0,0,0.95); 
            align-items: center; 
            justify-content: center; 
        }
        #modal-trailer.active { display: flex; animation: fadeIn 0.3s ease; }
    </style>
    
    @yield('styles')
</head>
<body class="fade-in">

    <nav class="fixed top-0 w-full z-[100] nav-blur px-8 md:px-16 py-5 flex justify-between items-center transition-all duration-500" id="main-nav">
        
        <div class="flex items-center gap-12">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tighter italic hover:scale-105 transition-transform">
                CINE<span class="text-rose-600">DEX</span>
            </a>
            
            <div class="hidden lg:flex gap-8 text-[12px] font-bold uppercase tracking-widest text-gray-400">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') && !request('search') ? 'nav-link-active' : 'hover:text-white' }} transition">Início</a>
                <a href="{{ route('filmes.index') }}" class="{{ request()->routeIs('filmes.index') ? 'nav-link-active' : 'hover:text-white' }} transition">Filmes</a>
                <a href="{{ route('series.index') }}" class="{{ request()->routeIs('series.index') ? 'nav-link-active' : 'hover:text-white' }} transition">Séries</a>
            </div>
        </div>
        
        <div class="flex items-center gap-8">
            <form action="{{ route('home') }}" method="GET" class="relative hidden sm:block">
                <input type="text" name="search" placeholder="Filmes, Séries..." value="{{ request('search') }}"
                    class="bg-white/5 border border-white/10 rounded-full py-2.5 px-6 pl-11 text-xs w-64 focus:outline-none focus:border-rose-600 focus:w-80 focus:bg-white/10 transition-all text-white">
                <button type="submit" class="absolute left-4 top-3 text-gray-500 hover:text-rose-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/>
                    </svg>
                </button>
            </form>
            
            <div class="group relative cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 shadow-lg shadow-rose-900/20 flex items-center justify-center font-bold text-white transition-transform group-hover:scale-110">
                    G
                </div>
                <div class="absolute right-0 top-full mt-2 w-48 bg-slate-900 border border-white/10 rounded-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <a href="#" class="block px-4 py-2 text-xs hover:bg-rose-600 transition">Minha Conta</a>
                    <a href="#" class="block px-4 py-2 text-xs hover:bg-rose-600 transition">Configurações</a>
                    <div class="h-px bg-white/10 my-1"></div>
                    <a href="#" class="block px-4 py-2 text-xs text-rose-500 hover:bg-rose-600 hover:text-white transition">Sair</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <div id="modal-trailer" onclick="closeTrailer()">
        <div class="relative w-[90%] max-w-5xl aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
            <button onclick="closeTrailer()" class="absolute top-4 right-4 z-50 bg-rose-600 text-white w-10 h-10 rounded-full font-bold hover:bg-rose-700 transition">✕</button>
            <div id="trailer-player" class="w-full h-full"></div>
        </div>
    </div>

    <footer class="py-20 bg-[#010206] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-8 flex flex-col items-center">
            <h2 class="text-3xl font-black italic mb-6">CINE<span class="text-rose-600">DEX</span></h2>
            <div class="flex gap-8 mb-10 text-gray-500 text-xs font-bold uppercase tracking-widest">
                <a href="#" class="hover:text-white transition">Privacidade</a>
                <a href="#" class="hover:text-white transition">Termos de Uso</a>
                <a href="#" class="hover:text-white transition">Ajuda</a>
            </div>
            <p class="text-[10px] text-gray-600 uppercase tracking-[0.5em]">
                © 2026 CineDex Ultra - Powered by TMDB API
            </p>
        </div>
    </footer>

    <script>
        // 1. Efeito de scroll na Navbar
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 100) {
                nav.classList.add('py-3', 'shadow-2xl');
                nav.style.background = "rgba(2, 4, 10, 0.95)";
            } else {
                nav.classList.remove('py-3', 'shadow-2xl');
                nav.style.background = "rgba(2, 4, 10, 0.8)";
            }
        });

        // 2. Lógica de Trailers
function openTrailer(id, type) {
    const container = document.getElementById('trailer-player');
    const apiKey = 'ca2223bfd7647b65c24cdc54bd2e8e1f'; 
    
    fetch(`https://api.themoviedb.org/3/${type}/${id}/videos?api_key=${apiKey}&language=pt-BR`)
        .then(r => r.json())
        .then(data => {
            const video = data.results.find(v => v.type === 'Trailer' && v.site === 'YouTube') || data.results[0];
            if (video) {
                container.innerHTML = `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${video.key}?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
                document.getElementById('modal-trailer').classList.add('active');
            } else {
                // Se showToast existir na página, usa ele. Se não, silencia o erro.
                if (typeof showToast === "function") {
                    showToast("Trailer indisponível no momento 🚫");
                }
            }
        })
        .catch(() => {
            if (typeof showToast === "function") showToast("Erro ao carregar vídeo.");
        });
}

        function closeTrailer() {
            document.getElementById('modal-trailer').classList.remove('active');
            document.getElementById('trailer-player').innerHTML = '';
        }

        // 3. Lógica de Favoritos (LocalStorage)
        function toggleFavorite(id, title, poster) {
            let favs = JSON.parse(localStorage.getItem('cinedex_favorites')) || [];
            const index = favs.findIndex(f => f.id == id);

            if (index > -1) {
                favs.splice(index, 1);
                alert('Removido dos favoritos!');
            } else {
                favs.push({ id, title, poster });
                alert('Adicionado aos favoritos!');
            }
            localStorage.setItem('cinedex_favorites', JSON.stringify(favs));
            
            // Atualiza a grade de favoritos se a função renderFavs existir na view
            if (typeof renderFavs === "function") renderFavs();
        }

        function toggleFavList() {
            const favSection = document.getElementById('fav-section');
            if (favSection) {
                favSection.classList.toggle('hidden');
                if (!favSection.classList.contains('hidden') && typeof renderFavs === "function") {
                    renderFavs();
                }
            } else {
                alert("Abra a página inicial para ver seus favoritos!");
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>