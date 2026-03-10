<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .bg-order-dark { background: #1e1b4b; } /* Indigo profundo */
        .gradient-order { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.2);
        }

        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1.5rem center; background-size: 1.2em; }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.15)]" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-order-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 gradient-order rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/40">
                        <i class="ph-fill ph-receipt text-2xl"></i>
                    </div>
                    <h1 class="text-4xl font-black italic tracking-tighter leading-none mb-4">
                        ORDEM DE <br> <span class="text-indigo-300">SERVIÇO</span>
                    </h1>
                    <p class="text-slate-400 font-medium leading-relaxed text-sm">
                        Registre novas demandas e vincule-as diretamente à sua base de clientes.
                    </p>
                </div>

                <div class="relative z-10 space-y-4">
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-3">
                        <i class="ph ph-info text-indigo-400 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-300">O valor será processado em BRL</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-8 md:p-16 relative">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 italic tracking-tight">Novo Pedido</h2>
                        <div class="h-1.5 w-12 gradient-order rounded-full mt-2"></div>
                    </div>
                    <a href="{{ route('pedidos.index') }}" class="group flex items-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-indigo-600 transition-all">
                        <i class="ph-bold ph-caret-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('pedidos.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500 transition-colors">Vincular Cliente</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-user-list text-slate-400 text-2xl"></i>
                                <select name="cliente_id" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                                    <option value="" disabled selected>Selecione um cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Descrição do Produto/Peça</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-package text-slate-400 text-2xl"></i>
                                <input type="text" name="produto" required value="{{ old('produto') }}"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Ex: Camiseta Algodão Egípcio - Tamanho G">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Volume</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-stack text-slate-400 text-2xl"></i>
                                <input type="number" name="quantidade" required min="1" value="{{ old('quantidade', 1) }}"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Valor Unitário (R$)</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-currency-circle-dollar text-slate-400 text-2xl"></i>
                                <input type="text" name="valor" id="money-mask" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold"
                                    placeholder="0,00">
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-order text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-[0_20px_40px_-10px_rgba(79,70,229,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-check-square-offset text-2xl"></i>
                            Finalizar e Abrir O.S
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('money-mask');
        input.addEventListener('input', (e) => {
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