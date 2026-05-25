<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <nav v-if="authStore.isAuthenticated" class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <router-link :to="{ name: 'ClientList' }" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Mini CRM</span>
                    </router-link>
                    <div class="flex items-center gap-6">
                        <span class="text-sm text-gray-500">Bienvenido,</span>
                        <span class="text-sm font-semibold text-gray-700">{{ authStore.user?.name }}</span>
                        <button @click="handleLogout" class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Salir
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <router-view />
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();
const router = useRouter();

onMounted(() => {
    authStore.loadFromStorage();
});

async function handleLogout() {
    await authStore.logout();
    router.push({ name: 'Login' });
}
</script>
