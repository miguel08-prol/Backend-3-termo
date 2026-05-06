<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Conta | Maestria Têxtil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white">

    <div class="flex min-h-screen">
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/95 via-indigo-900/70 to-transparent z-10"></div>
            
            <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                 class="absolute inset-0 w-full h-full object-cover" 
                 alt="Confecção">

            <div class="relative z-20 p-16">
                <div class="bg-white/10 backdrop-blur-md p-10 rounded-[2.5rem] border border-white/20 shadow-2xl">
                    <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
                        Faça parte da <br>
                        <span class="text-indigo-400">nova era da moda.</span>
                    </h2>
                    <p class="text-indigo-100 text-lg leading-relaxed max-w-md">
                        Crie a sua conta em menos de um minuto e assuma o controle total da sua produção, desde o fio até a entrega final.
                    </p>
                    
                    <div class="mt-10 flex items-center gap-4">
                        <div class="flex -space-x-3">
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-900 bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">A</div>
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-900 bg-purple-500 flex items-center justify-center text-white font-bold text-xs">B</div>
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-900 bg-emerald-500 flex items-center justify-center text-white font-bold text-xs text-center">MODA</div>
                        </div>
                        <p class="text-sm font-medium text-indigo-200 uppercase tracking-widest">Tecnologia & Estilo</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 bg-white">
            <div class="w-full max-w-md">
                
                <div class="flex items-center gap-2 mb-12 lg:hidden">
                    <div class="bg-indigo-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight">Confecção<span class="text-indigo-600">PRO</span></span>
                </div>

                <div class="mb-10">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">Criar Conta</h1>
                    <p class="text-gray-500 font-medium">Junte-se a nós e comece a organizar o seu ateliê hoje.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nome Completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-gray-300"
                               placeholder="Ex: Carlos Oliveira">
                        @if ($errors->has('name'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail Profissional</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-gray-300"
                               placeholder="seu@email.com">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Senha</label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-gray-300"
                               placeholder="Mínimo 8 caracteres">
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirmar Senha</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-gray-300"
                               placeholder="Repita a senha">
                    </div>

                    <button type="submit" 
                            class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg shadow-2xl shadow-indigo-200 transition-all active:scale-[0.97] mt-6">
                        Criar Conta Agora
                    </button>
                </form>

                <p class="mt-10 text-center text-gray-600 font-medium">
                    Já tem uma conta no sistema? 
                    <a href="{{ route('login') }}" class="text-indigo-600 font-extrabold hover:underline">Entrar</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>