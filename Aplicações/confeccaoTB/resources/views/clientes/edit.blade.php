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
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_40px_80px_-15px_rgba(15,23,42,0.15)] bg-white" data-aos="fade-up">
            
            <div class="lg:col-span-4 bg-edit-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-indigo-500/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-8 border border-indigo-500/30">
                        <i class="ph-fill ph-user-circle-gear text-3xl text-indigo-400"></i>
                    </div>
                    <h2 class="text-4xl font-black italic tracking-tighter leading-tight mb-4">Ajustar<br>Perfil</h2>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Atualize os dados de <span class="text-indigo-300 font-bold">{{ $cliente->nome }}</span>. Lembre-se que e-mail e CPF devem ser únicos.
                    </p>
                </div>

                <div class="relative z-10 mt-12">
                    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400">
                        <span class="w-10 h-[2px] bg-indigo-500"></span>
                        Gestão de Elite
                    </div>
                </div>

                <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 rounded-full blur-[100px]"></div>
            </div>

            <div class="lg:col-span-8 p-12 lg:p-16 bg-slate-50/50">
                <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Nome Completo</label>
                            <div class="input-capsule flex items-center bg-white rounded-3xl border border-slate-200 transition-all duration-300">
                                <div class="pl-6 text-slate-400"><i class="ph-bold ph-user text-xl"></i></div>
                                <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" required
                                       class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">CPF / Documento</label>
                            <div class="input-capsule flex items-center bg-white rounded-3xl border border-slate-200 transition-all duration-300">
                                <div class="pl-6 text-slate-400"><i class="ph-bold ph-identification-card text-xl"></i></div>
                                <input type="text" name="cpf" value="{{ old('cpf', $cliente->cpf) }}" required
                                       class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Telefone de Contato</label>
                            <div class="input-capsule flex items-center bg-white rounded-3xl border border-slate-200 transition-all duration-300">
                                <div class="pl-6 text-slate-400"><i class="ph-bold ph-phone text-xl"></i></div>
                                <input type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}" required
                                       class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">E-mail Profissional</label>
                            <div class="input-capsule flex items-center bg-white rounded-3xl border border-slate-200 transition-all duration-300">
                                <div class="pl-6 text-slate-400"><i class="ph-bold ph-envelope-simple text-xl"></i></div>
                                <input type="email" name="email" value="{{ old('email', $cliente->email) }}" required
                                       class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Endereço Residencial</label>
                            <div class="input-capsule flex items-center bg-white rounded-3xl border border-slate-200 transition-all duration-300">
                                <div class="pl-6 text-slate-400"><i class="ph-bold ph-map-pin text-xl"></i></div>
                                <input type="text" name="endereco" value="{{ old('endereco', $cliente->endereco) }}" required
                                       class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-edit text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-check-circle text-2xl"></i>
                            Salvar Alterações
                        </button>
                        
                        <a href="{{ route('clientes.index') }}" class="flex-1 bg-white text-slate-400 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest border border-slate-200 hover:bg-slate-100 transition-all text-center flex items-center justify-center">
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init(); 
// Função de máscara profissional
    const handleMask = (event, type) => {
        let input = event.target;
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não é número

        if (type === 'cpf') {
            value = value.substring(0, 11);
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } 
        
        if (type === 'tel') {
            value = value.substring(0, 11);
            if (value.length > 10) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
        input.value = value;
    }

    // Aplicando aos inputs
    const cpfInput = document.querySelector('input[name="cpf"]');
    const telInput = document.querySelector('input[name="telefone"]');

    if(cpfInput) cpfInput.addEventListener('input', (e) => handleMask(e, 'cpf'));
    if(telInput) telInput.addEventListener('input', (e) => handleMask(e, 'tel'));

    </script>
</x-app-layout>