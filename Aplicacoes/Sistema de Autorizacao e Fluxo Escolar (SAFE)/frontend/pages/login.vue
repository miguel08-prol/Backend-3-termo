<template>
  <div class="min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900">
      <img 
        src="../public/img_login/loginimg.svg" 
        alt="Manutenção Industrial" 
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-black/30"></div>
      
      <div class="relative z-10 flex flex-col justify-end p-12 text-white h-full">
        <h1 class="text-5xl font-extrabold mb-4 tracking-tight">
          <span class="text-red-600">SA</span>FE
        </h1>
        <p class="text-lg text-gray-200 max-w-lg leading-relaxed">
          O Sistema de Autorização e Fluxo Escolar. Simplifique processos e aumente a eficiência.
        </p>
      </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 relative">
      <div class="w-full max-w-md p-8 sm:p-10">
        <AuthLoginForm />
      </div>
    </div>
  </div>
</template>