<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f5; }
        .bg-ultra-dark { background: #0F172A; }
        .gradient-primary { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); }
        .text-gradient { background: linear-gradient(135deg, #6366F1 0%, #A855F7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Efeito de Vidro Flutuante */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .input-capsule:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.2);
        }
    </style>

    <div class="min-h-screen py-12 flex items-center justify-center p-6">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.2)]" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-ultra-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/20 rounded-full blur-[80px] -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/40">
                        <i class="ph-fill ph-users-three text-2xl"></i>
                    </div>
                    <h1 class="text-4xl font-black italic tracking-tighter leading-none mb-4">
                        DESIGN <br> <span class="text-indigo-400">PARTNERS</span>
                    </h1>
                    <p class="text-slate-400 font-medium leading-relaxed">
                        Registre seus parceiros com a sofisticação que seu negócio exige.
                    </p>
                </div>

                <div class="relative z-10 space-y-6">
                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center group-hover:bg-indigo-500 transition-all">
                            <i class="ph ph-check-circle text-indigo-400 group-hover:text-white"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Dados Criptografados</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-8 md:p-16 relative">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 italic tracking-tight">Novo Registro</h2>
                        <div class="h-1.5 w-12 gradient-primary rounded-full mt-2"></div>
                    </div>
                    <a href="{{ route('clientes.index') }}" class="group flex items-center gap-2 text-slate-400 font-black text-xs uppercase tracking-widest hover:text-indigo-600 transition-all">
                        <i class="ph-bold ph-caret-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('clientes.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        <div class="col-span-full space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500 transition-colors">Nome do Cliente</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-user-circle text-slate-400 text-2xl"></i>
                                <input type="text" name="nome" value="{{ old('nome') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Nome completo ou empresa">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Documentação</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-identification-card text-slate-400 text-2xl"></i>
                                <input type="text" name="cpf" value="{{ old('cpf') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="000.000.000-00">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">E-mail Principal</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-envelope-simple text-slate-400 text-2xl"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="exemplo@dominio.com">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Contato Direto</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-device-mobile-speaker text-slate-400 text-2xl"></i>
                                <input type="text" name="telefone" value="{{ old('telefone') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="(00) 0 0000-0000">
                            </div>
                        </div>

                        <div class="space-y-3 group">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1 group-focus-within:text-indigo-500">Localização</label>
                            <div class="input-capsule flex items-center bg-slate-50 rounded-3xl border-2 border-transparent focus-within:border-indigo-500/20 focus-within:bg-white transition-all px-6">
                                <i class="ph ph-map-pin text-slate-400 text-2xl"></i>
                                <input type="text" name="endereco" value="{{ old('endereco') }}" required
                                    class="w-full border-none bg-transparent py-5 px-4 focus:ring-0 text-slate-700 font-bold placeholder:text-slate-300"
                                    placeholder="Cidade, Estado">
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-[3] gradient-primary text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-[0_20px_40px_-10px_rgba(99,102,241,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <i class="ph-fill ph-rocket-launch text-2xl"></i>
                            Finalizar e Salvar
                        </button>
                        
                        <button type="reset" class="flex-1 bg-slate-100 text-slate-400 py-6 rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center gap-2 group">
                            <i class="ph ph-arrow-counter-clockwise text-xl group-hover:rotate-180 transition-transform duration-500"></i>
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