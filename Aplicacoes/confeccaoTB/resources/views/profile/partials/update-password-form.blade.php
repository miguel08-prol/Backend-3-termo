<section>
    <header class="mb-8">
        <h2 class="text-xl font-black text-slate-900 italic tracking-tight">
            {{ __('Atualizar Palavra-passe') }}
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __('Certifique-se de que a sua conta utiliza uma palavra-passe longa e aleatória para se manter segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Palavra-passe Atual')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4 mb-2" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nova Palavra-passe')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4 mb-2" />
            <x-text-input id="update_password_password" name="password" type="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Nova Palavra-passe')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4 mb-2" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                {{ __('Alterar Senha') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-500 italic">{{ __('Atualizado com sucesso.') }}</p>
            @endif
        </div>
    </form>
</section>