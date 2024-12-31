<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>Rejected/Draft Company Lists</strong></strong></h5>
                </div>
                <div class="pull-right">
                    <router-link :to="{name: 'CompanyInfoList'}" class="btn btn-success">Company Info</router-link>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="panel-body"><br>
                <div class="col-md-1">
                    <select class="form-control col-md-1" v-model="limits"  @change="limit($event)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class=" col-md-offset-8 col-md-3">
                    <input class="form-control pull-right" type="text" placeholder="Search..."
                           v-model="search" v-on:keyup="keymonitor">

                </div>
                <div class="col-md-12">
                <table class="table  dt-responsive">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="company in laravelData.data" :key="company.id">
                        <td>{{company.si}}</td>
                        <td>{{company.company_name}}</td>
                        <td>
                            <span v-if="company.is_rejected == 'yes'"  >Rejected</span>
                            <span v-else >Draft</span>
                        </td>
                        <td>{{company.created_at}}</td>
                        <td>
                            <router-link :to="{name: 'RejectedCompanyEdit', params: {id: company.id}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i> Open
                            </router-link>
                            <button v-if="company.is_rejected !== 'yes'" v-on:click="Comreject(company.id)" class="btn btn-danger btn-xs">Reject</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-md-5"><br>
                    Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries
                </div>
                <div class="col-md-7 ">
                    <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>
                </div>
            </div>
        </div>
<!--    </div>-->

</template>

<script>
    export default {
        data() {
            return {
                laravelData: {},
                search: '',
                limits:10
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
                this.$http.get('/settings/rejected-draft-company-list?page=' + page + is_search + max_limit)
                    .then(response => {
                        return response.json();
                    }).then(data => {
                    this.laravelData = data;
                    // this.data = data;
                });
            },
            keymonitor: function (e) {
                this.getResults(1);
            },
            limit: function (e) {
                this.getResults(1);
            },
            Comreject:function(Id){
                var app = this;
                var sure_del = confirm("Are you sure you want to Reject the Company?");
                if (sure_del) {
                axios.get('/settings/rejected-draft-company-change-status/'+Id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toaster.success('Your company Reject successfully!.');
                            app.$router.go('/index#/rejected-company');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
                } else {
                   return false;
                }

            }
        }
    }
</script>
