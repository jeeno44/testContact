<template>
    <div class="login">

        <div class="container">

            <div class="row">
                <div class="col-10 offset-1">

                    <nav class="navbar navbar-expand-sm bg-light navbar-light">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="/">Главная</a>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <a class="nav-link" href="#/contacts">Мои контакты</a>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <a class="nav-link" href="#/logs">Логи</a>
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
                        <div class="card-header">Войти</div>

                        <div class="card-body">
                            <form method="POST" action="" @submit.prevent="getLogin">
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail адрес <span class="req">*</span></label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" v-model="email" required autocomplete="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль <span class="req">*</span></label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" v-model="password" required autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                            <label class="form-check-label" for="remember">
                                                Запомнить меня
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="errorEnter">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="alert alert-danger" role="alert">
                                            Неправильные логин или пароль
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Войти
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</template>

<script>

    import axios from "axios";

    export default {
        name: "login",
        data(){
            return {
                isAuth:null,
                email:"",
                password:"",
                errorEnter:false
            }
        },
        methods:{
            getLogin(){
                console.log(this.email);
                console.log(this.password);

                axios.post('/auth/login',{email:this.email,password:this.password}).then(response => {

                    if (response.data == "not match"){
                        // Выводим предупреждение о неправильности пароля и очищаем поля ввода
                        this.errorEnter = true;
                        this.email = "";
                        this.password = "";
                    }
                    else{
                        // если данные верны редиректим на страницу с контактами
                        window.location.href = '#/contacts';
                    }

                });
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
