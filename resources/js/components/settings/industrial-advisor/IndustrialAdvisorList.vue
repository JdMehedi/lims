<template>
    <div class="box box-success">

        <div class="box-header with-border">
                <h5><strong><i class="fa fa-list"></i> <strong>Industrial Advisor List</strong></strong></h5>

                <div class="box-tools pull-right">
                    <router-link :to="{name: 'IndustrialAdvisorCreate'}" class="btn btn-success"><i class="fa fa-plus"></i> Add New Advisor</router-link>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-1 col-sm-6">
                        <div class="vue-table-length-box">
                            <select class="form-control input-sm" v-model="limits"  @change="limit($event)">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                         </div>
                    </div>
                    <div class=" col-md-offset-8 col-md-3 col-sm-6">
                        <div class="vue-table-filter-box">
                            <input class="form-control input-sm" type="text" placeholder="Search..."
                               v-model="search" v-on:keyup="keymonitor">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table vue-table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Office</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                             </thead>
                            <tbody>
                                <tr v-for="industrialAdvisor in laravelData.data" :key="industrialAdvisor.id">
                                    <td>{{industrialAdvisor.si}}</td>
                                    <td>{{industrialAdvisor.name}}</td>
                                    <td>{{industrialAdvisor.designation}}</td>
                                    <td>{{industrialAdvisor.office}}</td>


                                    <td>
                                    <span v-if="industrialAdvisor.status == 1">
                                        <span class="label label-success">Active</span>
                                    </span>
                                    <span v-else>
                                        <span class="label label-danger">Inactive</span>
                                    </span>
                                    </td>
                                    <td>
                                        <router-link :to="{name: 'IndustrialAdvisorEdit', params: {id: industrialAdvisor.id}}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Open
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                     </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="vue-table-info">
                            Showing {{ laravelData.from }} to {{ laravelData.to }} of {{ laravelData.total }} entries
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="vue-table-pagination">
                            <pagination :data="laravelData" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                this.$http.get('/settings/home-page/industrial-advisor?page=' + page + is_search + max_limit)
                    .then(response => {
                        this.laravelData = response.data;
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
