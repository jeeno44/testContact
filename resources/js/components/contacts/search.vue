<template>
    <div class="search">

        <div class="container-fluid" id="app">

            <div class="row">
                <div class="col-6 offset-3">

                    <form method="post">

                        <div class="form-group"@submit.prevent="">
                            <label for="search">Search :</label>
                            <input type="text" class="form-control" id="search" v-model="search" @keyup="searchContact">
                        </div>

                        <input type="submit" value="Send" class="btn btn-primary">
                        <input type="hidden" name="_token">

                    </form>

                </div>
            </div>

            <hr>

            <table class="table table-bordered" v-if="result.length > 0">

                  <thead>

                     <th>ID</th>
                     <th>Name</th>
                     <th>Second Name</th>
                     <th>Phone</th>
                     <th>Comment</th>

                  </thead>

                  <tbody>

                     <tr v-for="i in result">

                       <td class="centr">{{ i.id }}</td>
                       <td>{{ i.name }}</td>
                       <td>{{ i.second_name }}</td>
                       <td class="tel">{{ i.phone }}</td>
                       <td>{{ i.comment }}</td>

                     </tr>

                  </tbody>


            </table>
            <div class="alert alert-danger" role="alert" v-if="result == '' && search.length > 0">
                    <h3><i>Совпадений не найдено...</i></h3>
            </div>

        </div>

    </div>
</template>

<script>
    import axios from "axios";

    import Vue from "vue";
    import VueRouter from "vue-router";
    Vue.use(VueRouter);
    import index from "../index";
    import show from "./show";
    import create from "./create";
    import update from "./update";

    const routes = [
        {path: '/', component: index },
        {path: '/contacts/show', component: show },
        {path: '/contacts/create', component: create },
        {path: '/contacts/update/:id', component: update },
    ];

    const router = new VueRouter({
        routes,
        // mode: 'history'
    });

    export default {
        name: "search",
        router,
        data(){
            return {
                isAuth:null,
                search:"",
                result:""
            }
        },
        methods:{
            searchContact(){
                console.log(this.search);

                if (this.search.length > 0){
                    axios.post('/contacts/search',{search:this.search}).then(response => {
                        console.log(response.data);
                        this.result = response.data;
                    });
                }
                else {
                    this.result = "";
                }

            }
        },
        beforeMount() {

            axios.post('/auth/isauth').then(response => {

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

    .centr{
        width: 20px;
        text-align: center;
    }

    .tel{
        width: 200px;
        text-align: center;
    }

    th{
        text-align: center;
    }

</style>
