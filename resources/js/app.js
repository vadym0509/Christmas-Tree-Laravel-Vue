/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./site');
require('bootstrap-datepicker');

import VueToastr from 'vue-toastr';
import store from './store/index';
import Lingallery from 'lingallery';

window.Vue = require('vue');
Vue.use(VueToastr);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
Vue.component('lingallery', Lingallery);
Vue.component('session-message', require('./components/SessionMessage.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('shopping-cart-panel', require('./components/ShoppingCartPanel.vue').default);
Vue.component('shopping-cart-panel-item', require('./components/ShoppingCartPanelItem.vue').default);
Vue.component('product', require('./components/Product.vue').default);
Vue.component('shopping-cart', require('./components/ShoppingCart.vue').default);
Vue.component('shopping-cart-tile', require('./components/ShoppingCartTile.vue').default);
Vue.component('currency-select', require('./components/CurrencySelect.vue').default);
Vue.component('login-button', require('./components/Login/LoginButton.vue').default);
Vue.component('login-panel', require('./components/Login/LoginPanel.vue').default);
Vue.component('steps-form', require('./components/StepsForm.vue').default);
Vue.component('dropdown-product-variation', require('./components/DropdownProductVariation.vue').default);
Vue.component('nav-right-button', require('./components/NavRightButton').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    store
});
