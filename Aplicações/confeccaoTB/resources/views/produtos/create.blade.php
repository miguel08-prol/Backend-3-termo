<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .bg-product-dark { background: #0f172a; }
        .gradient-product { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.2);
        }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.1)]" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-product-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 gradient-product rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/40">
                        <i class="ph-fill ph-coat-hanger text-2xl"></i>
                    </div>
                    <h1 class="text-4xl font-black italic tracking-tighter leading-none mb-4">
                        DESIGN & <br> <span class="text-indigo-400">CATÁLOGO</span>
                    </h1>
                    <p class="text-slate-400 font-medium leading-relaxed text-sm">
                        Cadastre suas criações e defina o valor de mercado para o seu mostruário.
                    </p>
                </div>

                <div class="relative z-10 p-5 bg-white/5 border border-white/10 rounded-3xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-2">Sugestão</p>
                    <p class="text-xs text-slate-300 leading-relaxed italic">"O nome do produto deve ser claro para facilitar a busca nos pedidos."</p>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-8 md:p-16 relative">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 italic tracking-tight">Nova Peça</h2>
                        <div class="h-1.5 w-12 gradient-product rounded-full mt-2"></div>
                    </div>
                    <a href="{{ route('produtos.index') }}" class="group flex items-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-indigo-600 transition-all">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        Catálogo
                    </a>
                </div>

                <form action="{{ route('produtos.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500 transition-colors">Identificação da Peça</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-tag text-slate-400 text-2xl"></i>
                                <input type="text" name="nome" value="{{ old('nome') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Ex: Camiseta Oversized Minimalist">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Preço de Venda (R$)</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-currency-dollar text-slate-400 text-2xl"></i>
                                <input type="text" name="preco" id="preco-mask" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold"
                                    placeholder="0,00">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Saldo em Estoque</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-box-archive text-slate-400 text-2xl"></i>
                                <input type="number" name="estoque" value="{{ old('estoque', 0) }}" required min="0"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Detalhes / Composição</label>
                            <div class="input-capsule flex items-start bg-slate-50 rounded-[2rem] border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6 py-2">
                                <i class="ph ph-text-align-left text-slate-400 text-2xl mt-4"></i>
                                <textarea name="descricao" rows="3"
                                    class="w-full border-none bg-transparent py-4 px-4 focus:ring-0 text-slate-700 font-medium placeholder:text-slate-300 resize-none"
                                    placeholder="Descreva o material, cores disponíveis ou coleções..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-product text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-[0_20px_40px_-10px_rgba(79,70,229,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-plus-circle text-2xl"></i>
                            Adicionar ao Catálogo
                        </button>
                        
                        <button type="reset" class="flex-1 bg-slate-100 text-slate-400 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Limpar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const precoInput = document.getElementById('preco-mask');
        precoInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, "");
            value = (value / 100).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
            e.target.value = value;
        });
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>
</x-app-layout>