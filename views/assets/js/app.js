
window.Vue = require('vue');
// require('./plugins/vue-particles');

import router from "./router";
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import App from '@/assets/js/App.vue';
import VueConfirmDialog from 'vue-confirm-dialog'
// import { BCollapse } from 'bootstrap-vue'
// Vue.component('b-collapse', BCollapse)
import VueMeta from 'vue-meta'
Vue.use(VueMeta)
import Vue2TinymceEditor from "vue2-tinymce-editor";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'
import 'vue-anka-cropper/dist/VueAnkaCropper.css';


import "@/assets/css/main.css";
import "@/assets/sass/app.scss";
Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueAxios, axios)
Vue.use(VueConfirmDialog);
Vue.use( Vue2TinymceEditor );


var VueCookie = require('vue-cookie');
Vue.use(VueCookie);

Vue.component('vue-confirm-dialog', VueConfirmDialog.default)




// const files = require.context('./plugin', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    router,
    render: h => h(App)
}).$mount("#app");
