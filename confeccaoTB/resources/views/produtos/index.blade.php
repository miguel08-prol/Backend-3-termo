<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; overflow-x: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .glass-header { background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid #E2E8F0; }
        
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }

        .product-card { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #FFFFFF;
            border: 1px solid #F1F5F9;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.12);
        }

        /* Animação da Barra de Progresso do Toast */
        @keyframes progress { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: progress 4s linear forwards; }
    </style>

    @if(session('success'))
    <div id="toast-success" class="fixed top-6 right-6 z-[200] flex items-center w-full max-w-xs p-4 text-slate-500 bg-white rounded-[1.5rem] shadow-2xl border border-slate-100" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-500 bg-emerald-50 rounded-xl">
            <i class="ph-fill ph-check-circle text-2xl"></i>
        </div>
        <div class="ml-3 text-xs font-black uppercase tracking-wider">{{ session('success') }}</div>
        <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 rounded-full toast-progress"></div>
    </div>
    <script>setTimeout(() => { document.getElementById('toast-success').remove(); }, 4000);</script>
    @endif

    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-[60] flex flex-col">
<div class="p-8 flex items-center gap-4">
    <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shadow-indigo-200 shrink-0 transition-transform hover:rotate-12 cursor-pointer">
        <i class="ph-fill ph-sketch-logo text-2xl"></i>
    </div>

    <span x-show="sidebarOpen" 
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 -translate-x-2"
          x-transition:enter-end="opacity-100 translate-x-0"
          class="text-xl font-black tracking-tighter text-slate-900 italic uppercase">
        Maestria<span class="text-indigo-600">Têxtil</span>
    </span>
</div>

           <nav class="flex-1 px-4 space-y-3">
    <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-4">Principal</p>
    
    <a href="{{ route('dashboard') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('dashboard') ? 'ph-fill' : 'ph-bold' }} ph-house text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Painel Geral</span>
    </a>

    <a href="{{ route('pedidos.index') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('pedidos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('pedidos.*') ? 'ph-fill' : 'ph-bold' }} ph-clipboard-text text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Pedidos / O.S</span>
    </a>

    <a href="{{ route('produtos.index') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('produtos.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('produtos.*') ? 'ph-fill' : 'ph-bold' }} ph-coat-hanger text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Meus Produtos</span>
    </a>

    <a href="{{ route('clientes.index') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('clientes.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('clientes.*') ? 'ph-fill' : 'ph-bold' }} ph-users-three text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Clientes</span>
    </a>

    <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-8 mb-4">Gestão</p>

    <a href="{{ route('estoque.index') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('estoque.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('estoque.*') ? 'ph-fill' : 'ph-bold' }} ph-package text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Estoque / Insumos</span>
    </a>

    <a href="{{ route('fornecedores.index') }}" 
       class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ request()->routeIs('fornecedores.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
        <i class="{{ request()->routeIs('fornecedores.*') ? 'ph-fill' : 'ph-bold' }} ph-truck text-2xl"></i>
        <span x-show="sidebarOpen" x-transition.opacity>Fornecedores</span>
    </a>
</nav>
        </aside>

        <main :class="sidebarOpen ? 'pl-72' : 'pl-24'" class="flex-1 transition-all duration-300 min-h-screen">
            
            <header class="glass-header sticky top-0 z-40 px-8 py-6">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div data-aos="fade-down">
                        <h2 class="text-2xl font-black text-slate-900 italic uppercase tracking-tighter">Catálogo de <span class="text-indigo-600">Peças</span></h2>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $produtos->total() }} Produtos no sistema</p>
                        </div>
                    </div>

                    <div data-aos="fade-left">
                        <a href="{{ route('produtos.create') }}" class="gradient-bg text-white px-6 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] shadow-lg shadow-indigo-100 hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle text-lg"></i> Adicionar Peça
                        </a>
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($produtos as $produto)
                    <div class="product-card rounded-[2.5rem] p-7 flex flex-col justify-between" data-aos="fade-up">
                        
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-indigo-600">
                                    <i class="ph-fill ph-coat-hanger text-3xl"></i>
                                </div>
                                <div class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase italic {{ $produto->estoque > 5 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    Stock: {{ $produto->estoque }}
                                </div>
                            </div>

                            <div class="mb-5">
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight line-clamp-2 leading-tight">
                                    {{ $produto->nome }}
                                </h3>
                                <p class="text-[11px] text-slate-400 font-medium mt-2 line-clamp-1 italic">
                                    {{ $produto->descricao ?? 'Sem descrição detalhada' }}
                                </p>
                            </div>

                            <div class="bg-slate-50/80 rounded-3xl p-5 mb-6 flex items-center justify-between border border-slate-100">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5 tracking-widest">Valor Unitário</p>
                                    <p class="text-xl font-black text-indigo-600 italic">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                                </div>
                                <i class="ph-bold ph-tag text-2xl text-slate-200"></i>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="flex-1 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest py-4 rounded-2xl text-center hover:bg-indigo-600 transition-colors">
                                Editar
                            </a>
                            <button onclick="openDeleteModal('{{ $produto->id }}', '{{ $produto->nome }}')" class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                <i class="ph-bold ph-trash text-xl"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhum produto cadastrado.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-10">
                    {{ $produtos->links() }}
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="absolute inset-0 flex items-center justify-center p-6">
            <div class="bg-white rounded-[3rem] p-10 max-w-sm w-full shadow-2xl border border-slate-100" data-aos="zoom-in">
                <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                    <i class="ph-fill ph-warning-octagon text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter text-center tracking-widest">Excluir Peça?</h3>
                <p class="text-slate-500 text-xs mb-8 text-center font-medium leading-relaxed">
                    Você removerá <span id="itemNameSpan" class="text-rose-500 font-black italic"></span> permanentemente.
                </p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition">Voltar</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-rose-100">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true });
        
        function openDeleteModal(id, name) {
            document.getElementById('deleteForm').action = `/produtos/${id}`;
            document.getElementById('itemNameSpan').innerText = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>