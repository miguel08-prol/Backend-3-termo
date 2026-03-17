<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha | Confecção PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white">

    <div class="flex min-h-screen">
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-bl from-indigo-900/95 via-indigo-900/70 to-transparent z-10"></div>
            
            <img src="https://media.istockphoto.com/id/2191833269/pt/foto/protected-network-security-system-concept-copy-space.jpg?s=2048x2048&w=is&k=20&c=aeZbQviVhb7wy2_oeXx0Y3Dehk9Y49UAaG35tAaIH2I=" 
                 class="absolute inset-0 w-full h-full object-cover" 
                 alt="Organização de Ateliê">

            <div class="relative z-20 p-16 text-center">
                <div class="bg-white/10 backdrop-blur-md p-10 rounded-[2.5rem] border border-white/20 shadow-2xl">
                    <div class="w-20 h-20 bg-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-white mb-4">Segurança em primeiro lugar.</h2>
                    <p class="text-indigo-100 text-lg leading-relaxed">
                        Não se preocupe, acontece aos melhores. Vamos ajudá-lo a recuperar o acesso ao seu painel de forma rápida e segura.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-16 bg-white">
            <div class="w-full max-w-md">
                
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-500 mb-10 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Voltar para o Login
                </a>

                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3 tracking-tight">Recuperar Senha</h1>
                    <p class="text-gray-500 font-medium leading-relaxed">
                        Esqueceu a sua senha? Sem problema. Informe o seu e-mail e enviaremos um link para definir uma nova.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 font-medium text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail Cadastrado</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all placeholder:text-gray-300"
                               placeholder="exemplo@email.com">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg shadow-xl shadow-indigo-200 transition-all active:scale-[0.98]">
                        Enviar Link de Recuperação
                    </button>
                </form>

                <div class="mt-12 p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                    <p class="text-sm text-gray-500">
                        Ainda precisa de ajuda? <br>
                        <span class="text-gray-900 font-bold">suporte@confeccaopro.com</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>