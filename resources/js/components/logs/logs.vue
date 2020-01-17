<template>
    <div class="logs">

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
                            <li class="nav-item" v-if="isAuth">
                                <router-link class="nav-link" to="/contacts">Мои контакты</router-link>
                            </li>
                            <li class="nav-item active" v-if="isAuth">
                                <router-link class="nav-link" to="/logs">Логи</router-link>
                            </li>
                            <li class="nav-item" v-if="isAuth">
                                <router-link class="nav-link" to="#/logout">Выйти</router-link>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>

            <div class="row">
                <div class="col-2" id="left">Left</div>
                <div class="col-8" id="center">

                    <table class="table table-bordered">

                          <thead>

                             <th>ID</th>
                             <th>Event</th>
                             <th>Status</th>
                             <th>DateTime</th>

                          </thead>

                          <tbody>

                             <tr v-for="i in myLogs">

                               <td class="centr">{{ i.id }}</td>
                               <td>{{ i.event }}</td>
                               <td class="centr">{{ i.result }}</td>
                               <td class="date">{{ i.created_at }}</td>

                             </tr>

                          </tbody>
                    </table>


                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item" >
                                <button class="page-link" @click="getPrevPage" :disabled="!pages.prev_page">prev</button>
                            </li>
                            <li class="page-item" v-for="i in pages.count_page"><button class="page-link" @click="getPage(i)"> {{ i }} </button></li>
                            <li class="page-item">
                                <button class="page-link" @click="getNextPage" :disabled="!pages.next_page">next</button>
                            </li>
                        </ul>
                    </nav>

                    <hr>

                    <button class="btn btn-info" @click="exportLogs"> Export to CSV </button>

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

    export default {
        name: "logs",
        data(){
            return {
                isAuth:null,
                myLogs:[],
                pages:{current_page:"",prev_page:"",next_page:"",count_page:""},
                url:"/logs/show",
            }
        },
        methods:{
            getPrevPage(){

                // Метод перелистывания на предыдущую страницу

                axios.post(this.pages.prev_page).then(response => {
                    this.myLogs = response.data.data;
                    this.pages.next_page = response.data.next_page_url;
                    if (response.data.prev_page_url == null){
                        this.pages.prev_page = false;
                    }
                    else {
                        this.pages.prev_page = response.data.prev_page_url;
                    }
                    this.pages.current_page = response.data.current_page;
                });

            },
            getPage(val){

                // Метод перелистывания на указанную предыдущую страницу

                axios.post(`${this.url}?page=${val}`).then(response => {
                    this.myLogs = response.data.data;
                    this.pages.prev_page = response.data.prev_page_url;
                    this.pages.next_page = response.data.next_page_url;
                    this.pages.current_page = response.data.current_page;
                });
            },
            getNextPage(){

                axios.post(this.pages.next_page).then(response => {
                    this.myLogs = response.data.data;
                    this.pages.next_page = response.data.next_page_url;
                    if (response.data.prev_page_url == null){
                        this.pages.prev_page = false;
                    }
                    else {
                        this.pages.prev_page = response.data.prev_page_url;
                    }
                    this.pages.current_page = response.data.current_page;
                });
            },
            exportLogs(){
                console.log("Export LOGS");

                axios.post('/logs/export/',{}).
                then(response => {
                    console.log(response.data);

                    let arrs = [];

                    for (const i in response.data){
                        console.log(response.data[i]);
                        arrs.push({"id":response.data[i].id,"event":response.data[i].event});
                    }

                    console.log(arrs);

                    // const url = window.URL.createObjectURL(new Blob([response.data]));
                    const url = window.URL.createObjectURL(new Blob(
                        [JSON.stringify(response.data, null, 2)],
                        {type: "application/json;charset=utf-8"}).slice(2,-1));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'file.csv');
                    document.body.appendChild(link);
                    link.click();

                });

            },

        },
        beforeMount() {

            axios.post('/auth/isauth').then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;

                    axios.post('/logs/show').then(response => {
                        console.log(response.data);
                        this.myLogs = response.data.data;
                        this.pages.next_page = response.data.next_page_url;
                        this.pages.count_page = response.data.last_page;
                        if (response.data.current_page == 1){
                            this.pages.prev_page = false;
                        }
                        this.pages.current_page = response.data.current_page;
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

    th{
        text-align: center;
    }

    .centr{
        width: 20px;
        text-align: center;
    }

    .date{
        width: 200px;
        text-align: center;
    }

</style>
