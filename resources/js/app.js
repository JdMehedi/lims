require('./bootstrap');
global.jQuery = require('jquery');
var $ = global.jQuery;
window.$ = $;


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { createApp } from 'vue';

let app = createApp({
    // Get auth data, this state will be triggerd when user logged in
    created() {
        this.$store.dispatch("getAuthenticatedUserData")
    }
});


// Register Vuex and global store
import store from './store/store';
import vSelect from 'vue-select'
import "vue-select/dist/vue-select.css";
app.use(store);
app.component(vSelect);

// Register global Mixin files (available in all components only)
import AuthHelper from './mixins/AuthHelper';
app.mixin(AuthHelper)


// User router
import router from './routes'
app.use(router);


// Use v-pagination-3
import Pagination from 'v-pagination-3';
app.component('Pagination', Pagination)


// use VueToast
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
app.use(VueToast, {
    position: 'top-right',
    duration: 3000
});

app.mount('#app')
