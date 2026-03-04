<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
        ::-webkit-scrollbar { display: none; }
    </style>

    <div class="flex min-h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-72' : 'w-24'" 
               class="fixed left-0 top-0 h-full bg-white border-r border-slate-100 transition-all duration-300 z-50 flex flex-col">
            
            <div class="p-8 flex items-center gap-4">
                <div class="gradient-bg p-2.5 rounded-2xl text-white shadow-lg shrink-0">
                    <i class="ph-fill ph-needle-thread text-2xl"></i>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-xl font-black tracking-tighter text-slate-900 italic">Confecção<span class="text-indigo-600">PRO</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-3">
                <p x-show="sidebarOpen" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4 mb-4">Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-all group">
                    <i class="ph-bold ph-house text-2xl"></i>
                    <span x-show="sidebarOpen" x-transition.opacity>Painel Geral</span>
                </a>
                </nav>

            <div class="p-6 mt-auto border-t border-slate-50">
                <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-50 text-slate-400 hover:text-indigo-600 transition-all">
                    <i class="ph-bold text-xl" :class="sidebarOpen ? 'ph-caret-double-left' : 'ph-caret-double-right'"></i>
                </button>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-72' : 'ml-24'" class="flex-1 transition-all duration-300">
            
            <header class="h-24 flex items-center justify-between px-10 bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight italic">Meu Perfil</h2>
                </div>
            </header>

            <div class="p-10 max-w-5xl mx-auto space-y-10">
                
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm" data-aos="fade-up">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-red-50/30 p-10 rounded-[3rem] border border-red-100 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800, once: false });</script>
</x-app-layout>