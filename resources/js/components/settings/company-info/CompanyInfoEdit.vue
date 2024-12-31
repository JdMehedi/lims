<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">Company Detail of :{{company.company_name}}</div>
            <div class="panel-body">
                <div class="col-md-9">
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

                </div>
            </div>
        </div>

                <div class="col-md-12">
                    <router-link to="/company-info" class="btn btn-default pull-left"><i class="fa fa-remove"></i>Close</router-link>
                    <div v-if="company.is_approved == 0"  class="pull-right">
                        <button class="btn btn-success" v-on:click="approved(company.id)"><i class="fa fa-unlock-alt"></i>Make Approved</button>
                        <button class="btn btn-danger" v-on:click="Comrejected(company.id)"><i class="fa fa-remove"></i>Rejected</button>
                    </div>

                </div>

    </div>
</template>

<script>
    import {API} from '../../../custom.js';
    const customClass = new API();
    export default {
        data: function () {
            return {
                $id: null,
                allerros: [],
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
            approved:function(Id){
                var app = this;
                var Id
                console.log(Id);
                axios.get('/settings/approved-change-status/'+Id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toaster.success('Your Company Approved successfully!');
                            app.$router.replace('/company-info');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });

            },
            Comrejected:function(Id){
                var app = this;
                var Id
                console.log(Id);
                axios.get('/settings/rejected-change-status/'+Id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toaster.success('Your Company Rejected successfully!');
                            app.$router.replace('/index#/company-info');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });

            },

        }
    }
</script>
