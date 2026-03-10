<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f5; }
        .bg-ultra-dark { background: #0F172A; }
        .gradient-stock { background: linear-gradient(135deg, #3B82F6 0%, #2DD4BF 100%); }
        .text-gradient { background: linear-gradient(135deg, #3B82F6 0%, #2DD4BF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.2);
        }

        /* Estilização do Select nativo para combinar */
        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5em; }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.2)]" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-ultra-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 gradient-stock rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-500/40">
                        <i class="ph-fill ph-package text-2xl"></i>
                    </div>
                    <h1 class="text-4xl font-black italic tracking-tighter leading-none mb-4">
                        FLUXO DE <br> <span class="text-cyan-400">INSUMOS</span>
                    </h1>
                    <p class="text-slate-400 font-medium leading-relaxed">
                        Controle cada agulha, tecido e aviamento com precisão cirúrgica.
                    </p>
                </div>

                <div class="relative z-10 p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-cyan-500 mb-2">Dica de Gestão</p>
                    <p class="text-xs text-slate-300 leading-relaxed italic">"Um estoque bem organizado é o coração de uma confecção lucrativa."</p>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-8 md:p-16 relative">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 italic tracking-tight">Movimentação</h2>
                        <div class="h-1.5 w-12 gradient-stock rounded-full mt-2"></div>
                    </div>
                    <a href="{{ route('estoque.index') }}" class="group flex items-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-blue-600 transition-all">
                        <i class="ph-bold ph-caret-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('estoque.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Produto / Insumo</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-magnifying-glass text-slate-400 text-2xl"></i>
                                <select name="produto_id" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                                    <option value="" disabled selected>Selecione o item...</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Operação</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="tipo" value="Entrada" class="peer hidden" checked>
                                    <div class="py-4 text-center rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-widest peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all">
                                        <i class="ph-bold ph-arrow-down-left mr-1"></i> Entrada
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="tipo" value="Saída" class="peer hidden">
                                    <div class="py-4 text-center rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-widest peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 transition-all">
                                        <i class="ph-bold ph-arrow-up-right mr-1"></i> Saída
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Quantidade</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-hash-straight text-slate-400 text-2xl"></i>
                                <input type="number" name="quantidade" required min="1"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Motivo ou Observação</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-chat-centered-text text-slate-400 text-2xl"></i>
                                <input type="text" name="motivo"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Ex: Compra de fornecedor, Ajuste de inventário...">
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-stock text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-[0_20px_40px_-10px_rgba(59,130,246,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-arrows-left-right text-2xl"></i>
                            Processar Movimentação
                        </button>
                        
                        <button type="reset" class="flex-1 bg-slate-100 text-slate-400 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 hover:text-slate-600 transition-all flex items-center justify-center gap-2 group">
                            <i class="ph ph-trash text-xl group-hover:shake"></i>
                            Limpar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>
</x-app-layout>