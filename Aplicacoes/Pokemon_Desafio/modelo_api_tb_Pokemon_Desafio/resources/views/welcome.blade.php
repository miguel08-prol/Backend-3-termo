<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pokédex Pro | Sistema Pokémon Profissional</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #090a0f;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Animated Gradient Background */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 20% 50%, rgba(30, 58, 138, 0.3) 0%, rgba(0, 0, 0, 0) 50%),
                        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.2) 0%, rgba(0, 0, 0, 0) 50%),
                        linear-gradient(135deg, #0a0c10 0%, #0f1117 100%);
        }

        /* Animated Grid Pattern */
        .grid-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: 
                linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Glass Morphism */
        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .glass-white {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Hero Section */
        .hero-title {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #f472b6 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: titleGlow 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% {
                filter: brightness(1);
            }
            50% {
                filter: brightness(1.2);
            }
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.6) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(59, 130, 246, 0.6);
            box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.3);
        }

        /* Feature Cards */
        .feature-card {
            position: relative;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(59, 130, 246, 0.15);
            transition: all 0.4s ease;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 20px 40px -20px rgba(59, 130, 246, 0.4);
        }

        /* Pokémon Cards */
        .pokemon-showcase {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.8) 100%);
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .pokemon-showcase:hover {
            transform: translateY(-10px);
            border-color: #3b82f6;
            box-shadow: 0 25px 40px -20px rgba(59, 130, 246, 0.5);
        }

        .pokemon-image {
            transition: transform 0.4s ease;
        }

        .pokemon-showcase:hover .pokemon-image {
            transform: scale(1.1);
        }

        /* Type Badges */
        .type-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(59, 130, 246, 0.15);
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 4s ease-in-out infinite;
        }

        /* Stats Numbers */
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body>

    <div class="gradient-bg"></div>
    <div class="grid-pattern"></div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dragon text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">Pokédex<span class="text-blue-500">Ultra</span></span>
            </div>
            <div class="hidden md:flex gap-8">
                <a href="#features" class="text-gray-300 hover:text-white transition text-sm font-medium">Funcionalidades</a>
                <a href="#pokemons" class="text-gray-300 hover:text-white transition text-sm font-medium">Pokémons</a>
                <a href="#stats" class="text-gray-300 hover:text-white transition text-sm font-medium">Estatísticas</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center px-4 pt-20">
        <div class="max-w-6xl mx-auto text-center">
            <div class="reveal">
                <div class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/20 rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-medium text-blue-400 tracking-wide">SISTEMA PROFISSIONAL</span>
                </div>
                <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold mb-6 tracking-tighter">
                    Gerenciamento<br>
                    <span class="hero-title">Profissional de Pokémons</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10">
                    Plataforma completa para gerenciar, criar e batalhar com Pokémon. 
                    Interface moderna, rápida e intuitiva.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('pokemon.show', 'pikachu') }}" class="btn-primary px-8 py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-rocket"></i>
                        Explorar Pokédex
                    </a>
                    <a href="{{ route('custom-pokemons.create') }}" class="btn-secondary px-8 py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        Criar Pokémon
                    </a>
                </div>
            </div>

            <!-- Stats Preview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-20 reveal" style="transition-delay: 0.2s;">
                <div class="stat-card rounded-xl p-4 text-center">
                    <div class="stat-number">1000+</div>
                    <div class="text-gray-400 text-sm mt-1">Pokémons</div>
                </div>
                <div class="stat-card rounded-xl p-4 text-center">
                    <div class="stat-number">18</div>
                    <div class="text-gray-400 text-sm mt-1">Tipos</div>
                </div>
                <div class="stat-card rounded-xl p-4 text-center">
                    <div class="stat-number">∞</div>
                    <div class="text-gray-400 text-sm mt-1">Customizações</div>
                </div>
                <div class="stat-card rounded-xl p-4 text-center">
                    <div class="stat-number">24/7</div>
                    <div class="text-gray-400 text-sm mt-1">Disponível</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Funcionalidades Poderosas</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Tudo que você precisa para gerenciar seu universo Pokémon em um só lugar
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="feature-card rounded-2xl p-6 reveal">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-book-open text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Pokédex Completa</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Acesse informações detalhadas de todos os Pokémon, incluindo stats, habilidades, evoluções e muito mais.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 reveal" style="transition-delay: 0.1s;">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-palette text-purple-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Criação Customizada</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Crie seus próprios Pokémon com atributos únicos, tipos especiais e habilidades personalizadas.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 reveal" style="transition-delay: 0.2s;">
                    <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-gamepad text-red-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Sistema de Batalha</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Enfrente outros treinadores em batalhas estratégicas e teste o poder dos seus Pokémon.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Pokémon Section -->
    <section id="pokemons" class="py-24 px-4 bg-black/20">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Pokémons em Destaque</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Conheça alguns dos Pokémon mais icônicos do universo
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pikachu -->
                <div class="pokemon-showcase rounded-2xl p-6 text-center reveal" onclick="window.location.href='{{ route('pokemon.show', 'pikachu') }}'">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png" 
                         alt="Pikachu" 
                         class="pokemon-image w-32 h-32 mx-auto mb-4 float-animation">
                    <h3 class="text-xl font-bold mb-2">Pikachu</h3>
                    <div class="flex gap-2 justify-center mb-3">
                        <span class="type-badge bg-yellow-500 text-yellow-900"><i class="fas fa-bolt mr-1"></i>Elétrico</span>
                    </div>
                    <p class="text-gray-400 text-xs">#025 · Pokémon Rato</p>
                </div>

                <!-- Charizard -->
                <div class="pokemon-showcase rounded-2xl p-6 text-center reveal" style="transition-delay: 0.1s;" onclick="window.location.href='{{ route('pokemon.show', 'charizard') }}'">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/6.png" 
                         alt="Charizard" 
                         class="pokemon-image w-32 h-32 mx-auto mb-4 float-animation">
                    <h3 class="text-xl font-bold mb-2">Charizard</h3>
                    <div class="flex gap-2 justify-center mb-3">
                        <span class="type-badge bg-red-600 text-white"><i class="fas fa-fire mr-1"></i>Fogo</span>
                        <span class="type-badge bg-blue-600 text-white"><i class="fas fa-feather-alt mr-1"></i>Voador</span>
                    </div>
                    <p class="text-gray-400 text-xs">#006 · Pokémon Chama</p>
                </div>

                <!-- Mewtwo -->
                <div class="pokemon-showcase rounded-2xl p-6 text-center reveal" style="transition-delay: 0.2s;" onclick="window.location.href='{{ route('pokemon.show', 'mewtwo') }}'">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/150.png" 
                         alt="Mewtwo" 
                         class="pokemon-image w-32 h-32 mx-auto mb-4 float-animation">
                    <h3 class="text-xl font-bold mb-2">Mewtwo</h3>
                    <div class="flex gap-2 justify-center mb-3">
                        <span class="type-badge bg-purple-600 text-white"><i class="fas fa-brain mr-1"></i>Psíquico</span>
                    </div>
                    <p class="text-gray-400 text-xs">#150 · Pokémon Genético</p>
                </div>

                <!-- Greninja -->
                <div class="pokemon-showcase rounded-2xl p-6 text-center reveal" style="transition-delay: 0.3s;" onclick="window.location.href='{{ route('pokemon.show', 'greninja') }}'">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/658.png" 
                         alt="Greninja" 
                         class="pokemon-image w-32 h-32 mx-auto mb-4 float-animation">
                    <h3 class="text-xl font-bold mb-2">Greninja</h3>
                    <div class="flex gap-2 justify-center mb-3">
                        <span class="type-badge bg-blue-600 text-white"><i class="fas fa-tint mr-1"></i>Água</span>
                        <span class="type-badge bg-gray-800 text-white"><i class="fas fa-moon mr-1"></i>Sombrio</span>
                    </div>
                    <p class="text-gray-400 text-xs">#658 · Pokémon Ninja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="glass rounded-3xl p-12 text-center reveal">
                <i class="fas fa-dragon text-blue-500 text-4xl mb-4"></i>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para Começar?</h2>
                <p class="text-gray-400 mb-8 max-w-xl mx-auto">
                    Explore milhares de Pokémon, crie seus próprios personagens e domine o mundo Pokémon.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('pokemon.show', 'pikachu') }}" class="btn-primary px-8 py-3 rounded-xl font-semibold inline-flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        Iniciar Jornada
                    </a>
                    <a href="{{ route('battle.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-semibold inline-flex items-center justify-center gap-2">
                        <i class="fas fa-fist-raised"></i>
                        Batalhar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 px-4 border-t border-white/10">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-gray-500 text-sm">
                &copy; 2024 PokédexUltra. Todos os direitos reservados.
            </p>
            <p class="text-gray-600 text-xs mt-2">
                Pokémon e seus personagens são propriedade da Nintendo, Game Freak e Creatures.
            </p>
        </div>
    </footer>

    <script>
        // Scroll Reveal Animation
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            return rect.top <= windowHeight * 0.85 && rect.bottom >= 0;
        }

        function handleScrollReveal() {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(element => {
                if (isElementInViewport(element)) {
                    element.classList.add('active');
                }
            });
        }

        // Initial check
        window.addEventListener('DOMContentLoaded', () => {
            handleScrollReveal();
            setTimeout(handleScrollReveal, 100);
        });

        // Check on scroll
        window.addEventListener('scroll', handleScrollReveal);
        window.addEventListener('resize', handleScrollReveal);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Parallax effect
        document.addEventListener('mousemove', (e) => {
            const grid = document.querySelector('.grid-pattern');
            if (grid) {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                grid.style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            }
        });
    </script>
</body>
</html>