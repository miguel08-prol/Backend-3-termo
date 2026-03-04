<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confecção Pro | O Sistema Definitivo</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .text-gradient { background: linear-gradient(90deg, #4F46E5, #9333EA); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Prevenção de lag */
     [data-aos] {
        transition-property: transform, opacity !important;
    }
    
    /* Evita que o scroll trave ao recalcular animações */
    html, body {
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    ::-webkit-scrollbar {
    display: none;
}

/* Esconde a barra de rolagem no IE, Edge e Firefox */
html {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
    </style>
</head>
<body class="bg-white text-slate-900 antialiased">

    <nav class="fixed w-full z-50 top-0 px-4 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center glass border border-slate-100 rounded-3xl px-6 py-3 shadow-xl shadow-indigo-100/50">
            <div class="flex items-center gap-2">
                <div class="gradient-bg p-2 rounded-xl text-white shadow-lg shadow-indigo-200">
                    <i class="ph-fill ph-needle-thread text-xl"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tighter italic">Confecção<span class="text-indigo-600">PRO</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-600">
                <a href="#recursos" class="hover:text-indigo-600 transition">Recursos</a>
                <a href="#metodo" class="hover:text-indigo-600 transition">Como funciona</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Entrar</a>
                        <a href="{{ route('register') }}" class="bg-slate-900 text-white px-6 py-2.5 rounded-2xl hover:bg-slate-800 transition">Assinar Agora</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="relative pt-44 pb-32 overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div data-aos="fade-up">
                <span class="px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-extrabold uppercase tracking-widest border border-indigo-100 mb-8 inline-block">
                    ✨ O Software nº 1 para Ateliês
                </span>
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter leading-tight mb-8" data-aos="fade-up" data-aos-delay="100">
                Sua produção na palma <br> da sua <span class="text-gradient">mão.</span>
            </h1>
            <p class="text-xl text-slate-500 max-w-2xl mx-auto mb-12 font-medium leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Pare de perder dinheiro com retalhos e atrasos. O ConfecçãoPro organiza seu estoque, clientes e ordens de serviço de forma automática.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 gradient-bg text-white rounded-2xl font-bold text-lg shadow-2xl shadow-indigo-300 hover:scale-105 transition-all">
                    Começar Gratuitamente
                </a>
                <div class="flex items-center gap-2 text-slate-400 text-sm font-bold px-6">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    Sem cartão de crédito
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 mt-24 grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center">
                <div class="text-4xl font-black text-slate-900">+40%</div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Produtividade</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-slate-900">100%</div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Em Nuvem</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-slate-900">+2k</div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Usuários</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-slate-900">Zero</div>
                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Papelada</div>
            </div>
        </div>
    </section>

    <section id="metodo" class="py-32 bg-slate-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2" data-aos="fade-right">
                    <h2 class="text-4xl font-extrabold tracking-tighter mb-6">Como o ConfecçãoPRO <br> organiza seu caos:</h2>
                    <div class="space-y-8 mt-10">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md text-indigo-600 font-bold text-xl">1</div>
                            <div>
                                <h4 class="font-bold text-lg">Cadastro de Medidas</h4>
                                <p class="text-slate-500">Nunca mais pergunte o tamanho do cliente. Tudo salvo no perfil.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md text-indigo-600 font-bold text-xl">2</div>
                            <div>
                                <h4 class="font-bold text-lg">Corte e Costura</h4>
                                <p class="text-slate-500">Crie ordens de produção e saiba quem está fazendo o quê.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 shrink-0 bg-white rounded-2xl flex items-center justify-center shadow-md text-indigo-600 font-bold text-xl">3</div>
                            <div>
                                <h4 class="font-bold text-lg">Entrega Inteligente</h4>
                                <p class="text-slate-500">O sistema avisa o cliente quando a peça está pronta para retirada.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 relative" data-aos="fade-left">
                    <div class="bg-indigo-600 rounded-[3rem] p-4 shadow-3xl">
                        <img src="https://images.unsplash.com/photo-1535957998253-26ae1ef29506?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-[2.5rem] shadow-inner opacity-90" alt="Workflow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="gradient-bg rounded-[3rem] p-12 md:p-20 text-center text-white shadow-2xl shadow-indigo-200" data-aos="zoom-in">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tighter mb-8">Pronto para profissionalizar <br> sua marca?</h2>
                <p class="text-indigo-100 text-lg mb-12 max-w-xl mx-auto">Junte-se a centenas de ateliês que faturam mais com menos esforço.</p>
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-12 py-5 rounded-2xl font-bold text-xl shadow-xl hover:scale-105 transition-all inline-block">
                    Criar minha conta grátis
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-white pt-24 pb-12 border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <div class="gradient-bg p-2 rounded-xl text-white">
                        <i class="ph-fill ph-needle-thread text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tighter italic">ConfecçãoPRO</span>
                </div>
                <p class="text-slate-400 font-medium">Elevando o padrão da costura brasileira através da tecnologia.</p>
            </div>
            <div>
                <h5 class="font-bold mb-6">Produto</h5>
                <ul class="text-slate-500 space-y-4 font-medium">
                    <li><a href="#" class="hover:text-indigo-600">Funcionalidades</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Preços</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Novidades</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-bold mb-6">Suporte</h5>
                <ul class="text-slate-500 space-y-4 font-medium">
                    <li><a href="#" class="hover:text-indigo-600">Centro de Ajuda</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Documentação</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Contato</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-bold mb-6">Fique por dentro</h5>
                <div class="flex gap-2">
                    <input type="text" placeholder="Seu e-mail" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 w-full outline-none focus:border-indigo-600">
                    <button class="bg-indigo-600 text-white p-2 rounded-xl shadow-md"><i class="ph ph-paper-plane-right"></i></button>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-6 border-t border-slate-50 pt-8 text-center md:text-left flex flex-col md:flex-row justify-between text-slate-400 text-xs font-bold uppercase tracking-widest">
            <p>&copy; {{ date('Y') }} Confecção PRO. Todos os direitos reservados.</p>
            <div class="flex gap-6 justify-center mt-4 md:mt-0">
                <a href="#">Privacidade</a>
                <a href="#">Termos</a>
            </div>
        </div>
    </footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,   // Duração da animação (1 segundo)
        once: false,      // IMPORTANTE: Faz a animação acontecer sempre
        mirror: true,     // Faz animar ao subir o scroll também
        anchorPlacement: 'top-bottom', // Gatilho dispara assim que o topo do item toca o fundo da tela
        offset: 50        // Pequeno atraso para não animar "escondido"
    });
</script>
</body>
</html>