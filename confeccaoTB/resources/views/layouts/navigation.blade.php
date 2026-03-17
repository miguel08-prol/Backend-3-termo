<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            <div class="flex items-center">
                <div class="shrink-0 flex items-center bg-indigo-600 p-2.5 rounded-2xl shadow-lg shadow-indigo-200 group cursor-pointer transition-all hover:rotate-12">
                    <a href="{{ route('dashboard') }}">
                        <i class="ph-fill ph-sketch-logo text-white text-2xl"></i>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-slate-100 text-sm font-bold rounded-2xl text-slate-600 bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center mr-3 shadow-md font-black text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <i class="ph ph-caret-down ms-2 text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl flex items-center py-3">
                                <i class="ph ph-user-circle mr-2 text-lg text-indigo-600"></i> {{ __('Meu Perfil') }}
                            </x-dropdown-link>
                            
                            <hr class="my-2 border-slate-100">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="rounded-xl text-rose-500 hover:bg-rose-50 flex items-center py-3"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="ph ph-sign-out mr-2 text-lg"></i> {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-3 rounded-2xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                    <i :class="{'hidden': open, 'block': ! open }" class="ph ph-list text-2xl"></i>
                    <i :class="{'hidden': ! open, 'block': open }" class="ph ph-x text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100 animate-fade-in">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'ph-house'],
                    ['route' => 'pedidos.index', 'label' => 'Pedidos', 'icon' => 'ph-clipboard-text'],
                    ['route' => 'produtos.index', 'label' => 'Produtos', 'icon' => 'ph-coat-hanger'],
                    ['route' => 'clientes.index', 'label' => 'Clientes', 'icon' => 'ph-users-three'],
                    ['route' => 'estoque.index', 'label' => 'Estoque', 'icon' => 'ph-package'],
                    ['route' => 'fornecedores.index', 'label' => 'Fornecedores', 'icon' => 'ph-truck'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-4 rounded-2xl text-base font-bold transition-all {{ request()->routeIs($item['route']) ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500' }}">
                    <i class="{{ $item['icon'] }} mr-3 text-2xl"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="pt-4 pb-6 border-t border-slate-100 bg-slate-50/50 px-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg mr-4">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('profile.edit') }}" class="block w-full text-center py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm">Ver Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-rose-50 text-rose-500 rounded-xl font-bold text-sm">Sair do Sistema</button>
                </form>
            </div>
        </div>
    </div>
</nav>