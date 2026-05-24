export default defineNuxtRouteMiddleware((to, from) => {
  const token = useCookie('auth_token');
  const role = useCookie('user_role');

  // Rotas públicas
  const publicRoutes = ['/login'];
  if (publicRoutes.includes(to.path)) {
    if (token.value) {
      // Se estiver logado, redireciona para o dashboard apropriado
      if (role.value === 'admin') return navigateTo('/admin/dashboard');
      if (role.value === 'professor') return navigateTo('/professor/dashboard');
      if (role.value === 'tecnico') return navigateTo('/gateway/dashboard');
    }
    return;
  }

  // Se não tiver token, redireciona para login
  if (!token.value) {
    return navigateTo('/login');
  }

  // Verificar permissões por rota
  const adminRoutes = ['/admin'];
  const professorRoutes = ['/professor'];
  const gatewayRoutes = ['/gateway'];

  // Admin pode acessar tudo
  if (role.value === 'admin') {
    return;
  }

  // Professor só pode acessar rotas de professor
  if (role.value === 'professor' && professorRoutes.some(route => to.path.startsWith(route))) {
    return;
  }

  // Portaria (tecnico) só pode acessar rotas de gateway
  if (role.value === 'tecnico' && gatewayRoutes.some(route => to.path.startsWith(route))) {
    return;
  }

  // Redirecionar para o dashboard correto
  if (role.value === 'admin') return navigateTo('/admin/dashboard');
  if (role.value === 'professor') return navigateTo('/professor/dashboard');
  if (role.value === 'tecnico') return navigateTo('/gateway/dashboard');
  
  return navigateTo('/login');
});