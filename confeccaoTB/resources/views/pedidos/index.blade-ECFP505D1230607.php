<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; overflow-x: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .glass-header { background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid #E2E8F0; }
        
        /* Scrollbar Invisível */
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }

        /* Card Elegante */
        .order-card { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #FFFFFF;
            border: 1px solid #F1F5F9;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .order-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.12);
            border-color: #E2E8F0;
        }
        
        .sidebar-link { transition: all 0.2s ease; color: #64748B; }
        .sidebar-link:hover { background: #F8FAFC; color: #4F46E5; transform: translateX(4px); }
        .sidebar-active { background: #EEF2FF; color: #4F46E5; border-right: 3px solid #4F46E5; }

        .toast-progress { animation: progress 4s linear forwards; }
        @keyframes progress { from { width: 100%; } to { width: 0%; } }
    </style>

    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" 
               class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-[60] flex flex-col">
            
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">
                    Maestria<span class="text-indigo-600">Têxtil</span>
                </span>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-4 mt-2">Menu Principal</p>
                
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl">
                    <i class="ph-bold ph-house text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Painel Geral</span>
                </a>

                <a href="{{ route('pedidos.index') }}" class="sidebar-active flex items-center gap-4 p-4 rounded-l-2xl">
                    <i class="ph-fill ph-clipboard-text text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Pedidos / O.S</span>
                </a>

                <a href="{{ route('produtos.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl">
                    <i class="ph-bold ph-coat-hanger text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Produtos</span>
                </a>

                <a href="{{ route('clientes.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl">
                    <i class="ph-bold ph-users-three text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Clientes</span>
                </a>

                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-8 mb-4">Suprimentos</p>

                <a href="{{ route('estoque.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl">
                    <i class="ph-bold ph-package text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Estoque</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl">
                    <i class="ph-bold ph-truck text-2xl"></i>
                    <span x-show="sidebarOpen" class="text-xs uppercase tracking-widest font-extrabold">Fornecedores</span>
                </a>
            </nav>

            <div class="p-6 border-t border-slate-50">
                <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-50 text-slate-400 hover:text-indigo-600 transition-all hover:bg-indigo-50">
                    <i class="ph-bold text-xl" :class="sidebarOpen ? 'ph-caret-double-left' : 'ph-caret-double-right'"></i>
                </button>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'pl-72' : 'pl-24'" class="flex-1 transition-all duration-300 min-h-screen">
            
            @if(session('success'))
            <div id="toast-notif" class="fixed top-6 right-6 z-[100] flex items-center gap-4 bg-white/90 backdrop-blur-xl p-5 rounded-3xl shadow-2xl border border-emerald-100 min-w-[320px]" data-aos="fade-left">
                <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-200">
                    <i class="ph-bold ph-check text-xl"></i>
                </div>
                <div class="flex-1 pr-4">
                    <h4 class="text-slate-900 font-bold text-xs uppercase tracking-tighter">Sucesso!</h4>
                    <p class="text-slate-500 text-[11px] font-medium leading-tight">{{ session('success') }}</p>
                </div>
                <div class="absolute bottom-0 left-6 right-6 h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 toast-progress"></div>
                </div>
            </div>
            @endif

            <header class="glass-header sticky top-0 z-40 px-8 py-6">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div data-aos="fade-down">
                        <h2 class="text-2xl font-black text-slate-900 italic uppercase tracking-tighter">Painel de <span class="text-indigo-600">Pedidos</span></h2>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $pedidos->total() }} registros ativos</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3" data-aos="fade-left">
                        <a href="{{ route('pedidos.create') }}" class="gradient-bg text-white px-6 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] shadow-lg shadow-indigo-200 hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle text-lg"></i> Criar Pedido
                        </a>
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse ($pedidos as $pedido)
                    <div class="order-card rounded-[2.5rem] p-7 group" data-aos="fade-up">
                        
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pedido->cliente->nome) }}&background=F1F5F9&color=4F46E5&bold=true" class="w-11 h-11 rounded-2xl">
                                    <div class="overflow-hidden">
                                        <h4 class="text-xs font-black text-slate-900 uppercase truncate pr-2">{{ $pedido->cliente->nome }}</h4>
                                        <span class="text-[10px] font-bold text-slate-400 italic">Data: {{ $pedido->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] font-black italic">#{{ $pedido->id }}</div>
                            </div>

                            <div class="mb-5">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Produto Solicitado</p>
                                <h3 class="text-base font-extrabold text-slate-800 leading-snug h-12 line-clamp-2">
                                    {{ $pedido->produto }}
                                </h3>
                            </div>

                            <div class="bg-slate-50/80 rounded-3xl p-4 mb-6 flex items-center justify-between border border-slate-100">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Total</p>
                                    <p class="text-lg font-black text-indigo-600 italic">R$ {{ number_format($pedido->valor * $pedido->quantidade, 2, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Qtd</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $pedido->quantidade }} un.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <a href="{{ route('pedidos.edit', $pedido->id) }}" class="flex-1 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest py-3.5 rounded-2xl text-center hover:bg-indigo-600 transition-colors">
                                Editar
                            </a>
                            <button type="button" onclick="openDeleteModal('{{ $pedido->id }}', '{{ $pedido->produto }}')" 
                                    class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                <i class="ph-bold ph-trash text-xl"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-white rounded-[3rem] p-12 border-2 border-dashed border-slate-200">
                            <i class="ph ph-mask-sad text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhum pedido registrado no sistema.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-10">
                    {{ $pedidos->links() }}
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="absolute inset-0 flex items-center justify-center p-6">
            <div class="bg-white rounded-[3rem] p-10 max-w-sm w-full shadow-2xl border border-slate-100 text-center" data-aos="zoom-in">
                <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                    <i class="ph-fill ph-warning-octagon text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter">Apagar Registro?</h3>
                <p class="text-slate-500 text-xs mb-8 font-medium leading-relaxed px-4">
                    Deseja mesmo excluir o pedido de <br><span id="itemNameSpan" class="text-rose-500 font-black italic"></span>? 
                    Esta ação é irreversível.
                </p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition">Não</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true });
        
        function openDeleteModal(id, name) {
            const form = document.getElementById('deleteForm');
            form.action = '/pedidos/' + id; // Injeção via URL limpa
            document.getElementById('itemNameSpan').innerText = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        setTimeout(() => {
            const toast = document.getElementById('toast-notif');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                toast.style.transition = 'all 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000);
    </script>
</x-app-layout>