<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .bg-logistics { background: #1e293b; }
        .gradient-supplier { background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%); }
        
        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15);
        }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.1)]" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-logistics p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 gradient-supplier rounded-2xl flex items-center justify-center mb-8 shadow-lg">
                        <i class="ph-fill ph-truck text-2xl"></i>
                    </div>
                    <h1 class="text-4xl font-black italic tracking-tighter leading-none mb-4">
                        SUPPLY <br> <span class="text-blue-400">CHAIN</span>
                    </h1>
                    <p class="text-slate-400 font-medium leading-relaxed text-sm">
                        Cadastre seus parceiros estratégicos e garanta o fluxo contínuo de matéria-prima.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/10">
                    <i class="ph ph-shield-check text-blue-400 text-xl"></i>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Homologação de Parceiros</span>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-8 md:p-16 relative">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 italic tracking-tight">Novo Fornecedor</h2>
                        <div class="h-1.5 w-12 gradient-supplier rounded-full mt-2"></div>
                    </div>
                    <a href="{{ route('fornecedores.index') }}" class="group flex items-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-indigo-600 transition-all">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        Painel
                    </a>
                </div>

                <form action="{{ route('fornecedores.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500 transition-colors">Nome Fantasia</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-storefront text-slate-400 text-2xl"></i>
                                <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Nome comercial da empresa">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Razão Social</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-buildings text-slate-400 text-2xl"></i>
                                <input type="text" name="razao_social" value="{{ old('razao_social') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Nome jurídico completo">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Documentação (CNPJ)</label>
                           <div class="input-capsule rounded-2xl flex items-center px-4 bg-white/50 border border-slate-200 group transition-all">
    <i class="ph-bold ph-identification-card text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
    <input type="text" name="cnpj" id="cnpj" value="{{ old('cnpj') }}" required
           class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
           placeholder="00.000.000/0000-00">
</div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">E-mail Comercial</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-blue-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-envelope-simple text-slate-400 text-2xl"></i>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="contato@fornecedor.com">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-blue-500">Telefone / WhatsApp</label>
                           <div class="input-capsule rounded-2xl flex items-center px-4 bg-white/50 border border-slate-200 group transition-all">
    <i class="ph-bold ph-phone text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
    <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}"
           class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
           placeholder="(00) 00000-0000">
</div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-supplier text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-[0_20px_40px_-10px_rgba(59,130,246,0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-check-circle text-2xl"></i>
                            Confirmar Cadastro
                        </button>
                        
                        <button type="reset" class="flex-1 bg-slate-100 text-slate-400 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center gap-2 group">
                            <i class="ph ph-arrow-counter-clockwise text-xl group-hover:rotate-180 transition-transform duration-500"></i>
                            Resetar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
   <script>
    AOS.init({ duration: 800 });

    const applyMask = (input, maskType) => {
        if (!input) return; // Evita erro se o input não for encontrado

        input.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');

            if (maskType === 'cnpj') {
                v = v.substring(0, 14);
                v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                v = v.replace(/(\d{4})(\d)/, '$1-$2');
            } 
            else if (maskType === 'tel') {
                v = v.substring(0, 11);
                if (v.length > 10) {
                    v = v.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else {
                    v = v.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                }
            }
            e.target.value = v;
        });
    };

    // Inicialização
    const inputCnpj = document.getElementById('cnpj');
    const inputTel = document.getElementById('telefone');

    applyMask(inputCnpj, 'cnpj');
    applyMask(inputTel, 'tel');

    // Forçar a formatação inicial se já houver dados (útil no Edit)
    if(inputCnpj && inputCnpj.value) inputCnpj.dispatchEvent(new Event('input'));
    if(inputTel && inputTel.value) inputTel.dispatchEvent(new Event('input'));
</script>
</x-app-layout>