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
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">Maestria<span class="text-indigo-600">Têxtil</span></span>
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
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Painel Geral</h2>
                </div>
                
                <div class="flex items-center gap-6" data-aos="fade-left">
                    <div class="relative">
                        <i class="ph ph-bell text-2xl text-slate-400"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Administrador</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4F46E5&color=fff" class="w-12 h-12 rounded-2xl shadow-sm">
                    </div>
                </div>
            </header>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    @php
                        $metrics = [
                            ['label' => 'Vendas Mensais', 'val' => 'R$ 4.250', 'icon' => 'ph-chart-line-up', 'color' => 'indigo'],
                            ['label' => 'Ordens Ativas', 'val' => '28', 'icon' => 'ph-needle', 'color' => 'amber'],
                            ['label' => 'Novos Clientes', 'val' => '+14', 'icon' => 'ph-user-plus', 'color' => 'emerald'],
                            ['label' => 'Avisos Stock', 'val' => '3', 'icon' => 'ph-warning-circle', 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($metrics as $index => $m)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" 
                         class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-{{ $m['color'] }}-50 text-{{ $m['color'] }}-600 rounded-2xl flex items-center justify-center">
                                <i class="ph-fill {{ $m['icon'] }} text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $m['label'] }}</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight italic">{{ $m['val'] }}</h3>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex justify-between items-center mb-10">
                            <h3 class="text-xl font-black text-slate-900 tracking-tight italic">Status de Produção</h3>
                            <a href="{{ route('pedidos.index') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition-all">Ver Tudo</a>
                        </div>

                        <div class="space-y-6">
                            @foreach(['Vestido Seda Luxo', 'Camisa Oxford Slim', 'Casaco Inverno Lã'] as $item)
                            <div class="flex items-center gap-6 p-4 rounded-3xl hover:bg-slate-50 transition-all group">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shrink-0">
                                    <img src="https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?q=80&w=100&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900">{{ $item }}</h4>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><i class="ph ph-calendar mr-1"></i> 12 Mar</span>
                                        <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">Em Costura</span>
                                    </div>
                                </div>
                                <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden hidden md:block">
                                    <div class="h-full bg-indigo-600 w-2/3"></div>
                                </div>
                                <i class="ph ph-caret-right-bold text-slate-300 group-hover:text-indigo-600 transition-colors"></i>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-8" data-aos="fade-left" data-aos-delay="500">
                        <div class="gradient-bg p-10 rounded-[3rem] text-white shadow-2xl shadow-indigo-200 relative overflow-hidden group">
                            <i class="ph ph-sparkle absolute -right-4 -top-4 text-9xl opacity-10 group-hover:rotate-12 transition-transform duration-700"></i>
                            <h4 class="text-2xl font-black mb-4 tracking-tighter italic">Novo Pedido?</h4>
                            <p class="text-indigo-100 text-sm font-medium mb-8 leading-relaxed">Registe agora uma nova ordem de serviço e comece a produzir imediatamente.</p>
                            <a href="{{ route('pedidos.create') }}" class="block w-full py-4 bg-white text-indigo-600 text-center rounded-2xl font-black text-xs tracking-widest shadow-xl hover:scale-105 transition-all">
                                + CRIAR AGORA
                            </a>
                        </div>

                        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-10">
                            <h4 class="font-black text-slate-900 mb-8 tracking-tight italic uppercase text-xs tracking-widest">Nível de Stock</h4>
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                        <span>Linhas Brancas</span>
                                        <span class="text-red-500 font-bold">12%</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 w-[12%]"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                        <span>Tecido Algodão</span>
                                        <span class="text-indigo-600 font-bold">78%</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600 w-[78%]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: false, mirror: true });
    </script>
</x-app-layout>