<div class="flex min-h-screen">
    <div class="hidden lg:block lg:w-1/2 relative overflow-hidden bg-indigo-600">
        <img src="https://images.unsplash.com/photo-1558603668-6570496b66f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" 
             class="absolute inset-0 h-full w-full object-cover mix-blend-multiply opacity-60" 
             alt="Confecção">
        
        <div class="absolute inset-0 flex items-center justify-center p-12 text-white">
            <div class="max-w-md">
                <h2 class="text-4xl font-bold mb-4">TB2 Confecção</h2>
                <p class="text-lg text-indigo-100">Gerencie sua produção com a melhor tecnologia do mercado têxtil.</p>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-24 bg-white dark:bg-gray-950 relative">
        
        <div class="absolute top-8 right-8">
            <a href="/" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar para o site
            </a>
        </div>

        <div class="mx-auto w-full max-w-md">
            <div class="lg:hidden mb-8">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">TB</div>
            </div>

            <header class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $this->getHeading() }}
                </h1>
                <p class="text-gray-500 mt-2">Insira seus dados para acessar o painel administrativo.</p>
            </header>

            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>
        </div>
    </div>
</div>