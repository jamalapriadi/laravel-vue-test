
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueRouter);
Vue.use(VueSweetalert2);

//define routes for users
const routes = [
    {
        path: '/',
        name: 'homeIndex',
        component: require('./components/Home.vue').default
    },
    {
        path: '/profile',
        name: 'profileIndex',
        component: require('./components/Profile.vue').default
    },
    {
        path: '/password',
        name: 'password',
        component: require('./components/Password.vue').default
    },
    {
        path: '/example',
        name: 'example',
        component: require('./components/ExampleComponent.vue').default
    },
    {
        path: '/users',
        name: 'users-index',
        component: require('./components/users/Index.vue').default
    },
    {
        path: '/users/create',
        name: 'users-add',
        component: require('./components/users/Create.vue').default
    },
    {
        path: '/users/:id',
        name: 'users-view',
        component: require('./components/users/View.vue').default
    },
    {
        path: '/role',
        name: 'role-index',
        component: require('./components/role/RoleIndex.vue').default
    },
    {
        path: '/role/create',
        name: 'role-add',
        component: require('./components/role/RoleCreate.vue').default
    },
    {
        path: '/role/:id',
        name: 'role-view',
        component: require('./components/role/RoleView.vue').default
    },
    {
        path: '/bank',
        name: 'bankIndex',
        component: require('./components/master/bank/Index.vue').default
    },
    {
        path: '/add-bank',
        name: 'bankCreate',
        component: require('./components/master/bank/Create.vue').default
    },
    {
        path: '/edit-bank/:id',
        name: 'bankEdit',
        component: require('./components/master/bank/Edit.vue').default
    },
    {
        path: '/kelompok',
        name: 'kelompokIndex',
        component: require('./components/master/kelompok/Index.vue').default
    },
    {
        path: '/kota',
        name: 'kotaIndex',
        component: require('./components/master/kota/Index.vue').default
    },
    {
        path: '/lokasi',
        name: 'lokasiIndex',
        component: require('./components/master/lokasi/Index.vue').default
    },
    {
        path: '/merk',
        name: 'merkIndex',
        component: require('./components/master/merk/Index.vue').default
    },
    {
        path: '/perusahaan',
        name: 'perusahaanIndex',
        component: require('./components/master/perusahaan/Index.vue').default
    },
    {
        path: '/program',
        name: 'programIndex',
        component: require('./components/master/program/Index.vue').default
    },
    {
        path: '/rak',
        name: 'rakIndex',
        component: require('./components/master/rak/Index.vue').default
    },
    {
        path: '/sales',
        name: 'salesIndex',
        component: require('./components/master/sales/Index.vue').default
    },
    {
        path: '/suplier',
        name: 'suplierIndex',
        component: require('./components/master/suplier/Index.vue').default
    },
    {
        path: '/add-new-order',
        name: 'addOrderIndex',
        component: require('./components/order/Create.vue').default
    },
    {
        path: '/list-order',
        name: 'listOrder',
        component: require('./components/order/List.vue').default
    }
]

const router = new VueRouter({ routes });

const app = new Vue({
    linkExactActiveClass: 'active',
    router
}).$mount("#app");
