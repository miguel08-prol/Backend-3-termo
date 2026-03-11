<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .bg-inventory-dark { background: #0F172A; }
        .gradient-inventory { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        
        /* Efeito de elevação nos inputs */
        .input-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #F1F5F9;
            border: 2px solid transparent;
        }
        .input-wrapper:focus-within {
            background: white;
            border-color: #4F46E5;
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.15);
        }

        /* Seletor de Entrada/Saída estilo Apple */
        .type-radio:checked + .type-btn-entrada { background: #10B981; color: white; box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4); }
        .type-radio:checked + .type-btn-saida { background: #EF4444; color: white; box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4); }
        
        ::-webkit-scrollbar { display: none; }
    </style>

    <div class="min-h-screen py-10 flex items-center justify-center p-4">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] bg-white shadow-[0_50px_100px_-20px_rgba(15,23,42,0.12)] border border-white" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-inventory-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <a href="{{ route('estoque.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-white transition-all group mb-12">
                        <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-indigo-500 transition-all">
                            <i class="ph-bold ph-arrow-left"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Painel de Estoque</span>
                    </a>

                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[1.5rem] flex items-center justify-center mb-6 shadow-xl">
                        <i class="ph-fill ph-pencil-line text-3xl"></i>
                    </div>
                    <h2 class="text-4xl font-black italic tracking-tighter mb-4 uppercase leading-none">
                        Refinar<br><span class="text-indigo-400">Registro</span>
                    </h2>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed opacity-80">
                        Edite os valores de movimentação com precisão. O sistema recalcula o saldo total instantaneamente após a gravação.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="p-6 bg-white/5 rounded-[2.5rem] border border-white/10 backdrop-blur-xl">
                        <p class="text-[10px] font-black uppercase text-indigo-400 tracking-[0.2em] mb-2">Referência Interna</p>
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-black italic">#{{ $movimentacao->id }}</span>
                            <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-[10px] font-bold uppercase tracking-widest">Ativo</span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl"></div>
            </div>

            <div class="lg:col-span-8 p-12 md:p-20 bg-white relative">
                
                <form action="{{ route('estoque.update', $movimentacao->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-10">
                        
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Item em Movimentação</label>
                            <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                <i class="ph-fill ph-cube text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                <select name="produto_id" class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold text-lg">
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}" {{ $movimentacao->produto_id == $produto->id ? 'selected' : '' }}>
                                            {{ $produto->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Quantidade</label>
                                <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                    <i class="ph-bold ph-hash text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                    <input type="number" name="quantidade" value="{{ $movimentacao->quantidade }}" 
                                           class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-900 font-black italic text-2xl">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Tipo de Fluxo</label>
                                <div class="flex bg-slate-100 p-2 rounded-[2rem] h-[78px]">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="tipo" value="Entrada" class="hidden type-radio" {{ $movimentacao->tipo == 'Entrada' ? 'checked' : '' }}>
                                        <div class="type-btn-entrada h-full flex items-center justify-center rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all">
                                            Entrada
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="tipo" value="Saída" class="hidden type-radio" {{ $movimentacao->tipo == 'Saída' ? 'checked' : '' }}>
                                        <div class="type-btn-saida h-full flex items-center justify-center rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all">
                                            Saída
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Motivo da Alteração</label>
                            <div class="input-wrapper rounded-[2.5rem] flex items-start px-8 py-2">
                                <i class="ph-bold ph-note-pencil text-2xl text-slate-300 mt-5 group-focus-within:text-indigo-500 transition-colors"></i>
                                <textarea name="motivo" rows="3" class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-600 font-bold italic resize-none" placeholder="Descreva brevemente o motivo...">{{ $movimentacao->motivo }}</textarea>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full gradient-inventory text-white py-7 rounded-[2.5rem] font-black text-xs uppercase tracking-[0.3em] shadow-[0_20px_40px_-10px_rgba(79,70,229,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-4">
                                <i class="ph-fill ph-check-circle text-2xl"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</x-app-layout>