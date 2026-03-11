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

        .vendor-card { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #FFFFFF;
            border: 1px solid #F1F5F9;
        }
        .vendor-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 25px 50px -15px rgba(79, 70, 229, 0.12);
        }

        @keyframes progress { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: progress 4s linear forwards; }
    </style>

    @if(session('success'))
    <div id="toast-success" class="fixed top-6 right-6 z-[200] flex items-center w-full max-w-xs p-5 text-slate-600 bg-white rounded-[2rem] shadow-2xl border border-slate-100" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 text-emerald-500 bg-emerald-50 rounded-2xl shadow-sm">
            <i class="ph-fill ph-check-circle text-3xl"></i>
        </div>
        <div class="ml-4 text-[10px] font-black uppercase tracking-wider">{{ session('success') }}</div>
        <div class="absolute bottom-0 left-6 right-6 h-1 bg-emerald-500 rounded-full toast-progress"></div>
    </div>
    <script>setTimeout(() => { document.getElementById('toast-success').remove(); }, 4000);</script>
    @endif

    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-[60] flex flex-col">
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic uppercase tracking-tighter">Maestria<span class="text-indigo-600">Têxtil</span></span>
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
                    <i class="ph-bold ph-package text-2xl"></i>
                    <span x-show="sidebarOpen">Estoque / Insumos</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('fornecedores.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-fill ph-truck text-2xl"></i>
                    <span x-show="sidebarOpen">Fornecedores</span>
                </a>
            </nav>
        </aside>

        <main :class="sidebarOpen ? 'pl-72' : 'pl-24'" class="flex-1 transition-all duration-300 min-h-screen">
            
            <header class="glass-header sticky top-0 z-40 px-8 py-7">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div data-aos="fade-down">
                        <h2 class="text-2xl font-black text-slate-900 italic uppercase tracking-tighter">Rede de <span class="text-indigo-600">Parceiros</span></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $fornecedores->total() }} Empresas cadastradas</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4" data-aos="fade-left">
                        <a href="{{ route('fornecedores.create') }}" class="gradient-bg text-white px-8 py-4.5 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-[1.03] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle text-lg"></i> Novo Fornecedor
                        </a>
                    </div>
                </div>
            </header>

            <div class="p-10 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($fornecedores as $fornecedor)
                    <div class="vendor-card rounded-[3rem] p-8 flex flex-col justify-between" data-aos="fade-up">
                        
                        <div>
                            <div class="flex justify-between items-start mb-8">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-[1.5rem] flex items-center justify-center text-indigo-500 shadow-sm">
                                    <i class="ph-fill ph-buildings text-3xl"></i>
                                </div>
                                <div class="px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-500 italic">
                                    FOR-{{ str_pad($fornecedor->id, 3, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter leading-tight truncate">
                                    {{ $fornecedor->nome_fantasia }}
                                </h3>
                                <p class="text-[10px] text-slate-400 font-bold mt-1.5 line-clamp-1 uppercase tracking-tight italic">
                                    {{ $fornecedor->razao_social }}
                                </p>
                            </div>

                            <div class="space-y-4 mb-10">
                                <div class="flex items-center gap-4 p-4 bg-slate-50/80 rounded-2xl border border-slate-100">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-400 shadow-sm">
                                        <i class="ph-bold ph-identification-card text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Documento</p>
                                        <p class="text-xs font-bold text-slate-700">{{ $fornecedor->cnpj }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-slate-50/80 rounded-2xl border border-slate-100">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 shadow-sm">
                                        <i class="ph-bold ph-phone text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Contato</p>
                                        <p class="text-xs font-bold text-slate-700">{{ $fornecedor->telefone ?? '(00) 0000-0000' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('fornecedores.edit', $fornecedor->id) }}" class="flex-1 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] py-5 rounded-[1.5rem] text-center hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200">
                                Ver Detalhes
                            </a>
                            <button onclick="openDeleteModal('{{ $fornecedor->id }}', '{{ $fornecedor->nome_fantasia }}')" class="w-16 h-16 rounded-[1.5rem] bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                <i class="ph-bold ph-trash text-2xl"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-32 text-center bg-white rounded-[4rem] border-2 border-dashed border-slate-100">
                        <i class="ph ph-truck text-6xl text-slate-200 mb-4"></i>
                        <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Sua rede de fornecedores está vazia.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $fornecedores->links() }}
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
        <div class="absolute inset-0 flex items-center justify-center p-6">
            <div class="bg-white rounded-[3.5rem] p-12 max-w-sm w-full shadow-2xl border border-slate-100 text-center" data-aos="zoom-in">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2.2rem] flex items-center justify-center mb-8 mx-auto shadow-sm">
                    <i class="ph-fill ph-warning-octagon text-5xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2 italic uppercase tracking-tighter">Remover?</h3>
                <p class="text-slate-500 text-[11px] mb-10 font-medium leading-relaxed px-2 uppercase tracking-wide">
                    O fornecedor <br><span id="itemNameSpan" class="text-rose-600 font-black italic"></span><br> será excluído permanentemente.
                </p>
                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition">Cancelar</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-5 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-rose-200 transition">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        function openDeleteModal(id, name) {
            document.getElementById('deleteForm').action = `/fornecedores/${id}`;
            document.getElementById('itemNameSpan').innerText = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>