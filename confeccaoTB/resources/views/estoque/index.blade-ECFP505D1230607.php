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

        .inventory-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #F1F5F9; }
        .inventory-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 30px 60px -15px rgba(79, 70, 229, 0.15);
            border-color: #E2E8F0;
        }
        
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
                    <i class="{{ request()->routeIs('fornecedores.*') ? 'ph-fill' : 'ph-bold' }} ph-truck text-2xl"></i>
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
                    <h4 class="text-slate-900 font-black italic text-sm">Sucesso!</h4>
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
                        <i class="ph-bold ph-plus-circle text-lg"></i>
                        Nova Movimentação
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up">
                    @forelse ($movimentacoes as $mov)
                    <div class="inventory-card bg-white rounded-[3rem] p-8 flex flex-col relative overflow-hidden shadow-sm">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 rounded-[1.5rem] flex items-center justify-center {{ $mov->tipo == 'Entrada' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                <i class="ph-fill {{ $mov->tipo == 'Entrada' ? 'ph-trend-up' : 'ph-trend-down' }} text-3xl"></i>
                            </div>
                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm {{ $mov->tipo == 'Entrada' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $mov->tipo }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight italic mb-1">{{ $mov->produto->nome ?? 'Produto Indisponível' }}</h3>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-bold ph-calendar-blank text-indigo-500"></i>
                                {{ $mov->created_at->format('d/m/Y - H:i') }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-3xl p-5 mb-6 flex items-center justify-between border border-slate-100/50">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Volume</p>
                                <p class="text-2xl font-black text-slate-900 italic">{{ $mov->quantidade }} <span class="text-xs text-slate-400 not-italic font-bold">Unid.</span></p>
                            </div>
                            <div class="h-10 w-1px bg-slate-200"></div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</p>
                                <p class="text-xs font-bold text-slate-600 uppercase">Processado</p>
                            </div>
                        </div>

                        <div class="flex-1 mb-8">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-2">Motivo / Obs</p>
                            <p class="text-sm text-slate-500 font-medium italic leading-relaxed">"{{ $mov->motivo ?? 'Nenhuma observação registrada' }}"</p>
                        </div>

                        <div class="flex items-center gap-3 pt-6 border-t border-slate-50">
                            <a href="{{ route('estoque.edit', $mov->id) }}" class="flex-1 bg-indigo-50 text-indigo-600 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center gap-2 group">
                                <i class="ph-bold ph-pencil-simple text-lg transition-transform group-hover:rotate-12"></i>
                                Editar Registro
                            </a>
                            
                            <form action="{{ route('estoque.destroy', $mov->id) }}" method="POST" onsubmit="return confirm('Deseja excluir este registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                                    <i class="ph-bold ph-trash text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-32 flex flex-col items-center justify-center bg-white rounded-[4rem] border-2 border-dashed border-slate-100" data-aos="zoom-in">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                            <i class="ph ph-package text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-400 italic">Nenhuma movimentação no radar</h3>
                        <p class="text-slate-300 text-sm font-bold uppercase tracking-widest mt-2">Comece registrando uma entrada ou saída</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $movimentacoes->links() }}
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
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