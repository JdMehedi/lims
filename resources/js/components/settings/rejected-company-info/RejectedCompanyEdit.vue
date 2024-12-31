<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">Company Detail of :{{company.company_name}}</div>
            <div class="panel-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">Company Name : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.company_name}} </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">Country Name : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.country}} </div>
                        </div>
                        <div id="state">
                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">State Name : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.state}} </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">Provice Name : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.province}} </div>
                        </div>
                        </div>
                        <div id="division">
                            <div class="form-group col-md-12">
                                <label class="col-md-offset-4 col-md-4 col-md-offset-4">Division Name : </label>
                                <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.company_division}} </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-offset-4 col-md-4 col-md-offset-4">District Name : </label>
                                <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.company_district}} </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-md-offset-4 col-md-4 col-md-offset-4">Thana Name : </label>
                                <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.company_thana}} </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">Status : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">
                                <span v-if="company.company_status == 1" >Approved</span>
                                <span v-else >Rejected</span></div>

                        </div>

                        <div class="form-group col-md-12">
                            <label class="col-md-offset-4 col-md-4 col-md-offset-4">Create By : </label>
                            <div class="col-md-offset-4 col-md-4 col-md-offset-4">{{company.created_at}} </div>
                        </div>


                        <div class="col-md-12">
                            <div class="pull-left">Last update At {{company.updated_at}}  </div>
                            <router-link to="/rejected-company" class="btn btn-default pull-right"><i class="fa fa-remove"></i>Close</router-link>
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
                company: [],
            }

        },

        mounted() {
            // customClass.onlyNumber();
            // customClass.isEmail();
        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            console.log(id);
            app.$id = id;
            axios.get('/settings/company-info-edit/' + id)
                .then(function (resp) {
                    app.company = resp.data;
                    console.log(app.company);
                    var type = app.company.country_id;
                    if (type == 18) {
                        $('#division').show();
                        $('#state').hide();
                    } else{
                        $('#division').hide();
                        $('#state').show();
                    }
                })
                .catch(function () {
                    alert("Could not load your Company")
                });
        },
        methods: {
            // saveForm() {
            //     var app = this;
            //     var newCompany = app.area;
            //     axios.patch('/settings/update-area/' + app.$id, newCompany)
            //         .then(function (resp) {
            //             if (resp.data.status === true) {
            //                 app.$toaster.success('Your data update successfully.');
            //                 app.$router.replace('/area-list');
            //             }
            //         }).catch((error) => {
            //         this.allerros = error.response.data.errors;
            //     });
            // },

        }
    }
</script>
