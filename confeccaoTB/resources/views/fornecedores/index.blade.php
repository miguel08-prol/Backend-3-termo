<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        ::-webkit-scrollbar {
    display: none;
}

/* Esconde a barra de rolagem no IE, Edge e Firefox */
html {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
    </style>

    <div class="flex min-h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" 
               class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-50 flex flex-col">
            
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">Confecção<span class="text-indigo-600">PRO</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-3">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-4">Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-house text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Painel Geral</span>
                </a>

                <a href="{{ route('pedidos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-clipboard-text text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Pedidos / O.S</span>
                </a>

                <a href="{{ route('produtos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-coat-hanger text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Meus Produtos</span>
                </a>

                <a href="{{ route('clientes.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-users-three text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Clientes</span>
                </a>

                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-8 mb-4">Gestão de Insumos</p>

                <a href="{{ route('estoque.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-package text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Estoque / Insumos</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-indigo-50 text-indigo-600 font-bold shadow-sm transition-all group">
                    <i class="ph-fill ph-truck text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Fornecedores</span>
                </a>
            </nav>

            <div class="p-6 mt-auto border-t border-slate-50">
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-50 text-slate-400 hover:text-indigo-600 transition-all">
                    <i class="ph-bold text-xl" :class="sidebarOpen ? 'ph-caret-double-left' : 'ph-caret-double-right'"></i>
                </button>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-72' : 'ml-24'" class="flex-1 transition-all duration-300">
            
            <header class="h-24 flex items-center justify-between px-10 bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Fornecedores</h2>
                </div>
                
                <div class="flex items-center gap-4" data-aos="fade-left">
                    <a href="{{ route('fornecedores.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <i class="ph ph-truck text-xl"></i>
                        Novo Fornecedor
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 italic">Parceiros de Insumos</h3>
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest italic">Total: {{ $fornecedores->count() }} empresas</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-50">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Empresa</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Documento</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Contato</th>
                                    <th class="px-8 py-5 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($fornecedores as $fornecedor)
                                <tr class="hover:bg-indigo-50/30 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-white transition-all">
                                                <i class="ph-fill ph-buildings text-2xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">{{ $fornecedor->nome_fantasia }}</div>
                                                <div class="text-[10px] text-slate-400 font-black uppercase tracking-tight line-clamp-1">{{ $fornecedor->razao_social }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-medium text-slate-600">
                                        {{ $fornecedor->cnpj }}
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-slate-900">{{ $fornecedor->email }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $fornecedor->telefone }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
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
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl transition-all border border-slate-100">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6">
                    <i class="ph-fill ph-truck text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 text-center mb-2 tracking-tighter italic">Remover Fornecedor?</h3>
                <p class="text-slate-500 text-center text-sm mb-10 font-medium leading-relaxed">Deseja realmente remover <span id="itemNameSpan" class="font-bold text-slate-900 underline decoration-red-200"></span> do sistema?</p>
                
                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition">Voltar</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-red-600 shadow-lg shadow-red-100 transition">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: false });
        
        function openDeleteModal(id, name) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').action = `/fornecedores/${id}`;
            document.getElementById('itemNameSpan').innerText = name;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>