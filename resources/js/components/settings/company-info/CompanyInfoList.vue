<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5><strong><i class="fa fa-list"></i> <strong>List of Company Info</strong></strong></h5>
                <div class="float-right">
<!--                    <router-link :to="{name: 'RejectedCompanyList'}" class="btn btn-success">Rejected/Draft Company Lists</router-link>-->
<!--                    <router-link :to="{name: 'CompanyInfoCreate'}" class="btn btn-success">Create Company Info</router-link>-->
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body"><br>
                <div class="col-md-1 float-left">
                    <select class="form-control col-md-12" v-model="limits"  @change="limit($event)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class=" col-md-offset-8 col-md-3 float-right">
                    <input class="form-control" type="text" placeholder="Search..."
                           v-model="search" v-on:keyup="keymonitor">
                </div>
                <br>
                <br>
                <div class="col-md-12">
                <table class="table  dt-responsive">
                    <thead>
                    <tr>
                        <th>#SL No.</th>
                        <th>Company Name</th>
                        <th>Approval Status</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Created By</th>
                        <th>Action</th>
<!--                        <th>Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="company in laravelData.data" :key="company.id">
                        <td>{{company.si}}</td>
                        <td>{{company.org_nm}}</td>
                        <td>
                            <span v-if="company.is_approved == 1" class="text-success">Yes</span>
                            <span v-else class="text-danger">No</span></td>
                        <td>
                            <span v-if="company.company_status == 1" class="text-success">Active</span>
                            <span v-else class="text-danger">In-active</span>
                        </td>
                        <td>{{company.created_at}}</td>
                        <td>{{company.user_email}}</td>
                        <td>
<!--                            :to="{name: 'CompanyInfoEdit', params: {id: company.id}}"-->
<!--                            <a class="btn btn-xs btn-primary" :href="'/client/company-profile/create/' + company.id" target="_blank"> Open</a>-->
                             &nbsp;
                            <span v-if="company.is_approved == 1">
                                <button v-if="company.company_status == 0" v-on:click="Comactive(company.id,'1')" class="btn btn-success btn-xs">Activate</button>
                                <button v-else class="btn btn-danger btn-xs" v-on:click="Comdeactive(company.id,'0')">Deactivate</button>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-md-5"><br>
                  <!--  Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries-->
                </div>
                <div class="col-md-7 ">
                    <pagination v-model="page" :records="laravelData.total ?? 0" :per-page="limits ?? 0" @paginate="getResults"/>

                    <!--                    <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>-->
                </div>
            </div>
        </div>
<!--    </div>-->

</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                laravelData: {},
                search: '',
                limits:10,
                page: 1
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getResults();
        },

        methods: {
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                var max_limit = '&limit=' + this.limits;
                var is_search = '';
                if(this.search){
                    is_search = '&search=' + this.search
                }
               axios.get('/settings/company-info?page=' + page + is_search + max_limit)
                    .then(response => {
                        this.laravelData = response.data;
                    })
            },
            keymonitor: function (e) {
                this.getResults(1);
            },
            limit: function (e) {
                this.getResults(1);
            },
            Comactive:function(Id,status_id){
                var app = this;
                var sure_del = confirm("Are you sure you want to Active the Company?");
                if (sure_del) {
                axios.get('/settings/company-change-status/'+Id+'/'+status_id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toast.success('Your company Active successfully!.');
                            app.$router.go('/index#/company-info');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
                }else{
                    return false;
                }

            },
            Comdeactive:function(Id,status_id){
                var app = this;
                var sure_del = confirm("Are you sure you want to Deactive the Company?");
                if (sure_del) {
                axios.get('/settings/company-change-status/'+Id+'/'+status_id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toast.success('Your company De active successfully!.');
                            app.$router.go('/index#/company-info');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
                }else{
                 return false;
                }

            },

        }
    }
</script>
