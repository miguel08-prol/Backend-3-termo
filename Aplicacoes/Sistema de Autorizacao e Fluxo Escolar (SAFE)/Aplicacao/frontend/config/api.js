export const API_CONFIG = {
    baseURL: 'http://127.0.0.1:8000/api',
    endpoints: {
        auth: {
            login: '/login',
            logout: '/logout',
            user: '/user'
        },
        maquinas: '/maquinas',
        ordens: '/ordens',
        usuarios: '/usuarios',
        tecnicos: '/tecnicos',
        metrics: '/ordens/metricas',
        meusChamados: '/meus-chamados'
    }
}