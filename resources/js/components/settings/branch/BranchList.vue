<template>
    <!--    <div class="col-lg-12">-->
    <!--<div class="form-group">-->
    <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
    <!--</div>-->

    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <div class="float-left">
                <h5><strong><i class="fa fa-list"></i> <strong>Branch List</strong></strong></h5>
            </div>
            <div class="float-right">
                <router-link :to="{name: 'BranchCreate'}" class="btn btn-success">Create new Branch</router-link>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="panel-body">
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
                        <th @click="sortTable('branch_name')" style="cursor:pointer"><i class="fa fa-sort"></i> Branch Name</th>
                        <th @click="sortTable('bank_name')" style="cursor:pointer"><i class="fa fa-sort"></i> Bank Name</th>
                        <th @click="sortTable('address')" style="cursor:pointer"><i class="fa fa-sort"></i> Address</th>
                        <th><i class="fa fa-sort"></i> Active status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="branch in laravelData.data" :key="branch.id">
                        <td>{{branch.si}}</td>
                        <td>{{branch.branch_name}}</td>
                        <td>{{branch.bank_name}}</td>
                        <td>{{branch.address}}</td>
                        <td>
                            <span v-if="branch.is_active == 1" class="text-success">Active</span>
                            <span v-else class="text-danger">In-active</span>
                        </td>

                        <td>
                            <router-link :to="{name: 'BranchEdit', params: {id: branch.id}}" class="btn btn-xs btn-primary">
                                <i
                                        class="fa fa-edit"></i> Edit
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-5"><br>
<!--                Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries-->
            </div>
            <div class="col-md-7 ">
                <pagination v-model="page" :records="laravelData.total" :per-page="limits" @paginate="getResults"/>
                <!--                <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>-->
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
              currentSort:'',
              currentSortDir:'asc',
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
            getResults(page, sort='', column_name='') {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                var max_limit = '&limit=' + this.limits;
                var is_search = '';
                if(this.search){
                    is_search = '&search=' + this.search
                }
                axios.get('/settings/branch-list-v2-get?page=' + page + is_search + max_limit + column_name + sort)
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
          sortTable:function (short) {
            if(short === this.currentSort) {
              this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = short;
            var sort = '&order='+this.currentSortDir;
            var column_name = '&column_name='+short;
            this.getResults(1, sort, column_name);
          }
        }
    }
</script>
<style>
    .card-header{
        padding: 5px 20px;
    }
</style>
