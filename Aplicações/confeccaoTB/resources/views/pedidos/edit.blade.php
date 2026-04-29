<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .bg-edit-dark { background: #0f172a; }
        .gradient-edit { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.3);
            border-color: #6366f1;
        }
        ::-webkit-scrollbar { display: none; }
        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1.5rem center; background-size: 1.5rem; }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(15,23,42,0.3)] bg-white" data-aos="fade-up">
            
            <div class="lg:col-span-4 bg-edit-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-8 border border-white/20">
                        <i class="ph-fill ph-pencil-line text-3xl text-indigo-300"></i>
                    </div>
                    <h2 class="text-4xl font-black italic tracking-tighter leading-tight mb-4">Editar<br>Pedido</h2>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Atualize as informações do pedido #{{ $pedido->id }}. Os cálculos serão refeitos automaticamente.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.3em] text-indigo-500">
                        <span class="w-10 h-[2px] bg-indigo-500"></span>
                        Ajuste de Ordem
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 p-12 lg:p-16">
                <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Cliente</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-user text-2xl text-slate-300"></i>
                                <select name="cliente_id" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $pedido->cliente_id == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Produto / Descrição</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-package text-2xl text-slate-300"></i>
                                <input type="text" name="produto" value="{{ $pedido->produto }}" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Quantidade</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-hash text-2xl text-slate-300"></i>
                                <input type="number" name="quantidade" id="quantidade" value="{{ $pedido->quantidade }}" required min="1" class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Preço Unitário</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-money text-2xl text-slate-300"></i>
                                <input type="text" name="valor" id="money-mask" value="{{ number_format($pedido->valor, 2, ',', '.') }}" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="md:col-span-2 p-8 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 flex justify-between items-center">
                            <div>
                                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Valor Total Atualizado</h4>
                                <p class="text-slate-400 text-xs">Baseado na nova quantidade e preço</p>
                            </div>
                            <span id="total-display" class="text-3xl font-black text-indigo-600">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-edit text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-floppy-disk text-2xl"></i>
                            Atualizar Pedido
                        </button>
                        
                        <a href="{{ route('pedidos.index') }}" class="flex-1 bg-slate-100 text-slate-500 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                            <i class="ph ph-arrow-left text-xl"></i>
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800 });

        document.addEventListener('DOMContentLoaded', function() {
            const valorInput = document.getElementById('money-mask');
            const qtdInput = document.getElementById('quantidade');
            const totalDisplay = document.getElementById('total-display');

            function formatarMoeda(e) {
                let v = e.target.value.replace(/\D/g, "");
                v = (v / 100).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
                e.target.value = v;
                calcularTotal();
            }

            function calcularTotal() {
                // Pega o valor, remove "R$", remove o ponto de milhar e troca vírgula por ponto
                const valorLimpo = valorInput.value.replace(/[^\d,]/g, '').replace(',', '.');
                const preco = parseFloat(valorLimpo) || 0;
                const qtd = parseInt(qtdInput.value) || 0;
                
                const total = preco * qtd;

                totalDisplay.innerText = total.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }

            valorInput.addEventListener('input', formatarMoeda);
            qtdInput.addEventListener('input', calcularTotal);

            // IMPORTANTE: Disparar o cálculo ao carregar para mostrar o total do pedido existente
            if(valorInput.value) {
                // Força a formatação inicial com R$ se não tiver
                if(!valorInput.value.includes('R$')) {
                    let v = valorInput.value.replace(/\D/g, "");
                    valorInput.value = (v / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                }
                calcularTotal();
            }
        });
    </script>
</x-app-layout>