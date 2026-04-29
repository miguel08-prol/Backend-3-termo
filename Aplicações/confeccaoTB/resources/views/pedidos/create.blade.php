<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .bg-order-dark { background: #1e1b4b; } 
        .gradient-order { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.2);
        }

        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1.5rem center; background-size: 1.5rem; }
        ::-webkit-scrollbar { display: none; }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(30,27,75,0.2)] bg-white" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-order-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-8 border border-white/20">
                        <i class="ph-fill ph-shopping-cart-simple text-3xl text-indigo-300"></i>
                    </div>
                    <h2 class="text-4xl font-black italic tracking-tighter leading-tight mb-4">Novo<br>Pedido</h2>
                    <p class="text-indigo-200/60 text-sm font-medium leading-relaxed">
                        Preencha os dados da nova ordem de serviço. O sistema calculará os totais automaticamente.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400">
                        <span class="w-10 h-[2px] bg-indigo-500"></span>
                        Gestão de Produção
                    </div>
                </div>

                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-[100px]"></div>
            </div>

            <div class="lg:col-span-8 p-12 lg:p-16">
                <form action="{{ route('pedidos.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Cliente</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-user text-2xl text-slate-300"></i>
                                <select name="cliente_id" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                                    <option value="">Selecione o Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Produto / Descrição</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-package text-2xl text-slate-300"></i>
                                <input type="text" name="produto" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold" placeholder="Ex: Camiseta Dry Fit">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Quantidade</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-hash text-2xl text-slate-300"></i>
                                <input type="number" name="quantidade" id="quantidade" required min="1" class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold" placeholder="0">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Preço Unitário</label>
                            <div class="input-capsule rounded-[2rem] flex items-center px-8 bg-slate-50 border-2 border-transparent transition-all">
                                <i class="ph-fill ph-money text-2xl text-slate-300"></i>
                                <input type="text" name="valor" id="money-mask" required class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold" placeholder="R$ 0,00">
                            </div>
                        </div>

                        <div class="md:col-span-2 p-8 bg-indigo-50 rounded-[2.5rem] border-2 border-dashed border-indigo-100 flex justify-between items-center">
                            <div>
                                <h4 class="text-[10px] font-black uppercase text-indigo-400 tracking-widest">Total Estimado</h4>
                                <p class="text-slate-500 text-xs">Cálculo automático em tempo real</p>
                            </div>
                            <span id="total-display" class="text-3xl font-black text-indigo-600">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-order text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-check-square-offset text-2xl"></i>
                            Finalizar e Abrir O.S
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

            // Máscara de Moeda e Gatilho para Cálculo
            valorInput.addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, "");
                v = (v / 100).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
                e.target.value = v;
                calcularTotal();
            });

            // Escuta mudança na quantidade
            qtdInput.addEventListener('input', calcularTotal);

            function calcularTotal() {
                // Converte string "R$ 1.500,50" para número decimal 1500.50
                const valorLimpo = valorInput.value.replace(/[^\d,]/g, '').replace(',', '.');
                const preco = parseFloat(valorLimpo) || 0;
                const qtd = parseInt(qtdInput.value) || 0;
                
                const total = preco * qtd;

                totalDisplay.innerText = total.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }
        });
    </script>
</x-app-layout>