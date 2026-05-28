<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestiona tus clientes y sus contactos</p>
            </div>
            <a
                href="/clients/create"
                class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200 font-medium text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo cliente
            </a>
            <div class="flex gap-2">
                <button
                    @click="exportExcel"
                    class="flex items-center gap-1.5 px-3 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-emerald-300 hover:text-emerald-600 transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Excel
                </button>
                <button
                    @click="exportPdf"
                    class="flex items-center gap-1.5 px-3 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 hover:text-red-600 transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    PDF
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar por nombre..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                        @input="onSearch"
                    />
                </div>
                <div class="relative min-w-[180px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <select
                        v-model="statusFilter"
                        class="w-full pl-10 pr-8 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm appearance-none bg-white"
                        @change="onFilter"
                    >
                        <option value="">Todos los estados</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="prospecto">Prospecto</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="loading" class="flex justify-center py-16">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        <div v-else-if="clients.length === 0" class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">No se encontraron clientes</p>
            <p class="text-gray-400 text-sm mt-1">Crea tu primer cliente para empezar</p>
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="client in clients"
                :key="client.id"
                class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all"
            >
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <span class="text-indigo-600 font-bold text-sm">{{ client.name.charAt(0).toUpperCase() }}</span>
                                </div>
                                <div>
                                    <a
                                        :href="'/clients/' + client.id"
                                        class="text-lg font-semibold text-gray-800 hover:text-indigo-600 transition-colors"
                                    >
                                        {{ client.name }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-sm text-gray-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ client.contacts_count || 0 }} contacto(s)
                                        </span>
                                        <span class="text-gray-300">·</span>
                                        <span class="text-sm text-gray-500">{{ formatDate(client.created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                            <p v-if="client.description" class="mt-2 ml-[52px] text-sm text-gray-600 line-clamp-1">{{ client.description }}</p>
                        </div>
                        <div class="flex items-center gap-3 ml-4">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full"
                                :class="{
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200': client.status === 'activo',
                                    'bg-red-50 text-red-700 border border-red-200': client.status === 'inactivo',
                                    'bg-amber-50 text-amber-700 border border-amber-200': client.status === 'prospecto',
                                }"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full"
                                    :class="{
                                        'bg-emerald-500': client.status === 'activo',
                                        'bg-red-500': client.status === 'inactivo',
                                        'bg-amber-500': client.status === 'prospecto',
                                    }"
                                ></span>
                                {{ client.status }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 ml-[52px] flex gap-3 pt-3 border-t border-gray-100">
                        <a
                            :href="'/clients/' + client.id"
                            class="flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver detalle
                        </a>
                        <a
                            :href="'/clients/' + client.id + '/edit'"
                            class="flex items-center gap-1 text-sm text-gray-600 hover:text-indigo-600 font-medium transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                        <button
                            @click="handleDelete(client)"
                            class="flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="pagination && pagination.lastPage > 1" class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">
                    Página <span class="font-medium">{{ pagination.currentPage }}</span> de <span class="font-medium">{{ pagination.lastPage }}</span>
                    — <span class="font-medium">{{ pagination.total }}</span> resultados
                </span>
                <div class="flex gap-2">
                    <button
                        :disabled="!pagination.currentPage || pagination.currentPage <= 1"
                        @click="changePage(pagination.currentPage - 1)"
                        class="flex items-center gap-1 px-3 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    <button
                        :disabled="pagination.currentPage >= pagination.lastPage"
                        @click="changePage(pagination.currentPage + 1)"
                        class="flex items-center gap-1 px-3 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useClientsStore } from '../stores/clients';
import api from '../bootstrap';

const authStore = useAuthStore();
const clientsStore = useClientsStore();

const search = ref('');
const statusFilter = ref('');
const loading = ref(false);
const clients = ref([]);
const pagination = ref(null);

let searchTimeout = null;

onMounted(() => {
    if (!authStore.isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    loadClients();
});

async function loadClients(page = 1) {
    loading.value = true;
    try {
        const params = { page };
        if (search.value) params.search = search.value;
        if (statusFilter.value) params.status = statusFilter.value;

        await clientsStore.fetchClients(params);
        clients.value = clientsStore.clients;
        pagination.value = clientsStore.pagination;
    } finally {
        loading.value = false;
    }
}

function onSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadClients(), 300);
}

function onFilter() {
    loadClients();
}

function changePage(page) {
    loadClients(page);
}

async function downloadExport(url, filename) {
    try {
        const params = {};
        if (search.value) params.search = search.value;
        if (statusFilter.value) params.status = statusFilter.value;
        const response = await api.get(url, {
            params,
            responseType: 'blob',
        });
        const blobUrl = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = blobUrl;
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(blobUrl);
    } catch (e) {
        console.error('Export error:', e);
        console.error('Response:', e.response?.status, e.response?.data);
        const msg = e.response?.data?.message || e.message || 'Error al descargar el archivo.';
        alert(msg);
    }
}

function exportExcel() {
    downloadExport('/export/clients/excel', 'clientes.xlsx');
}

function exportPdf() {
    downloadExport('/export/clients/pdf', 'clientes.pdf');
}

async function handleDelete(client) {
    if (!confirm(`¿Eliminar el cliente "${client.name}"?`)) return;
    try {
        await clientsStore.deleteClient(client.id);
        loadClients(pagination.value?.currentPage || 1);
    } catch {
        alert('Error al eliminar el cliente.');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}
</script>
