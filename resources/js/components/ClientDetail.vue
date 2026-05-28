<template>
    <div>
        <div class="mb-6">
            <a href="/clients" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a clientes
            </a>
        </div>

        <div v-if="loading" class="flex justify-center py-16">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        <template v-else-if="client">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <span class="text-indigo-600 font-bold text-xl">{{ client.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ client.name }}</h1>
                            <div class="flex items-center gap-3 mt-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full"
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
                                <span class="text-sm text-gray-400">·</span>
                                <span class="text-sm text-gray-500">Creado el {{ formatDate(client.created_at) }}</span>
                            </div>
                            <p v-if="client.description" class="mt-3 text-gray-600 text-sm leading-relaxed">{{ client.description }}</p>
                        </div>
                    </div>
                    <a
                        :href="'/clients/' + client.id + '/edit'"
                        class="flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar cliente
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Contactos</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ contacts.length }} contacto(s) registrado(s)</p>
                    </div>
                    <button
                        @click="showCreateForm = true"
                        class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200 font-medium text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar contacto
                    </button>
                </div>

                <div v-if="contacts.length === 0" class="text-center py-12">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No hay contactos registrados</p>
                    <p class="text-gray-400 text-sm mt-1">Agrega el primer contacto para este cliente</p>
                </div>

                <div v-else class="space-y-3">
                    <div
                        v-for="contact in contacts"
                        :key="contact.id"
                        class="flex items-center justify-between p-4 rounded-xl border transition-all"
                        :class="contact.is_primary ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-100 hover:border-gray-200'"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                :class="contact.is_primary ? 'bg-indigo-200' : 'bg-gray-200'"
                            >
                                <span class="font-bold text-sm" :class="contact.is_primary ? 'text-indigo-700' : 'text-gray-600'">
                                    {{ contact.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-800 text-sm">{{ contact.name }}</span>
                                    <span
                                        v-if="contact.is_primary"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Principal
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mt-0.5 flex items-center gap-2">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ contact.email }}
                                    </span>
                                    <span v-if="contact.phone" class="text-gray-300">|</span>
                                    <span v-if="contact.phone" class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ contact.phone }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                            <button
                                @click="openEditForm(contact)"
                                class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                title="Editar contacto"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                @click="handleDelete(contact)"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                title="Eliminar contacto"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div v-else class="text-center py-16">
            <p class="text-gray-500">Cliente no encontrado.</p>
        </div>

        <ContactForm
            v-if="showCreateForm"
            :client-id="clientId"
            @close="showCreateForm = false"
            @saved="onContactSaved"
        />

        <ContactForm
            v-if="editingContact"
            :client-id="clientId"
            :contact="editingContact"
            @close="editingContact = null"
            @saved="onContactSaved"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useClientsStore } from '../stores/clients';
import { useContactsStore } from '../stores/contacts';
import ContactForm from './ContactForm.vue';

const props = defineProps({
    clientId: { type: [Number, String], required: true },
});

const authStore = useAuthStore();
const clientsStore = useClientsStore();
const contactsStore = useContactsStore();

const client = computed(() => clientsStore.currentClient);
const contacts = ref([]);
const loading = ref(false);
const showCreateForm = ref(false);
const editingContact = ref(null);

onMounted(async () => {
    if (!authStore.isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    loading.value = true;
    try {
        await clientsStore.fetchClient(props.clientId);
        await loadContacts();
    } finally {
        loading.value = false;
    }
});

async function loadContacts() {
    const data = await contactsStore.fetchContacts(props.clientId);
    contacts.value = data;
}

function openEditForm(contact) {
    editingContact.value = { ...contact };
}

async function onContactSaved() {
    showCreateForm.value = false;
    editingContact.value = null;
    await loadContacts();
}

async function handleDelete(contact) {
    if (!confirm(`¿Eliminar el contacto "${contact.name}"?`)) return;
    try {
        await contactsStore.deleteContact(contact.id);
        await loadContacts();
    } catch {
        alert('Error al eliminar el contacto.');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>
