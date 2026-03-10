<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        
        /* Esconde a barra de rolagem */
        ::-webkit-scrollbar { display: none; }
        html { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="flex min-h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" 
               class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-50 flex flex-col">
            
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">
                    Confecção<span class="text-indigo-600">Têxtil</span>
                </span>
            </div>

            <nav class="flex-1 px-4 space-y-3">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-4">Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                    <i class="ph-bold ph-house text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Painel Geral</span>
                </a>

                <a href="{{ route('pedidos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-indigo-50 text-indigo-600 font-bold shadow-sm transition-all">
                    <i class="ph-fill ph-clipboard-text text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Pedidos / O.S</span>
                </a>

                <a href="{{ route('produtos.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                    <i class="ph-bold ph-coat-hanger text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Meus Produtos</span>
                </a>

                <a href="{{ route('clientes.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                    <i class="ph-bold ph-users-three text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Clientes</span>
                </a>

                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mt-8 mb-4">Gestão</p>

                <a href="{{ route('estoque.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all">
                    <i class="ph-bold ph-package text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Estoque / Insumos</span>
                </a>

                  <a href="{{ route('fornecedores.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-truck text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Fornecedores</span>
                </a>
            </nav>

            <div class="p-6 mt-auto border-t border-slate-50">
                <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-50 text-slate-400 hover:text-indigo-600 transition-all">
                    <i class="ph-bold text-xl" :class="sidebarOpen ? 'ph-caret-double-left' : 'ph-caret-double-right'"></i>
                </button>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-72' : 'ml-24'" class="flex-1 transition-all duration-300">
            
            <header class="h-24 flex items-center justify-between px-10 bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Gestão de Pedidos</h2>
                </div>
                
                <div data-aos="fade-left">
                    <a href="{{ route('pedidos.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <i class="ph ph-plus-circle text-xl"></i>
                        Novo Pedido
                    </a>
                </div>
            </header>

            <div class="p-10">
                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900">Ordens de Serviço Ativas</h3>
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest italic">Total: {{ $pedidos->total() }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-50">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Produto / O.S</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Valor Total</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Data do Pedido</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($pedidos as $pedido)
                                <tr class="hover:bg-indigo-50/30 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pedido->cliente->nome) }}&background=EEF2FF&color=4F46E5" class="w-10 h-10 rounded-xl shadow-sm">
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">{{ $pedido->cliente->nome }}</div>
                                                <div class="text-xs text-slate-400 font-medium">{{ $pedido->cliente->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <span class="text-sm font-bold text-slate-700">{{ $pedido->produto }}</span>
                                        </div>
                                    </td>
<td class="px-8 py-6">
    <div class="text-sm font-black text-slate-900 italic">
        R$ {{ number_format($pedido->valor * $pedido->quantidade, 2, ',', '.') }}
    </div>
    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
        Unitário: R$ {{ number_format($pedido->valor, 2, ',', '.') }} | Qtd: {{ $pedido->quantidade }}
    </div>
</td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-slate-700">
                                            {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-black uppercase tracking-tight">
                                            {{ \Carbon\Carbon::parse($pedido->created_at)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="openDeleteModal('{{ $pedido->id }}', '{{ $pedido->produto }}')" class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="ph ph-trash text-xl"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                        {{ $pedidos->links() }}
                    </div>
                </div>
            </div>
        </main>

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

<style>
    .toast-progress { animation: progress 4s linear forwards; }
    @keyframes progress { from { width: 100%; } to { width: 0%; } }
</style>

<script>
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
@endif
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden" role="dialog">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl border border-slate-100">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="ph-fill ph-warning text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 text-center mb-2 tracking-tight italic">Excluir Pedido?</h3>
                <p class="text-slate-500 text-center text-sm mb-8 font-medium">Você está prestes a remover a ordem de <span id="itemNameSpan" class="font-bold text-slate-900"></span>.</p>
                
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold text-sm hover:bg-slate-100 transition">Cancelar</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-red-500 text-white rounded-2xl font-bold text-sm hover:bg-red-600 shadow-lg shadow-red-100 transition">Confirmar</button>
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
            document.getElementById('deleteForm').action = `/pedidos/${id}`;
            document.getElementById('itemNameSpan').innerText = name;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>