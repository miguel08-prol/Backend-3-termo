<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Maestria Têxtil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white">

    <div class="flex min-h-screen">
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 via-indigo-900/60 to-transparent z-10"></div>
            
            <img src="https://images.unsplash.com/photo-1555529771-835f59fc5efe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80" 
                 class="absolute inset-0 w-full h-full object-cover" 
                 alt="Confecção">

            <div class="relative z-20 p-16">
                <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20">
                    <h2 class="text-4xl font-extrabold text-white mb-4 leading-tight">
                        A moda passa, <br>
                        <span class="text-indigo-400">o estilo permanece.</span>
                    </h2>
                    <p class="text-indigo-100 text-lg leading-relaxed">
                        Organize sua produção com a mesma precisão de um corte perfeito. 
                        Tudo o que seu ateliê precisa em um único lugar.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 bg-white">
            <div class="w-full max-w-md">
                
                <div class="flex items-center gap-2 mb-12 lg:hidden">
                    <div class="bg-indigo-600 p-2 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5z"></path></svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">Confecção<span class="text-indigo-600">PRO</span></span>
                </div>

                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Entrar</h1>
                    <p class="text-gray-500 font-medium">Bem-vindo de volta! Por favor, insira os seus dados.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all"
                               placeholder="seu@email.com">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="text-sm font-bold text-gray-700">Senha</label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">Esqueceu?</a>
                        </div>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all"
                               placeholder="••••••••">
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600 font-medium">Manter conectado</label>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 transition-all active:scale-[0.98]">
                        Acessar Painel
                    </button>
                </form>

                <p class="mt-10 text-center text-gray-600 font-medium">
                    Ainda não tem conta? 
                    <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Criar conta grátis</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>