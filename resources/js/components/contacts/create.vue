<template>
    <div class="create">

        <div class="row" v-if="!done">
            <div class="col-6 offset-3">

                <form action="" method="post" @submit.prevent="appendContact">

                    <div class="form-group">
                        <label for="name">Name (min 4 / {{ cont.name.length }}) <span class="req">*</span>:</label>
                        <input type="text" class="form-control" id="name" v-model="cont.name">
                    </div>

                    <div class="form-group">
                        <label for="second_name">Second Name :</label>
                        <input type="text" class="form-control" id="second_name" v-model="cont.secondName">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone (len 10 / {{ cont.phone.length }}) <span class="req">*</span>:</label>
                        <input type="tel" class="form-control" id="phone" v-model="cont.phone">
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment :</label>
                        <textarea class="form-control" id="comment" v-model="cont.comment" rows="3"></textarea>
                    </div>

                    <div class="form-group row" v-if="empty">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <b>Проверьте правильность вводимых значений</b>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" v-if="isset">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <b>Такой номер уже используется</b>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Send" v-if="cont.name.length >= 4 && cont.phone.length == 10" class="btn btn-primary">
                    <input type="hidden" name="_token">

                </form>

            </div>
        </div>

        <div class="row" v-if="done">
            <div class="col-6 offset-3">

                <div class="alert alert-success" role="alert">
                    Контакт успешно добавлен <button class="btn btn-info" @click="done = !done"> Добавить ещё ? </button>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "create",
        data(){
            return {
                isAuth:null,
                userId:null,
                cont:{name:"",secondName:"",phone:"",comment:""},
                empty:false,
                isset:false,
                done:false
            }
        },
        methods:{
            appendContact(){
                console.log(this.userId);
                console.log(this.cont);

                axios.post('/contacts/create',
                    {
                        userid:this.userId,
                        name:this.cont.name,
                        second_name:this.cont.secondName,
                        phone:this.cont.phone,
                        comment:this.cont.comment
                    }
                ).then(response => {
                    console.log(response.data);

                    switch (response.data) {
                        case "empty" :
                            this.empty = true;
                            this.isset = false;
                            break;
                        case "isset" :
                            this.isset = true;
                            this.empty = false;
                            break;
                        case "ok"    :
                            this.empty  = false;
                            this.isset  = false;
                            this.done   = true;
                            // this.cont.name,this.cont.secondName,this.cont.phone,this.cont.comment = "";
                            this.cont.name = "";
                            this.cont.secondName = "";
                            this.cont.phone = "";
                            this.cont.comment = "";
                            break;
                        default: console.log("Something wrong");break
                    }

                });
            }
        },
        beforeMount() {

            axios.post('/auth/isauth').then(response => {

                if (response.data == "ok"){
                    this.isAuth = true;
                    axios.post('/auth/isauth/user').then(response => {
                        this.userId = response.data.id;
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

    .req{
        color: red;
    }

</style>
