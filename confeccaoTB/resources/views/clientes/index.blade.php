<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }

        .client-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .client-card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.15); }
        
        .toast-progress { animation: progress 4s linear forwards; }
        @keyframes progress { from { width: 100%; } to { width: 0%; } }
    </style>

    <div class="flex min-h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-50 flex flex-col">
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" class="text-xl font-black tracking-tighter text-slate-900 italic">Maestria<span class="text-indigo-600">Têxtil</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-2">Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="{{ request()->routeIs('dashboard') ? 'ph-fill' : 'ph-bold' }} ph-house text-2xl"></i>
                    <span x-show="sidebarOpen">Painel Geral</span>
                </a>

                <a href="{{ route('pedidos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('pedidos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="{{ request()->routeIs('pedidos.*') ? 'ph-fill' : 'ph-bold' }} ph-clipboard-text text-2xl"></i>
                    <span x-show="sidebarOpen">Pedidos / O.S</span>
                </a>

                <a href="{{ route('produtos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('produtos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="{{ request()->routeIs('produtos.*') ? 'ph-fill' : 'ph-bold' }} ph-coat-hanger text-2xl"></i>
                    <span x-show="sidebarOpen">Meus Produtos</span>
                </a>

                <a href="{{ route('clientes.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('clientes.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="{{ request()->routeIs('clientes.*') ? 'ph-fill' : 'ph-bold' }} ph-users-three text-2xl"></i>
                    <span x-show="sidebarOpen">Clientes</span>
                </a>

                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-6 mb-2">Gestão</p>

                <a href="{{ route('estoque.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('estoque.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="{{ request()->routeIs('estoque.*') ? 'ph-fill' : 'ph-bold' }} ph-package text-2xl"></i>
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
            <div id="notification-toast" data-aos="fade-left" class="fixed top-10 right-10 z-[110] flex items-center gap-4 bg-white/95 backdrop-blur-xl p-6 rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.12)] border border-emerald-100 min-w-[380px]">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100 shrink-0">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-slate-900 font-black italic text-sm tracking-tighter">Ação Concluída</h4>
                    <p class="text-slate-500 text-xs font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-600 transition-colors">
                    <i class="ph ph-x-circle text-xl"></i>
                </button>
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 toast-progress"></div>
                </div>
            </div>
            @endif

            <header class="h-32 flex items-center justify-between px-10 glass-header sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Relacionamento</h2>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Listagem de Clientes</p>
                </div>
                
                <div class="flex items-center gap-6" data-aos="fade-left">
                    <form action="{{ route('clientes.index') }}" method="GET" class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou documento..." 
                               class="pl-12 pr-6 py-3.5 bg-slate-100 border-none rounded-[1.5rem] text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 w-80 transition-all">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                    </form>

                    <a href="{{ route('clientes.create') }}" class="px-8 py-4 gradient-bg text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                        <i class="ph-bold ph-user-plus text-lg"></i>
                        Novo Registro
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse ($clientes as $cliente)
                    <div class="client-card bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group" data-aos="fade-up">
                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-50 rounded-full opacity-40 group-hover:scale-150 transition-transform duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div class="w-20 h-20 bg-white p-1 rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($cliente->nome) }}&background=6366f1&color=fff&bold=true&size=128" class="w-full h-full rounded-[1.8rem] object-cover">
                                </div>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="w-11 h-11 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <i class="ph ph-pencil-simple text-xl"></i>
                                    </a>
                                    <button onclick="openDeleteModal('{{ $cliente->id }}', '{{ $cliente->nome }}')" class="w-11 h-11 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        <i class="ph ph-trash text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-1 truncate">{{ $cliente->nome }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Parceiro Ativo</span>
                                </div>
                            </div>

                            <div class="space-y-4 border-t border-slate-50 pt-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-indigo-600">
                                        <i class="ph ph-fingerprint text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">Documento</p>
                                        <p class="text-sm font-bold text-slate-700">{{ $cliente->cpf }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-indigo-600">
                                        <i class="ph ph-phone text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">Contato</p>
                                        <p class="text-sm font-bold text-slate-700 truncate">{{ $cliente->telefone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                        <i class="ph ph-users-three text-7xl text-slate-200 mb-6"></i>
                        <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter">Nenhum registro encontrado</h3>
                        <p class="text-slate-400 font-medium">Cadastre seu primeiro cliente para começar.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $clientes->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden" role="dialog">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl transition-all border border-slate-100">
                <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6">
                    <i class="ph-fill ph-warning-octagon text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 text-center mb-2 tracking-tighter italic">Confirmar Remoção?</h3>
                <p class="text-slate-500 text-center text-sm mb-10 font-medium leading-relaxed">Você está prestes a excluir <span id="clientNameSpan" class="font-bold text-slate-900 underline decoration-rose-200"></span>.</p>
                
                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition">Não</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-bold text-xs uppercase shadow-lg shadow-rose-100 transition">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        function openDeleteModal(id, name) {
            document.getElementById('deleteForm').action = `/clientes/${id}`;
            document.getElementById('clientNameSpan').innerText = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Auto-remove toast
        setTimeout(() => {
            const toast = document.getElementById('notification-toast');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100px)';
                toast.style.transition = 'all 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000);
    </script>
</x-app-layout>