<template>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <div class="float-left">
                <h5><strong><i class="fa fa-list"></i> <strong>Security List</strong></strong></h5>
            </div>
            <div class="float-right">
                <router-link :to="{name: 'SecurityCreate'}" class="btn btn-success">Create security profile </router-link>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="card-body">
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
                    <th>SL</th>
                    <th>Profile name</th>
                    <th>Ip address</th>
                    <th>Weekly off days</th>
                    <th>Start time</th>
                    <th>End time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="security in laravelData.data" :key="security.id">
                    <td>{{security.si}}</td>
                    <td>{{security.profile_name}}</td>
                    <td>{{security.allowed_remote_ip}}</td>
                    <td>{{security.week_off_days}}</td>
                    <td>{{security.work_hour_start}}</td>
                    <td>{{security.work_hour_end}}</td>


                    <td>
                    <span v-if="security.active_status == 'yes'" class="text-success">Active</span>
                    <span v-else class="text-danger">Inactive</span>
                    </td>
                    <td>
                        <router-link :to="{name: 'SecurityEdit', params: {id: security.id}}" class="btn btn-xs btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </router-link>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class="col-md-6"><br>
            </div>
            <div class="col-md-6 ">
                <pagination v-model="page" :records="laravelData.total ?? 0" :per-page="limits ?? 0" @paginate="getResults"/>

                <!--  <pagination class="pull-right" :data="laravelData"
                              @pagination-change-page="getResults"></pagination>-->
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
                axios.get('/settings/security?page=' + page + is_search + max_limit)
                    .then(response => {
                        app.laravelData = response.data;
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
