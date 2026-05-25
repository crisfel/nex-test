<template>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <router-link :to="{ name: 'ClientList' }" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a clientes
            </router-link>
            <h1 class="text-2xl font-bold text-gray-800">{{ isEdit ? 'Editar cliente' : 'Nuevo cliente' }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ isEdit ? 'Actualizá los datos del cliente' : 'Completá los datos para registrar un nuevo cliente' }}</p>
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

        <form @submit.prevent="handleSubmit" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del cliente *</label>
                <input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Nombre de la empresa"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                <select
                    v-model="form.status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm appearance-none bg-white"
                >
                    <option value="prospecto">Prospecto</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                <textarea
                    v-model="form.description"
                    rows="4"
                    placeholder="Breve descripción del cliente..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm resize-none"
                ></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="saving"
                    class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium shadow-md shadow-indigo-200 text-sm"
                >
                    <svg v-if="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ saving ? 'Guardando...' : 'Guardar cliente' }}
                </button>
                <router-link
                    :to="{ name: 'ClientList' }"
                    class="flex items-center gap-1.5 px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all font-medium text-sm"
                >
                    Cancelar
                </router-link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useClientsStore } from '../stores/clients';

const route = useRoute();
const router = useRouter();
const clientsStore = useClientsStore();

const isEdit = computed(() => !!route.params.id);
const saving = ref(false);
const error = ref(null);
const errors = ref(null);

const form = reactive({
    name: '',
    status: 'prospecto',
    description: '',
});

onMounted(async () => {
    if (isEdit.value) {
        try {
            const client = await clientsStore.fetchClient(route.params.id);
            form.name = client.name;
            form.status = client.status;
            form.description = client.description || '';
        } catch {
            error.value = 'Error al cargar el cliente.';
        }
    }
});

async function handleSubmit() {
    saving.value = true;
    error.value = null;
    errors.value = null;
    try {
        if (isEdit.value) {
            await clientsStore.updateClient(route.params.id, form);
        } else {
            await clientsStore.createClient(form);
        }
        router.push({ name: 'ClientList' });
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors;
        } else {
            error.value = err.response?.data?.message || 'Error al guardar el cliente.';
        }
    } finally {
        saving.value = false;
    }
}
</script>
