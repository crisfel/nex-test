import { createRouter, createWebHashHistory } from 'vue-router';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('../components/Login.vue'),
        meta: { guest: true },
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../components/Register.vue'),
        meta: { guest: true },
    },
    {
        path: '/',
        name: 'ClientList',
        component: () => import('../components/ClientList.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/clients/create',
        name: 'ClientCreate',
        component: () => import('../components/ClientForm.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/clients/:id',
        name: 'ClientDetail',
        component: () => import('../components/ClientDetail.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/clients/:id/edit',
        name: 'ClientEdit',
        component: () => import('../components/ClientForm.vue'),
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');

    if (to.meta.requiresAuth && !token) {
        next({ name: 'Login' });
    } else if (to.meta.guest && token) {
        next({ name: 'ClientList' });
    } else {
        next();
    }
});

export default router;
