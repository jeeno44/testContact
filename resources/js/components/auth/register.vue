<template>
    <div class="register">

        <div class="container">

            <div class="row">
                <div class="col-10 offset-1">

                    <nav class="navbar navbar-expand-sm bg-light navbar-light">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="/">Главная</a>
                            </li>
                            <li class="nav-item" v-if="!isAuth">
                                <a class="nav-link" href="#/register">Регистрация</a>
                            </li>
                            <li class="nav-item" v-if="!isAuth">
                                <a class="nav-link" href="#/login">Войти</a>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <a class="nav-link" href="#/logout">Выйти</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>

            <div class="row">
                <div class="col-12">&nbsp;</div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Регистрация</div>

                        <div class="card-body">
                            <form method="POST" action="" @submit.prevent="sendRegister">

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" v-model="name"
                                               required autocomplete="name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">
                                        E-Mail адрес <span class="req">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" v-model="email"
                                              required autocomplete="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">
                                        Пароль (min 4 / {{ password.length }})  <span class="req">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" v-model="password"
                                               required autocomplete="new-password">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                        Повтор пароля (min 4 / {{ repassword.length }}) <span class="req">*</span>
                                    </label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               v-model="repassword" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="isset">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="alert alert-danger" role="alert">
                                            Пользователь уже существует
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0" v-if="valideForm">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "register",
        data(){
            return {
                isAuth:null,
                name:"",
                email:"",
                password:"",
                repassword:"",
                isset:false
            }
        },
        methods:{
            sendRegister(){
                // Если все данные в порядке отправляем post запрос и добавляем нового пользователя.
                axios.post('/auth/register',{name:this.name,email:this.email,password:this.password}).then(response => {

                    if(response.data == "user isset"){
                        this.isset = true;
                        this.name = "";
                        this.email = "";
                        this.password = "";
                        this.repassword = "";
                    }
                    else {
                        window.location.href = '#/contacts';
                    }

                });
            }
        },
        computed:{
            valideForm(){

                if (this.name.length >= 4 && this.email.length >= 5 && this.password.length >= 4 && this.password == this.repassword){
                    return true;
                }
                else{
                    return false;
                }

            }
        },
        beforeMount() {

            axios.post('/auth/isauth',{}).then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;
                    window.location.href = '/';
                }
                else{
                    this.isAuth = false;
                }

            });
        }
    }
</script>

<style>

    .req{
        color: red;
    }

</style>
