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

                <a href="{{ route('estoque.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-indigo-50 text-indigo-600 font-bold shadow-sm transition-all group">
                    <i class="ph-fill ph-package text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Estoque / Insumos</span>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-truck text-2xl"></i>
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
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Gestão de Estoque</h2>
                </div>
                
                <div class="flex items-center gap-4" data-aos="fade-left">
                    <a href="{{ route('estoque.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <i class="ph ph-arrows-down-up text-xl"></i>
                        Nova Movimentação
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10" data-aos="fade-up">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Movimentações</p>
                        <h3 class="text-2xl font-black text-slate-900 italic">{{ $movimentacoes->total() }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-8 border-b border-slate-50">
                        <h3 class="text-lg font-bold text-slate-900 italic">Histórico Recente</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-50">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Produto</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Tipo</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Quantidade</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Motivo</th>
                                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Data / Hora</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($movimentacoes as $mov)
                                <tr class="hover:bg-indigo-50/30 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-white transition-colors">
                                                <i class="ph-bold ph-package"></i>
                                            </div>
                                            <span class="text-sm font-bold text-slate-900">{{ $mov->produto->nome }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 text-[10px] font-black rounded-lg uppercase tracking-widest {{ $mov->tipo == 'Entrada' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                            @if($mov->tipo == 'Entrada')
                                                <i class="ph ph-trend-up mr-1"></i>
                                            @else
                                                <i class="ph ph-trend-down mr-1"></i>
                                            @endif
                                            {{ $mov->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-black text-slate-900 font-mono">{{ $mov->quantidade }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs text-slate-500 font-medium">{{ $mov->motivo }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-bold text-slate-400 tracking-tight">
                                            {{ $mov->created_at->format('d/m/Y') }}
                                            <span class="block text-[10px] font-medium opacity-60">{{ $mov->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
        AOS.init({ duration: 800, once: false });
    </script>
</x-app-layout>