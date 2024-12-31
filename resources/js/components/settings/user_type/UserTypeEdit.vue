<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header"><h5 class="card-title pt-2 pb-2">User type of {{usertype.type_name}}</h5></div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()" id="userType">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Type name</label>
                            <div :class="['col-md-8', allerros.type_name ? 'has-error' : '']">
                                <input type="text" v-model="usertype.type_name" class="form-control">
                                <span v-if="allerros.type_name" :class="['text-danger']">{{ allerros.type_name[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2 ">security Profile:</label>
                            <div :class="['col-md-8', allerros.security_profile_id ? 'has-error' : '']">
                                <select v-model="usertype.security_profile_id" class="form-control input-sm ">
                                    <option value=""> Select one</option>
                                    <option v-for="item in securityList" v-bind:value="item.id"> {{item.profile_name}}</option>
                                </select>
                                <span v-if="allerros.security_profile_id" :class="['text-danger']">{{ allerros.security_profile_id[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2 ">Auth Token Type:</label>
                            <div :class="['col-md-8', allerros.auth_token_type ? 'has-error' : '']">
                                <select v-model="usertype.auth_token_type" class="form-control input-sm ">
                                    <option value=""> Select one</option>
                                    <option value="mandatory"> Mandatory</option>
                                    <option value="optional"> Optional</option>
                                </select>

                                <span v-if="allerros.auth_token_type" :class="['text-danger']">{{ allerros.auth_token_type[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">DB Access Data:</label>
                            <div :class="['col-md-8', allerros.db_access_data ? 'has-error' : '']">
                                <input type="text" v-model="usertype.db_access_data" class="form-control">
                                <span v-if="allerros.db_access_data" :class="['text-danger']">{{ allerros.db_access_data[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="status" class="col-md-2 required-star">Active Status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="usertype.status" name="status" type="radio"
                                              value="active" id="status">
                                    Active &nbsp;</label>
                                <label><input v-model="usertype.status" name="status" type="radio"
                                                value="inactive" id="status"> Inactive</label>
                                <span v-if="allerros.status"
                                      :class="['text-danger']">{{ allerros.status[0] }}</span>

                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/user-type" class="btn btn-default">Back</router-link>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Update
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import {API} from '../../../custom.js';
    // const customClass = new API();
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                securityList: [],
                usertype: {
                    type_name: '',
                    auth_token_type: '',
                    security_profile_id: '',
                    db_access_data: '',
                    status: '',
                },
            }
        },
        created() {

            let app = this;
            let id = app.$route.params.id;
            app.id = id;
            axios.get('/settings/edit-user-type/' + id)
                .then(function (resp) {
                    app.usertype = resp.data;
                })
                .catch(function () {
                    alert("Could not load your user")
                });

            this.getSecurityList();
        },
        mounted() {
            $(document).ready(function () {
                $("#userType").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
        },

        methods: {
            saveForm() {
                var app = this;
                var data = app.usertype;
                axios.patch('/settings/update-user-type/' + app.id, data)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/user-type');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
            getSecurityList(){
                axios.get('/settings/get-security-list')
                    .then(response => {
                        // alert(555)
                        this.securityList = response.data;
                    }).catch((error)=>{
                    alert('error');
                })

            },
        },
    }
</script>
