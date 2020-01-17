<template>
    <div class="update">

        <div class="row">
            <div class="col-12">

                <table class="table table-bordered">

                        <thead>

                        <th width="20">ID</th>
                        <th>Name</th>
                        <th>Second Name</th>
                        <th>Phone</th>
                        <th>Comment</th>

                        </thead>

                      <tbody>



                         <tr v-for="i in contact">

                           <td>{{ i.id }}</td>
                           <td class="set" @click="setValue(i.name,'name')">{{ i.name }}</td>
                           <td class="set" @click="setValue(i.second_name,'s_name')">{{ i.second_name }}</td>
                           <td class="set" @click="setValue(i.phone,'phone')">{{ i.phone }}</td>
                           <td class="set" @click="setValue(i.comment,'comment')">{{ i.comment }}</td>

                         </tr>

                      </tbody>


                </table>

            </div>
        </div>

        <div class="row" v-if="!done">
            <div class="col-6 offset-3">

                <form action="" method="post" @submit.prevent="updateContact">

                    <div class="form-group">
                        <label for="name">Name (min 4 / {{ newCont.name.length }}) <span class="req">*</span>:</label>
                        <input type="text" class="form-control" id="name" v-model="newCont.name">
                    </div>

                    <div class="form-group">
                        <label for="second_name">Second Name :</label>
                        <input type="text" class="form-control" id="second_name" v-model="newCont.second_name">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone (len 10 / {{ newCont.phone.length }} ) <span class="req">*</span>:</label>
                        <input type="tel" class="form-control" id="phone" v-model="newCont.phone">
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment :</label>
                        <textarea class="form-control" id="comment"v-model="newCont.comment" rows="3"></textarea>
                    </div>

                    <div class="form-group row" v-if="empty">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <b>Проверьте правильность вводимых значений</b>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Send" v-if="newCont.name.length >= 4 && newCont.phone.length == 10" class="btn btn-primary">
                    <input type="hidden" name="_token">

                </form>

            </div>
        </div>

        <div class="row" v-if="done">
            <div class="col-6 offset-3">

                <div class="alert alert-success" role="alert">
                    Контакт успешно отредактирован
                </div>

            </div>
        </div>

    </div>
</template>

<script>

    import axios from "axios";

    export default {
        name: "update",
        data(){
            return {
                done:false,
                id:this.$route.params.id,
                contact:[],
                newCont:{name:"",second_name:"",phone:"",comment:""},
                empty:false
            }
        },
        methods:{
            updateContact(){
                console.log(this.newCont);
                console.log(this.contact[0].id);

                axios.post(`/contacts/set/update/${this.contact[0].id}`,{
                    userid:this.contact[0].user_id,
                    name:this.newCont.name,
                    second_name:this.newCont.second_name,
                    phone:this.newCont.phone,
                    comment:this.newCont.comment
                }).then(response => {
                    switch (response.data) {
                        case "empty": this.empty = true ;break;
                        case "ok": this.done = true ;break;
                        default: "Something wrong";break;
                    }
                });
            },
            setValue(val,e){
                console.log(val);

                switch (e) {
                    case "name"     : this.newCont.name         = val; break;
                    case "s_name"   : this.newCont.second_name  = val; break;
                    case "phone"    : this.newCont.phone        = val; break;
                    case "comment"  : this.newCont.comment      = val; break;
                }
            }
        },
        beforeMount() {
            axios.post(`/contacts/get/update/${this.id}`).then(response => {
                console.log(response.data);
               this.contact = response.data
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

    .set{
        cursor: pointer;
    }

</style>
