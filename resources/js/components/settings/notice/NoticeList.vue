<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>List of Notices</strong></strong></h5>
                </div>
                <div class="float-right">
                    <router-link :to="{name: 'NoticeCreate'}" class="btn btn-success">Create Notice</router-link>
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
                        <th>Sl</th>
                        <th>Heading</th>
                        <th>Details</th>
                        <th>Importance</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="notice in laravelData.data" :key="notice.id">
<!--                        :key="notice.id"-->
                        <td>{{notice.si}}</td>
                        <td style="width: 20%" v-html="notice.heading.length>50?notice.heading.slice(0, 50) + '...':notice.heading"></td>
                        <td v-html="notice.details.length>100?notice.details.slice(0, 100) + '...':notice.details"></td>
                        <td>{{notice.importance}}</td>
                        <td>{{notice.status}}</td>
                        <td  style="width: 10%">
                            <router-link :to="{name: 'NoticeEdit', params: {id: notice.id}}" class="btn btn-xs btn-primary">
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
                var app =this
                axios.get('/settings/notice-list?page=' + page + is_search + max_limit)
                   .then(response => {
                    app.laravelData = response.data;
                    console.log(app.laravelData)
                    app.data = response.data;
                });
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
