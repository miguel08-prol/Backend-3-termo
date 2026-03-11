<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .bg-vendor-dark { background: #0F172A; }
        .gradient-vendor { background: linear-gradient(135deg, #6366F1 0%, #4338CA 100%); }
        
        .input-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #F1F5F9;
            border: 2px solid transparent;
        }
        .input-wrapper:focus-within {
            background: white;
            border-color: #6366F1;
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15);
        }
        
        ::-webkit-scrollbar { display: none; }
    </style>

    <div class="min-h-screen py-10 flex items-center justify-center p-4">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden rounded-[3.5rem] bg-white shadow-[0_50px_100px_-20px_rgba(15,23,42,0.12)] border border-white" data-aos="zoom-in">
            
            <div class="lg:col-span-4 bg-vendor-dark p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <a href="{{ route('fornecedores.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-white transition-all group mb-12">
                        <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-indigo-500 transition-all">
                            <i class="ph-bold ph-arrow-left"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Lista de Fornecedores</span>
                    </a>

                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[1.5rem] flex items-center justify-center mb-6 shadow-xl">
                        <i class="ph-fill ph-buildings text-3xl"></i>
                    </div>
                    <h2 class="text-4xl font-black italic tracking-tighter mb-4 uppercase leading-none">
                        Editar<br><span class="text-indigo-400">Parceiro</span>
                    </h2>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed opacity-80">
                        Atualize as informações cadastrais e de contacto. Estes dados são essenciais para a emissão de notas e ordens de compra.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="p-6 bg-white/5 rounded-[2.5rem] border border-white/10 backdrop-blur-xl">
                        <p class="text-[10px] font-black uppercase text-indigo-400 tracking-[0.2em] mb-2">Documento Identificado</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-black italic">{{ $fornecedore->cnpj }}</span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl"></div>
            </div>

            <div class="lg:col-span-8 p-12 md:p-20 bg-white">
                
                {{-- Exibição de Erros para Debug --}}
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                        <p class="font-bold text-xs uppercase mb-2">Verifique os campos abaixo:</p>
                        <ul class="text-xs list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('fornecedores.update', $fornecedore->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Nome Fantasia (Comercial)</label>
                            <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                <i class="ph-fill ph-storefront text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $fornecedore->nome_fantasia) }}" required
                                       class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold text-lg">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Razão Social (Jurídico)</label>
                            <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                <i class="ph-fill ph-briefcase text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                <input type="text" name="razao_social" value="{{ old('razao_social', $fornecedore->razao_social) }}" required
                                       class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">E-mail de Contacto</label>
                                <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                    <i class="ph-bold ph-envelope-simple text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                    <input type="email" name="email" value="{{ old('email', $fornecedore->email) }}"
                                           class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold">
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">Telefone / WhatsApp</label>
                                <div class="input-wrapper rounded-[2rem] flex items-center px-8">
                                    <i class="ph-bold ph-phone text-2xl text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                    <input type="text" name="telefone" value="{{ old('telefone', $fornecedore->telefone) }}"
                                           class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold">
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 ml-4 tracking-[0.2em]">CNPJ</label>
                            <div class="input-wrapper rounded-[2rem] flex items-center px-8 bg-slate-50">
                                <i class="ph-bold ph-identification-card text-2xl text-slate-300"></i>
                                <input type="text" name="cnpj" value="{{ old('cnpj', $fornecedore->cnpj) }}" required
                                       class="w-full border-none bg-transparent py-6 px-4 focus:ring-0 text-slate-800 font-bold">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full gradient-vendor text-white py-7 rounded-[2.5rem] font-black text-xs uppercase tracking-[0.3em] shadow-[0_20px_40px_-10px_rgba(99,102,241,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-4">
                                <i class="ph-fill ph-check-circle text-2xl"></i>
                                Atualizar Fornecedor
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