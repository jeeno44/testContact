<template>
    <div class="index">

        <div class="container-fluid" id="app">

            <div class="row">
                <div class="col-12" id="header">

                    Header

                </div>
            </div>

            <div class="row">
                <div class="col-3 offset-4">

                    <nav class="navbar navbar-expand-sm bg-light navbar-light">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <router-link class="nav-link" to="/">Главная</router-link>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <router-link class="nav-link" to="/contacts">Мои контакты</router-link>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <router-link class="nav-link" to="/logs">Логи</router-link>
                            </li>
                            <li class="nav-item" v-if="!isAuth">
                                <router-link class="nav-link" to="/register">Регистрация</router-link>
                            </li>
                            <li class="nav-item" v-if="!isAuth">
                                <router-link class="nav-link" to="/login">Войти</router-link>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <router-link class="nav-link" to="/logout">Выйти</router-link>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>

            <div class="row">
                <div class="col-2" id="left">Left</div>
                <div class="col-8" id="center">

                    <div class="row">
                        <div class="col-12">&nbsp;</div>
                    </div>

                    <div class="row">
                        <div class="col-10 offset-1">
                            <div class="alert alert-success" role="alert">
                                <h3>Главная страница</h3>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-2" id="right">Right</div>
            </div>

            <div class="row">
                <div class="col-12" id="footer">Footer</div>
            </div>


        </div>

    </div>
</template>

<script>

    import Vue from "vue";
    import axios from "axios";

    import VueRouter from "vue-router";
    Vue.use(VueRouter);
    import register from "./auth/register";
    import login from "./auth/login";
    import logout from "./auth/logout";
    import contacts from "./contacts/contacts";
    import show from "./contacts/show";
    import create from "./contacts/create";
    import update from "./contacts/update";
    import search from "./contacts/search";
    import logs from "./logs/logs";

    const routes = [
        { path: '/', component: this },
        { path: '/register', component: register },
        { path: '/login', component: login },
        { path: '/logout', component: logout },
        { path: '/logs', component: logs },
        { path: '/contacts', component: contacts,
            children: [
                { path: 'show', component: show },
                { path: 'create', component: create },
                { path: 'update/:id', component: update },
                { path: 'search', component: search },
            ]},
    ];

    const router = new VueRouter({
        routes,
        // mode: 'history'
    });

    export default {
        name: "index",
        data(){
            return {
                isAuth:null
            }
        },
        beforeMount() {

            axios.post('/auth/isauth',{}).then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;
                }
                else{
                    this.isAuth = false;
                }

            });

        }
    }
</script>

<style>

    #header{
        /*background-color: #ddfeea;*/
        height: 200px;
    }

    #left{
        /*background-color: #fdfecc;*/
        height: 800px;
    }

    #center{
        /*background-color: #f5defe;*/
        height: 800px;
    }

    #right{
        /*background-color: #feb1af;*/
        height: 800px;
    }

    #footer{
        /*background-color: #7ffee6;*/
        height: 50px;
    }

</style>
