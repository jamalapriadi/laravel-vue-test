
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
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import Spinner from 'vue-simple-spinner';
import BootstrapVue from 'bootstrap-vue';
import Vuelidate from 'vuelidate';
import Autocomplete from 'v-autocomplete'
import 'v-autocomplete/dist/v-autocomplete.css'
import ToggleButton from 'vue-js-toggle-button'
import Snotify from 'vue-snotify';


Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(Spinner);
Vue.use(BootstrapVue);
Vue.use(Vuelidate);
Vue.use(Autocomplete)
Vue.use(ToggleButton)
Vue.use(Snotify)

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
    { path: '/users', name: 'users-index', component: require('./components/users/Index.vue').default },
    { path: '/users/create', name: 'users-add', component: require('./components/users/Create.vue').default },
    { path: '/users/:id', name: 'users-view', component: require('./components/users/View.vue').default },
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
    { path: '/bank', name: 'bankIndex', component: require('./components/master/bank/Index.vue').default },
    { path: '/add-bank', name: 'bankCreate', component: require('./components/master/bank/Create.vue').default },
    { path: '/edit-bank/:id', name: 'bankEdit', component: require('./components/master/bank/Edit.vue').default },
    { path: '/kelompok', name: 'kelompokIndex', component: require('./components/master/kelompok/Index.vue').default },
    { path: '/add-kelompok', name: 'kelompokCreate', component: require('./components/master/kelompok/Create.vue').default },
    { path: '/edit-kelompok/:id', name: 'kelompokEdit', component: require('./components/master/kelompok/Edit.vue').default },
    { path: '/kota', name: 'kotaIndex', component: require('./components/master/kota/Index.vue').default },
    { path: '/add-kota', name: 'kotaCreate', component: require('./components/master/kota/Create.vue').default },
    { path: '/edit-kota/:id', name: 'kotaEdit', component: require('./components/master/kota/Edit.vue').default },
    { path: '/lokasi', name: 'lokasiIndex', component: require('./components/master/lokasi/Index.vue').default },
    { path: '/add-lokasi', name: 'lokasiCreate', component: require('./components/master/lokasi/Create.vue').default },
    { path: '/edit-lokasi/:id', name: 'lokasiEdit', component: require('./components/master/lokasi/Edit.vue').default },
    { path: '/merk', name: 'merkIndex', component: require('./components/master/merk/Index.vue').default },
    { path: '/add-merk', name: 'merkCreate', component: require('./components/master/merk/Create.vue').default },
    { path: '/edit-merk/:id', name: 'merkEdit', component: require('./components/master/merk/Edit.vue').default },
    { path: '/perusahaan', name: 'perusahaanIndex', component: require('./components/master/perusahaan/Index.vue').default },
    { path: '/add-perusahaan', name: 'perusahaanCreate', component: require('./components/master/perusahaan/Create.vue').default },
    { path: '/edit-perusahaan/:id', name: 'perusahaanEdit', component: require('./components/master/perusahaan/Edit.vue').default },
    { path: '/sales', name: 'salesIndex', component: require('./components/master/sales/Index.vue').default },
    { path: '/add-sales', name: 'salesCreate', component: require('./components/master/sales/Create.vue').default },
    { path: '/edit-sales/:id', name: 'salesEdit', component: require('./components/master/sales/Edit.vue').default },
    { path: '/program', name: 'programIndex', component: require('./components/master/program/Index.vue').default },
    { path: '/add-new-program', name: 'programCreate', component: require('./components/master/program/Create.vue').default },
    { path: '/edit-program/:id', name: 'programEdit', component: require('./components/master/program/Edit.vue').default },
    { path: '/program-detail/:id', name: 'programDetail', component: require('./components/master/program/Detail.vue').default},
    { path: '/rak', name: 'rakIndex', component: require('./components/master/rak/Index.vue').default },
    { path: '/add-rak', name: 'rakCreate', component: require('./components/master/rak/Create.vue').default },
    { path: '/edit-rak/:id', name: 'rakEdit', component: require('./components/master/rak/Edit.vue').default },
    { path: '/import-rak', name:'rakImport', component: require('./components/master/rak/Import.vue').default},
    { path: '/suplier', name: 'suplierIndex', component: require('./components/master/suplier/Index.vue').default },
    { path: '/add-suplier', name: 'suplierCreate', component: require('./components/master/suplier/Create.vue').default },
    { path: '/edit-suplier/:id', name: 'suplierEdit', component: require('./components/master/suplier/Edit.vue').default },
    { path: '/customer', name: 'customerIndex', component: require('./components/master/customer/Index.vue').default },
    { path: '/add-customer', name: 'customerCreate', component: require('./components/master/customer/Create.vue').default },
    { path: '/edit-customer/:id', name: 'customerEdit', component: require('./components/master/customer/Edit.vue').default },
    { path: '/barang', name: 'barangIndex', component: require('./components/master/barang/Index.vue').default },
    { path: '/add-barang', name: 'barangCreate', component: require('./components/master/barang/Create.vue').default },
    { path: '/edit-barang/:id', name: 'barangEdit', component: require('./components/master/barang/Edit.vue').default },
    { path: '/import-barang', name: 'importBarang', component: require('./components/master/barang/ImportBarang.vue').default },
    { path: '/import-update-barang', name: 'updateImportBarang', component: require('./components/master/barang/ImportUpdateBarang.vue').default},

    { path: '/po', name: 'poIndex', component: require('./components/master/po/Index.vue').default},
    { path: '/add-new-po', name: 'poCreate', component: require('./components/master/po/Create.vue').default },
    { path: '/po-detail/:id', name: 'poDetail', component: require('./components/master/po/Detail.vue').default},

    { path : '/list-picking', name:'pickingIndex', component: require('./components/picking/Index').default},
    { path: '/add-new-picking', name:'pickingCreate', component: require('./components/picking/Create.vue').default},
    { path: '/picking-detail/:id', name: 'pickingDetail', component: require('./components/picking/Detail.vue').default},

    { path: '/add-new-order', name: 'addOrderIndex', component: require('./components/order/Create.vue').default },
    { path: '/list-order', name: 'listOrder', component: require('./components/order/List.vue').default },
    { path: '/order-detail/:id', name: 'orderDetail', component: require('./components/order/Detail.vue').default},

    { path: '/storing', name:'storeIndex', component: require('./components/storing/Index.vue').default},
    { path: '/add-new-storing', name: 'storingCreate', component: require('./components/storing/Create.vue').default },
    { path: '/storing-detail/:id', name: 'storingDetail', component: require('./components/storing/Detail.vue').default},

    { path: '/list-mutasi', name: 'listMutasi', component: require('./components/mutasi/Index.vue').default },
    { path: '/add-new-mutasi', name: 'mutasiCreate', component: require('./components/mutasi/Create.vue').default },
    { path: '/mutasi-detail/:id', name: 'mutasiDetail', component: require('./components/mutasi/Detail.vue').default},

    { path: '/list-piutang', name: 'listPiutang', component: require('./components/piutang/list.vue').default },
    { path: '/add-new-piutang', name: 'piutangCreate', component: require('./components/piutang/create.vue').default },
    { path: '/piutang-detail/:id', name: 'piutangDetail', component: require('./components/piutang/detail.vue').default},

    { path: '/list-retur', name: 'listRetur', component: require('./components/retur/list.vue').default },
    { path: '/add-new-retur', name: 'returCreate', component: require('./components/retur/create.vue').default },
    { path: '/retur-detail/:id', name: 'returDetail', component: require('./components/retur/detail.vue').default},

    { path: '/het', name:'hetForm', component: require('./components/het.vue').default},

    { path: '/storing-retur', name:'storeReturIndex', component: require('./components/storingretur/list.vue').default},
    { path: '/add-new-storing-retur', name: 'storingReturCreate', component: require('./components/storingretur/create.vue').default },
    { path: '/storing-detail-retur/:id', name: 'storingReturDetail', component: require('./components/storingretur/detail.vue').default},

    { path: '/order-jatuh-tempo', name:'orderJatuhTempoIndex', component: require('./components/order/orderjatuhtempo.vue').default},
    { path: '/po-detail-status/:id', name:'poDetailStatus', component: require('./components/master/po/detail-status.vue').default}
]

const router = new VueRouter({ routes });

const app = new Vue({
    linkExactActiveClass: 'active',
    router
}).$mount("#app");
