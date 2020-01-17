<template>
    <div class="logout">

        <div class="container-fluid" id="app">

            <div class="row">
                <div class="col-12">
                    &nbsp;
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <div class="alert alert-danger" role="alert">
                                <h1>!!! Вы вышли из системы !!!</h1>
                            </div>
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
        name: "logout",
        beforeMount() {
            axios.post('/auth/isauth/logout').then(response => {
               console.log(response.data);
                window.location.href = '/';
            });
        }
    }
</script>

<style scoped>

</style>
