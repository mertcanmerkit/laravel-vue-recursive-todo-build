import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import '../css/app.css';

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrash, faEdit, faPlus, faArrowLeft, faCheck, faFlag, faArrowTurnDown, faEllipsisVertical } from '@fortawesome/free-solid-svg-icons';

library.add(faTrash, faEdit, faPlus, faArrowLeft, faCheck, faFlag, faArrowTurnDown, faEllipsisVertical);

const app = createApp(App);
app.use(router);
app.use(ToastPlugin, {
    position: 'top'
});
app.component('font-awesome-icon', FontAwesomeIcon);

app.mount('#app');
