<x-app-layout>
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg?v={{ time() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        
        /* Ocultar barra de rolagem */
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }

        .stat-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.1); }
        
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
                    <i class="ph-bold ph-package text-2xl"></i>
                    <span x-show="sidebarOpen">Estoque / Insumos</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ request()->routeIs('fornecedores.*') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="ph-fill ph-truck text-2xl"></i>
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
                    <h4 class="text-slate-900 font-black italic text-sm">Operação Concluída</h4>
                    <p class="text-slate-500 text-xs font-medium">{{ session('success') }}</p>
                </div>
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 toast-progress"></div>
                </div>
            </div>
            @endif

            <header class="h-32 flex items-center justify-between px-10 glass-header sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Parceiros de Insumos</h2>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Gestão de Fornecedores</p>
                </div>
                
                <div class="flex items-center gap-4" data-aos="fade-left">
                    <a href="{{ route('fornecedores.create') }}" class="px-8 py-4 gradient-bg text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                        <i class="ph-bold ph-plus-circle text-lg"></i>
                        Novo Fornecedor
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10" data-aos="fade-up">
                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Total de Empresas</p>
                        <h3 class="text-3xl font-black text-slate-900 italic relative z-10">{{ $fornecedores->count() }}</h3>
                    </div>

                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Ativos no Sistema</p>
                        <h3 class="text-3xl font-black text-emerald-600 italic relative z-10">100%</h3>
                    </div>

                    <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Pendências</p>
                        <h3 class="text-3xl font-black text-slate-900 italic relative z-10">0</h3>
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 italic">Lista de Fornecedores</h3>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Painel em Tempo Real</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-50">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Empresa / Razão</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">CNPJ</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Contato Direto</th>
                                    <th class="px-8 py-5 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Gerenciar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($fornecedores as $fornecedor)
                                <tr class="hover:bg-indigo-50/20 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-white transition-all group-hover:rotate-6">
                                                <i class="ph-fill ph-buildings text-2xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">{{ $fornecedor->nome_fantasia }}</div>
                                                <div class="text-[9px] text-slate-400 font-black uppercase tracking-tight">{{ $fornecedor->razao_social }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-lg">{{ $fornecedor->cnpj }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $fornecedor->email }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $fornecedor->telefone }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                            <a href="{{ route('fornecedores.edit', $fornecedor->id) }}" class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                <i class="ph ph-pencil-simple text-xl"></i>
                                            </a>
                                            <button onclick="openDeleteModal('{{ $fornecedor->id }}', '{{ $fornecedor->nome_fantasia }}')" class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="ph ph-trash text-xl"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden" role="dialog">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-[3rem] p-10 max-w-sm w-full shadow-2xl transition-all border border-slate-100">
                <div class="w-24 h-24 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                    <i class="ph-fill ph-warning-octagon text-5xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 text-center mb-2 tracking-tighter italic">Confirmar Exclusão</h3>
                <p class="text-slate-500 text-center text-sm mb-10 font-medium leading-relaxed px-4">Deseja realmente remover o parceiro <span id="itemNameSpan" class="font-bold text-slate-900 underline decoration-red-200 decoration-2"></span> do sistema?</p>
                
                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition">Cancelar</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-red-600 shadow-lg shadow-red-100 transition">Sim, Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        function openDeleteModal(id, name) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').action = `/fornecedores/${id}`;
            document.getElementById('itemNameSpan').innerText = name;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Auto-fechar notificação toast
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