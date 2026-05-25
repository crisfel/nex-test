import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../bootstrap';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const token = ref(null);

    const isAuthenticated = computed(() => !!token.value);

    function loadFromStorage() {
        const savedToken = localStorage.getItem('auth_token');
        const savedUser = localStorage.getItem('auth_user');
        if (savedToken) {
            token.value = savedToken;
        }
        if (savedUser) {
            user.value = JSON.parse(savedUser);
        }
    }

    async function register(payload) {
        const { data } = await api.post('/register', payload);
        token.value = data.token;
        user.value = data.user;
        localStorage.setItem('auth_token', data.token);
        localStorage.setItem('auth_user', JSON.stringify(data.user));
        return data;
    }

    async function login(payload) {
        const { data } = await api.post('/login', payload);
        token.value = data.token;
        user.value = data.user;
        localStorage.setItem('auth_token', data.token);
        localStorage.setItem('auth_user', JSON.stringify(data.user));
        return data;
    }

    async function logout() {
        try {
            await api.post('/logout');
        } catch {
            // ignore if token is already invalid
        }
        token.value = null;
        user.value = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
    }

    async function fetchUser() {
        try {
            const { data } = await api.get('/user');
            user.value = data;
            localStorage.setItem('auth_user', JSON.stringify(data));
        } catch {
            await logout();
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        loadFromStorage,
        register,
        login,
        logout,
        fetchUser,
    };
});
