import Vue from "vue";

Vue.config.productionTip = false;

import VueRouter from "vue-router";
Vue.use(VueRouter);
import index from "./components/index";
import register from "./components/auth/register";
import login from "./components/auth/login";
import logout from "./components/auth/logout";

import contacts from "./components/contacts/contacts";
import show from "./components/contacts/show";
import create from "./components/contacts/create";
import update from "./components/contacts/update";
import search from "./components/contacts/search";
import logs from "./components/logs/logs";

const routes = [
    { path: '/', component: index },
    { path: '/register', component: register },
    { path: '/login', component: login },
    { path: '/logout', component: logout },
    { path: '/contacts', component: contacts,
        children: [
            { path: 'show', component: show },
            { path: 'create', component: create },
            { path: 'update/:id', component: update },
            { path: 'search', component: search },
            ]},
    {path: '/logs', component: logs },
];

const router = new VueRouter({
    routes,
    // mode: 'history'
});



new Vue({
    el:'#app',
    router
 }).$mount("#app");
