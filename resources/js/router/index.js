import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Dashboard from '../pages/Dashboard.vue';
import TaskList from '../pages/TaskList.vue';
import { getUser } from '../auth';

const routes = [
    { path: '/login', component: Login, meta: { guestOnly: true } },
    { path: '/register', component: Register, meta: { guestOnly: true } },
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/lists', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/lists/:listId/tasks', component: () => import('../pages/TaskList.vue'), meta: { requiresAuth: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const user = await getUser();

    if (to.meta.requiresAuth && !user) {
        return next('/login');
    }

    if (to.meta.guestOnly && user) {
        return next('/');
    }

    next();
});

export default router;
