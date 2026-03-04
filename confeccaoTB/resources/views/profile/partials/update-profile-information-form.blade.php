<section>
    <header class="mb-8">
        <h2 class="text-xl font-black text-slate-900 italic tracking-tight">
            {{ __('Informações da Conta') }}
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __("Atualize o seu nome e endereço de e-mail.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nome Completo')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4 mb-2" />
            <x-text-input id="name" name="name" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900" :value="old('name', $user->name)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-mail Profissional')" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4 mb-2" />
            <x-text-input id="email" name="email" type="email" class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                {{ __('Guardar Alterações') }}
            </button>
        </div>
    </form>
</section>