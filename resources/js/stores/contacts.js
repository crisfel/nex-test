import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '../bootstrap';

export const useContactsStore = defineStore('contacts', () => {
    const contacts = ref([]);
    const loading = ref(false);

    async function fetchContacts(clientId) {
        loading.value = true;
        try {
            const { data } = await api.get(`/clients/${clientId}/contacts`);
            contacts.value = data;
            return data;
        } finally {
            loading.value = false;
        }
    }

    async function createContact(clientId, payload) {
        const { data } = await api.post(`/clients/${clientId}/contacts`, payload);
        return data;
    }

    async function updateContact(contactId, payload) {
        const { data } = await api.put('/contacts', { ...payload, id: contactId });
        return data;
    }

    async function deleteContact(contactId) {
        await api.delete(`/contacts/${contactId}`);
    }

    return {
        contacts,
        loading,
        fetchContacts,
        createContact,
        updateContact,
        deleteContact,
    };
});
