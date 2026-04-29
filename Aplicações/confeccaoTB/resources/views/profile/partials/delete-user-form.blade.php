<section class="space-y-6">
    <header>
        <h2 class="text-xl font-black text-red-600 italic tracking-tight">
            {{ __('Zona Crítica: Eliminar Conta') }}
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __('Assim que a sua conta for eliminada, todos os seus recursos e dados (medidas, clientes, pedidos) serão permanentemente apagados.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-4 bg-red-100 text-red-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all"
    >{{ __('Eliminar Minha Conta') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-white rounded-[2.5rem]">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-4">
                {{ __('Tem a certeza absoluta?') }}
            </h2>

            <p class="text-sm font-medium text-slate-500 mb-8 leading-relaxed">
                {{ __('Esta ação não pode ser desfeita. Por favor, introduza a sua palavra-passe para confirmar que deseja eliminar permanentemente a sua conta de administrador.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Palavra-passe') }}" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-red-500 transition-all font-bold text-slate-900"
                    placeholder="{{ __('Sua Palavra-passe para confirmar') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-100 transition">
                    {{ __('Eliminar Agora') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>