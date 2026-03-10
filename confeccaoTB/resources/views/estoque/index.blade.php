<x-app-layout>
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg?v={{ time() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        
        /* Scrollbar invisível */
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }

        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.1); }
        
        .toast-progress { animation: progress 4s linear forwards; }
        @keyframes progress { from { width: 100%; } to { width: 0%; } }
    </style>

    <div class="flex min-h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-50 flex flex-col">
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">Maestria<span class="text-indigo-600">Têxtil</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-2">Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-bold ph-house text-2xl"></i>
                    <span x-show="sidebarOpen">Painel Geral</span>
                </a>

                <a href="{{ route('pedidos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('pedidos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-bold ph-clipboard-text text-2xl"></i>
                    <span x-show="sidebarOpen">Pedidos / O.S</span>
                </a>

                <a href="{{ route('produtos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('produtos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-bold ph-coat-hanger text-2xl"></i>
                    <span x-show="sidebarOpen">Meus Produtos</span>
                </a>

                <a href="{{ route('clientes.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('clientes.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-bold ph-users-three text-2xl"></i>
                    <span x-show="sidebarOpen">Clientes</span>
                </a>

                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-6 mb-2">Gestão</p>

                <a href="{{ route('estoque.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('estoque.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-fill ph-package text-2xl"></i>
                    <span x-show="sidebarOpen">Estoque / Insumos</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('fornecedores.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-bold ph-truck text-2xl"></i>
                    <span x-show="sidebarOpen">Fornecedores</span>
                </a>
            </nav>

            <div class="p-6 mt-auto border-t border-slate-50">
                <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-50 text-slate-400 hover:text-indigo-600 transition-all">
                    <i class="ph-bold text-xl" :class="sidebarOpen ? 'ph-caret-double-left' : 'ph-caret-double-right'"></i>
                </button>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-72' : 'ml-24'" class="flex-1 transition-all duration-300">
            
            @if(session('success'))
            <div id="toast-notif" data-aos="fade-left" class="fixed top-10 right-10 z-[110] flex items-center gap-4 bg-white/95 backdrop-blur-xl p-6 rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.1)] border border-emerald-100 min-w-[380px]">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-slate-900 font-black italic text-sm">Movimentação Concluída</h4>
                    <p class="text-slate-500 text-xs font-medium">{{ session('success') }}</p>
                </div>
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 toast-progress"></div>
                </div>
            </div>
            @endif

            <header class="h-32 flex items-center justify-between px-10 glass-header sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Controle de Insumos</h2>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Gestão de Estoque</p>
                </div>
                
                <div class="flex items-center gap-4" data-aos="fade-left">
                    <a href="{{ route('estoque.create') }}" class="px-8 py-4 gradient-bg text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                        <i class="ph-bold ph-arrows-down-up text-lg"></i>
                        Nova Movimentação
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10" data-aos="fade-up">
                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Total de Registros</p>
                        <h3 class="text-3xl font-black text-slate-900 italic relative z-10">{{ $movimentacoes->total() }}</h3>
                    </div>

                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Entradas Recentes</p>
                        <h3 class="text-3xl font-black text-emerald-600 italic relative z-10">
                            {{ $movimentacoes->where('tipo', 'Entrada')->count() }}
                        </h3>
                    </div>

                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Saídas Recentes</p>
                        <h3 class="text-3xl font-black text-rose-600 italic relative z-10">
                            {{ $movimentacoes->where('tipo', 'Saída')->count() }}
                        </h3>
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 italic">Histórico de Movimentação</h3>
                        <span class="px-4 py-1.5 bg-slate-50 text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest">Atualizado agora</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-50">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Item / Insumo</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Fluxo</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Qtd</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($movimentacoes as $mov)
                                <tr class="hover:bg-indigo-50/20 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                <i class="ph-bold ph-package text-lg"></i>
                                            </div>
                                            <span class="text-sm font-bold text-slate-900">{{ $mov->produto->nome ?? 'Produto Indisponível' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $mov->tipo == 'Entrada' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                            <i class="{{ $mov->tipo == 'Entrada' ? 'ph-bold ph-arrow-circle-up-right' : 'ph-bold ph-arrow-circle-down-right' }} mr-1"></i>
                                            {{ $mov->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 font-mono italic">{{ $mov->quantidade ?? 0 }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase">Unidades</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs text-slate-500 font-medium italic opacity-70 group-hover:opacity-100 transition-opacity">{{ $mov->motivo ?? 'Nenhuma observação registrada' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <i class="ph ph-intersect-square text-6xl text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-bold italic">Nenhuma movimentação encontrada.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                        {{ $movimentacoes->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        // Auto-fechar toast
        setTimeout(() => {
            const toast = document.getElementById('toast-notif');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(50px)';
                toast.style.transition = 'all 0.6s ease';
                setTimeout(() => toast.remove(), 600);
            }
        }, 4000);
    </script>
</x-app-layout>