<template>
    <div class="contacts">

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
                            <li class="nav-item">
                                <router-link class="nav-link" to="/">Главная</router-link>
                            </li>
                            <li class="nav-item active" v-if="isAuth">
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
                        <div class="col-3 offset-1">
                            <router-link to="/contacts/show" class="btn btn-info">Показать контакты</router-link>
                        </div>
                        <div class="col-3">
                            <router-link to="/contacts/create" class="btn btn-success">Добавить контакт</router-link>
                        </div>
                        <div class="col-3">
                            <router-link to="/contacts/search" class="btn btn-primary">Поискъ контакта</router-link>
                        </div>
                    </div>

                    <hr>

                    <router-view></router-view>

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
    import axios from "axios";

    import show from "./show";
    import create from "./create";
    import update from "./update";
    import search from "./search";

    import VueRouter from "vue-router";
    import Vue from "vue";
    Vue.use(VueRouter);

    const routes = [
        {path: '/contacts/show', component: show },
        {path: '/contacts/create', component: create },
        {path: '/contacts/update/:id', component: update },
        {path: '/contacts/search', component: search },
    ];

    const router = new VueRouter({
        routes,
        // mode: 'history'
    });



    export default {
        name: "contacts",
        // router,
        data(){
            return {
                isAuth:null,
                userId:null,
            }
        },
        methods:{
            showNotes(){
                // console.log(this.userId);
                // console.log(this.myConts);
            }
        },
        beforeMount() {

            axios.post('/auth/isauth').then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;

                    axios.post('/auth/isauth/conts').then(response => {
                       this.userId = response.data[0].id;
                       if (response.data[1] != "empty"){
                           this.myConts = response.data[1]
                       }
                    });

                }
                else{
                    this.isAuth = false;
                    window.location.href = '/';
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
