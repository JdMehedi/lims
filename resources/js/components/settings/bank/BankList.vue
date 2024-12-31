<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>Bank List</strong></strong></h5>
                </div>
                <div class="float-right">
                    <router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>
                </div>
                <div class="clearfix"></div>

            </div>

            <div class="card-body">
                <div>
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
                </div>
                <br>
                <br>
                <div class="col-md-12">
                <table class="table  dt-responsive ">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Contact No.</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Active status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="bank in laravelData.data" :key="bank.id">
                        <td>{{bank.si}}</td>
                        <td>{{bank.name}}</td>
                        <td>{{bank.phone}}</td>
                        <td>{{bank.email}}</td>
                        <td>
                            {{bank.location}}
                        </td>
                        <td>
                            <span v-if="bank.is_active == 1" class="text-success">Active</span>
                            <span v-else class="text-danger">In-active</span>
                        </td>
                        <td>
                            <router-link :to="{name: 'BankEdit', params: {id: bank.id}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-md-5"><br>
<!--                    Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries-->
                </div>
                <div class="col-md-7 ">
                    <pagination v-model="page" :records="laravelData.total" :per-page="limits" @paginate="getResults"/>

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
                var app =this
                axios.get('/settings/bank-list-v2-get?page=' + page + is_search + max_limit)
                    .then(response => {
                        app.laravelData = response.data;
                        console.log(app.laravelData)
                    })
            },
            keymonitor: function (e) {
                this.getResults(1);
            },
            limit: function (e) {
                this.getResults(1);
            }
        }
    }
</script>
<style>
    .card-header{
        padding: 5px 20px;
    }
</style>

