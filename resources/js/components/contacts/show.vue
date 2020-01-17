<template>
    <div class="show">

        <div class="container-fluid" id="app">

            <div class="row">
                <div class="col-12">

                    <table class="table table-bordered" v-if="myConts.length > 0">

                          <thead>

                             <th>ID</th>
                             <th>Name</th>
                             <th>Senond Name</th>
                             <th>Phone</th>
                             <th>Comment</th>
                             <th>Edit</th>
                             <th>Delete</th>

                          </thead>

                          <tbody>

                             <tr v-for="cont in myConts">

                               <td class="centr">{{ cont.id }}</td>
                               <td>{{ cont.name }}</td>
                               <td>{{ cont.second_name }}</td>
                               <td class="tel">{{ cont.phone }}</td>
                               <td>{{ cont.comment }}</td>
                               <td class="centr"><router-link :to="`/contacts/update/${cont.id}`"><img src="/src/img/edit2.ico" width="16" height="16"></router-link></td>
                               <td class="centr"><img src="/src/img/remove.png" width="16" height="16" @click="removeContact(cont.id)"></td>

                             </tr>

                          </tbody>


                    </table>
                    <div class="row" v-if="myConts.length == 0">
                        <div class="col-8 offset-2">

                            <div class="alert" role="alert">
                                <h2><i>Список контактов пуст</i></h2>
                            </div>

                        </div>
                    </div>

                    <nav aria-label="Page navigation example" v-if="myConts.length > 0">
                        <ul class="pagination">
                            <li class="page-item" disabled="">
                                <button class="page-link" @click="getPrevPage" :disabled="!pages.prev_page">prev</button>
                            </li>
                            <li class="page-item" v-for="i in pages.count_page"><button class="page-link" @click="getPage(i)"> {{ i }} </button></li>
                            <li class="page-item">
                                <button class="page-link" @click="getNextPage" :disabled="!pages.next_page">next</button>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>




        </div>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "show",
        data(){
            return {
                isAuth:null,
                myConts:[],
                pages:{current_page:"",prev_page:"",next_page:"",count_page:""},
                url:"/contacts/show"
            }
        },
        methods:{
            getPrevPage(){

                // Метод перелистывания на предыдущую страницу

                axios.post(this.pages.prev_page).then(response => {
                    this.myConts = response.data.data;
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
                   this.myConts = response.data.data;
                   this.pages.prev_page = response.data.prev_page_url;
                   this.pages.next_page = response.data.next_page_url;
                   this.pages.current_page = response.data.current_page;
                });

            },
            getNextPage(){

                // Метод перелистывания на следующую страницу

                axios.post(this.pages.next_page).then(response => {
                    this.myConts = response.data.data;
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
            removeContact(val){

                // Метод удаления контакта

                axios.post(`/contacts/remove/${val}`,{current_page:this.pages.current_page}).then(response => {
                    this.myConts = response.data.data;
                    this.pages.next_page = response.data.next_page_url;
                    this.pages.count_page = response.data.last_page;
                    if (response.data.current_page == 1){
                        this.pages.prev_page = false;
                    }
                    this.pages.current_page = response.data.current_page
                });
            }
        },
        beforeMount() {

            // Первоначальная загрузка всех контактов

            axios.post('/auth/isauth').then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;
                    axios.post('/contacts/show').then(response => {
                        this.myConts = response.data.data;
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

    img{
        cursor: pointer;
    }

    .centr{
        width: 20px;
        text-align: center;
    }

    .tel{
        text-align: center;
        width: 200px;
    }

</style>
