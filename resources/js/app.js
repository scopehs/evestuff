/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import Pusher from "pusher-js";
window.Pusher = Pusher;
import { createApp } from "vue";

import router from "./router";
import store from "@/store.js";
import App from "./views/App.vue";
import { Quasar, Notify, LoadingBar } from "quasar";
import quasarLang from "quasar/lang/en-GB";
import quasarIconSet from "quasar/icon-set/svg-fontawesome-v6";
// import VueGridLayout from "vue-grid-layout";

import "@quasar/extras/fontawesome-v6/fontawesome-v6.css";
import "quasar/src/css/index.sass";
const app = createApp(App);
app.use(router);
// app.use(VueGridLayout);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
app.use(store);
app.provide("can", function (value) {
    if (window.Laravel.jsPermissions == 0) {
        return false;
    }
    let permissions = window.Laravel.jsPermissions.permissions;
    let _return = false;
    if (!Array.isArray(permissions)) {
        return false;
    }
    if (value.includes("|")) {
        value.split("|").forEach(function (item) {
            if (permissions.includes(item.trim())) {
                _return = true;
            }
        });
    } else if (value.includes("&")) {
        _return = true;
        value.split("&").forEach(function (item) {
            if (!permissions.includes(item.trim())) {
                _return = false;
            }
        });
    } else {
        _return = permissions.includes(value.trim());
    }
    return _return;
});

app.use(Quasar, {
    plugins: { Notify, LoadingBar }, // import Quasar plugins and add here
    lang: quasarLang,
    iconSet: quasarIconSet,
    /*
  config: {
    brand: {
      // primary: '#e46262',
      // ... or all other brand colors
    },
    notify: {...}, // default set of options for Notify Quasar plugin
    loading: {...}, // default set of options for Loading Quasar plugin
    loadingBar: { ... }, // settings for LoadingBar Quasar plugin
    // ..and many more (check Installation card on each Quasar component/directive/plugin)
  }
  */
});
app.config.productionTip = false;

app.mount("#app");

// import '@fortawesome/fontawesome-f      ree/css/all.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => app.component(key.split('/').pop().split('.')[0], files(key).default))

// app.component('box', require('./components/Box.vue').default);
