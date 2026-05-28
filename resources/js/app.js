import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import './bootstrap';

import AppLayout from './components/AppLayout.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import ClientList from './components/ClientList.vue';
import ClientForm from './components/ClientForm.vue';
import ClientDetail from './components/ClientDetail.vue';

const components = {
    login: Login,
    register: Register,
    'clients-index': ClientList,
    'clients-form': ClientForm,
    'clients-detail': ClientDetail,
};

const appContainer = document.getElementById('app');
if (appContainer) {
    const page = appContainer.dataset.page;
    const Comp = components[page];
    if (Comp) {
        const props = appContainer.dataset.props ? JSON.parse(appContainer.dataset.props) : {};
        createApp({
            render() {
                return h(AppLayout, null, {
                    default: () => h(Comp, props),
                });
            },
        }).use(createPinia()).mount('#app');
    }
}
