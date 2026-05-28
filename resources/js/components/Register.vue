<template>
    <div class="min-h-[80vh] flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Crear cuenta</h1>
                <p class="text-sm text-gray-500 mt-1">Regístrate para gestionar tus clientes</p>
            </div>

            <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ error }}
            </div>
            <div v-if="errors" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <li v-for="(msg, key) in errors" :key="key">{{ msg[0] || msg }}</li>
                </ul>
            </div>

            <form @submit.prevent="handleRegister" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Tu nombre completo"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        placeholder="ejemplo@correo.com"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Contraseña</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        placeholder="Mínimo 8 caracteres"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmar contraseña</label>
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        placeholder="Repite la contraseña"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                    />
                </div>
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-indigo-600 text-white py-2.5 px-4 rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium shadow-md shadow-indigo-200"
                >
                    <span v-if="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Registrando...
                    </span>
                    <span v-else>Crear cuenta</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                ¿Ya tienes cuenta?
                <a href="/login" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    Iniciar sesión
                </a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();

const form = reactive({ name: '', email: '', password: '', password_confirmation: '' });
const error = ref(null);
const errors = ref(null);
const loading = ref(false);

async function handleRegister() {
    error.value = null;
    errors.value = null;
    loading.value = true;
    try {
        await authStore.register(form);
        window.location.href = '/clients';
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors;
        } else {
            error.value = err.response?.data?.message || 'Error al registrarse.';
        }
    } finally {
        loading.value = false;
    }
}
</script>
