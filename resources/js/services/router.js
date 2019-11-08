import VueRouter from 'vue-router';

import Home from '../pages/Home';
import Login from '../pages/Login';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined,
        }
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: undefined,
        }
    }
];

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});

export default router;
