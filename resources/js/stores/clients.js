import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '../bootstrap';

export const useClientsStore = defineStore('clients', () => {
    const clients = ref([]);
    const currentClient = ref(null);
    const pagination = ref(null);
    const loading = ref(false);

    async function fetchClients(params = {}) {
        loading.value = true;
        try {
            const { data } = await api.get('/clients', { params });
            clients.value = data.data ?? data;
            pagination.value = {
                currentPage: data.current_page,
                lastPage: data.last_page,
                total: data.total,
                perPage: data.per_page,
            };
        } finally {
            loading.value = false;
        }
    }

    async function fetchClient(id) {
        loading.value = true;
        try {
            const { data } = await api.get(`/clients/${id}`);
            currentClient.value = data;
            return data;
        } finally {
            loading.value = false;
        }
    }

    async function createClient(payload) {
        const { data } = await api.post('/clients', payload);
        return data;
    }

    async function updateClient(id, payload) {
        const { data } = await api.put(`/clients/${id}`, payload);
        return data;
    }

    async function deleteClient(id) {
        await api.delete(`/clients/${id}`);
    }

    return {
        clients,
        currentClient,
        pagination,
        loading,
        fetchClients,
        fetchClient,
        createClient,
        updateClient,
        deleteClient,
    };
});
