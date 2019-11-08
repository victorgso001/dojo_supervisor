import bearer from '@websanova/vue-auth/drivers/auth/bearer';
import axios from '@websanova/vue-auth/drivers/http/axios.1.x';
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x';

const config = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'dojo-jkc-spa',
    tokenStore: ['localStorage'],
    rolesVar: 'role',
    loginData: {
        url: '/auth/login',
        method: 'POST',
        redirect: '/login'
    },
}

export default config;
