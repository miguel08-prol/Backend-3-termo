<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confecção TB2 | Gestão Inteligente para Indústria Têxtil</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.svg') }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Animações de entrada */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-hero { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Classes para o efeito de Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Delays */
        .delay-100 { transition-delay: 100ms; animation-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; animation-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; animation-delay: 300ms; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">
    
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] left-[10%] w-[500px] h-[500px] rounded-full bg-indigo-400/20 blur-[100px] mix-blend-multiply"></div>
        <div class="absolute top-[20%] right-[10%] w-[600px] h-[600px] rounded-full bg-violet-400/20 blur-[120px] mix-blend-multiply"></div>
    </div>

    <nav class="flex items-center justify-between p-6 max-w-7xl mx-auto animate-hero">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/30 text-xl">
                TB
            </div>
            <span class="text-2xl font-extrabold tracking-tight text-slate-800">
                Confecção<span class="text-indigo-600">TB2</span>
            </span>
        </div>
        <div>
            <a href="{{ url('/admin/login') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:text-indigo-600 hover:ring-indigo-300 transition-all duration-300">
                Acessar Painel
            </a>
        </div>
    </nav>

    <main class="relative px-6 pt-10 pb-20 lg:px-8 flex flex-col items-center text-center justify-center">
        <div class="mx-auto max-w-4xl">
            <div class="mb-8 flex justify-center animate-hero delay-100 opacity-0">
                <span class="rounded-full px-4 py-1.5 text-sm leading-6 text-indigo-600 font-medium bg-indigo-50 ring-1 ring-inset ring-indigo-200/50">
                    O sistema definitivo para a sua produção têxtil ✨
                </span>
            </div>

            <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl animate-hero delay-200 opacity-0">
                Do fio ao produto final, <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">nós gerenciamos.</span>
            </h1>
            
            <p class="mt-8 text-lg leading-8 text-slate-600 font-medium max-w-2xl mx-auto animate-hero delay-300 opacity-0">
                Abandone as planilhas. Controle estoques de tecidos, aviamentos, pedidos de clientes e ordens de corte em uma plataforma rápida, segura e fácil de usar.
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 animate-hero delay-300 opacity-0">
                <a href="{{ url('/admin/login') }}" class="w-full sm:w-auto group relative inline-flex items-center justify-center rounded-full bg-indigo-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-indigo-500/30 hover:bg-indigo-500 hover:-translate-y-1 transition-all duration-300">
                    Entrar no Sistema
                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="#como-funciona" class="text-base font-semibold leading-6 text-slate-700 hover:text-indigo-600 transition-colors">
                    Ver recursos <span aria-hidden="true">↓</span>
                </a>
            </div>
        </div>

        <div class="mt-20 w-full max-w-5xl animate-hero delay-300 opacity-0 relative">
            <div class="rounded-2xl bg-white/70 backdrop-blur-xl border border-white/80 shadow-2xl shadow-slate-200/50 p-6 relative overflow-hidden ring-1 ring-slate-900/5">
                <div class="flex gap-2 mb-8 border-b border-slate-100 pb-4">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
                        <p class="text-sm font-semibold text-slate-500">Pedidos no Mês</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">142</p>
                        <p class="text-xs text-emerald-600 mt-2 font-medium">↑ 12% vs mês anterior</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100">
                        <p class="text-sm font-semibold text-slate-500">Peças Produzidas</p>
                        <p class="text-3xl font-bold text-slate-800 mt-2">3.450</p>
                        <p class="text-xs text-emerald-600 mt-2 font-medium">Na meta</p>
                    </div>
                    <div class="bg-red-50 p-5 rounded-xl shadow-sm border border-red-100">
                        <p class="text-sm font-semibold text-red-600">Alerta de Insumos</p>
                        <p class="text-3xl font-bold text-red-900 mt-2">4</p>
                        <p class="text-xs text-red-600 mt-2 font-medium">Estoque abaixo do mínimo</p>
                    </div>
                    <div class="bg-indigo-50 p-5 rounded-xl shadow-sm border border-indigo-100">
                        <p class="text-sm font-semibold text-indigo-600">Faturamento Realizado</p>
                        <p class="text-3xl font-bold text-indigo-900 mt-2">R$ 18.450</p>
                        <p class="text-xs text-indigo-600 mt-2 font-medium">Atualizado hoje</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section id="como-funciona" class="py-24 overflow-hidden bg-white/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mb-6 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Estoque de Insumos sob controle.
                    </h2>
                    <p class="mt-6 text-lg leading-8 text-slate-600">
                        Saiba exatamente quantos metros de tecido, rolos de linha e botões você tem disponíveis. Configure alertas para quando um insumo estiver acabando e evite que a sua produção pare por falta de material.
                    </p>
                    <ul class="mt-8 space-y-4 text-slate-700">
                        <li class="flex gap-x-3 items-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Cadastro detalhado com custo e unidade de medida.
                        </li>
                        <li class="flex gap-x-3 items-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Indicadores visuais de estoque baixo, médio e cheio.
                        </li>
                    </ul>
                </div>
                <div class="reveal delay-200 relative">
                    <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Rolos de tecidos e costura" class="rounded-2xl shadow-2xl object-cover h-[450px] w-full ring-1 ring-slate-900/10">
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="bg-red-100 p-2 rounded-full text-red-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Linha Branca Poliéster</p>
                            <p class="text-xs text-slate-500">Apenas 5 cones restantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal order-2 lg:order-1 relative">
                    <img src="https://images.unsplash.com/photo-1556740758-90de374c12ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Pessoa gerenciando pedidos" class="rounded-2xl shadow-2xl object-cover h-[450px] w-full ring-1 ring-slate-900/10">
                </div>
                <div class="reveal delay-200 order-1 lg:order-2">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center mb-6 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Gestão de Pedidos e Clientes.
                    </h2>
                    <p class="mt-6 text-lg leading-8 text-slate-600">
                        Crie orçamentos e pedidos em segundos. Adicione os produtos, calcule o valor total automaticamente e acompanhe o status de cada encomenda (Pendente, Em Produção, Finalizado).
                    </p>
                    <ul class="mt-8 space-y-4 text-slate-700">
                        <li class="flex gap-x-3 items-center">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Base de dados completa de Clientes e Fornecedores.
                        </li>
                        <li class="flex gap-x-3 items-center">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Cálculo automático de subtotais e valores finais.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="relative isolate py-24 px-6 sm:py-32 lg:px-8 mt-10">
        <div class="absolute inset-0 -z-10 overflow-hidden bg-indigo-600 rounded-t-[3rem]">
            <div class="absolute left-[50%] top-[50%] -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 blur-[100px] rounded-full"></div>
        </div>
        <div class="mx-auto max-w-2xl text-center reveal">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Pronto para organizar sua confecção?</h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-indigo-100">
                Junte-se à TB2 Confecção e leve o seu processo produtivo para o próximo nível. Sem complicações, direto ao ponto.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ url('/admin/login') }}" class="rounded-full bg-white px-8 py-3.5 text-base font-semibold text-indigo-600 shadow-sm hover:bg-indigo-50 transition-colors">
                    Acessar meu painel
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 py-12 pb-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-slate-800 pb-8">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold tracking-tight text-white">
                        Confecção<span class="text-indigo-400">TB2</span>
                    </span>
                </div>
                <div class="text-slate-400 text-sm">
                    Suporte técnico: <a href="mailto:contato@tb2confeccao.com.br" class="text-indigo-400 hover:text-indigo-300">contato@tb2confeccao.com.br</a>
                </div>
            </div>
            <div class="mt-8 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} TB2 Sistema de Gestão Têxtil. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');

            const revealOnScroll = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target); // Anima apenas uma vez
                    }
                });
            }, {
                threshold: 0.15 // Dispara quando 15% do elemento estiver visível
            });

            reveals.forEach(reveal => {
                revealOnScroll.observe(reveal);
            });
        });
    </script>
</body>
</html>