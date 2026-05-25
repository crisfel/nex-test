<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="$emit('close')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ isEdit ? 'Editar contacto' : 'Nuevo contacto' }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ isEdit ? 'Actualiza los datos del contacto' : 'Completa los datos del contacto' }}</p>
                </div>
                <button @click="$emit('close')" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5">
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ error }}
                </div>
                <div v-if="errors" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <li v-for="(msg, key) in errors" :key="key">{{ msg[0] || msg }}</li>
                    </ul>
                </div>

                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Nombre completo"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico *</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="correo@ejemplo.com"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="+52 55 1234 5678"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                        />
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input
                                    v-model="form.is_primary"
                                    type="checkbox"
                                    class="sr-only"
                                />
                                <div
                                    class="w-5 h-5 border-2 rounded-md flex items-center justify-center transition-all"
                                    :class="form.is_primary ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300 bg-white'"
                                >
                                    <svg v-if="form.is_primary" class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Marcar como contacto principal</span>
                                <p v-if="form.is_primary && !isEdit" class="text-xs text-amber-600 mt-0.5">
                                    Los otros contactos de este cliente pasarán a ser secundarios automáticamente.
                                </p>
                            </div>
                        </label>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-white transition-all font-medium text-sm"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    :disabled="saving"
                    @click="handleSubmit"
                    class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium shadow-md shadow-indigo-200 text-sm"
                >
                    <svg v-if="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ saving ? 'Guardando...' : 'Guardar contacto' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useContactsStore } from '../stores/contacts';

const props = defineProps({
    clientId: { type: [Number, String], required: true },
    contact: { type: Object, default: null },
});

const emit = defineEmits(['close', 'saved']);
const contactsStore = useContactsStore();

const isEdit = !!props.contact;
const saving = ref(false);
const error = ref(null);
const errors = ref(null);

const form = reactive({
    name: '',
    email: '',
    phone: '',
    is_primary: false,
});

onMounted(() => {
    if (isEdit && props.contact) {
        form.name = props.contact.name;
        form.email = props.contact.email;
        form.phone = props.contact.phone || '';
        form.is_primary = props.contact.is_primary;
    }
});

async function handleSubmit() {
    saving.value = true;
    error.value = null;
    errors.value = null;
    try {
        if (isEdit) {
            await contactsStore.updateContact(props.contact.id, form);
        } else {
            await contactsStore.createContact(props.clientId, form);
        }
        emit('saved');
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors;
        } else {
            error.value = err.response?.data?.message || 'Error al guardar el contacto.';
        }
    } finally {
        saving.value = false;
    }
}
</script>
