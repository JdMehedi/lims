<template>

<div class="card card-magenta border border-magenta">
    <div class="card-header">
        <div class="float-left">
            <h5 class="box-title"><strong><i class="fa fa-list"></i> <strong>Home Page Content</strong></strong></h5>
        </div>
        <div class="float-right">
            <router-link :to="{name: 'HomeContentCreate'}" class="btn btn-success"><i class="fa fa-plus"></i> Add New Content</router-link>
        </div>
    </div>

    <div class="card-body">
        <div class="col-md-1 col-sm-6">
            <div class="vue-table-length-box">
                <select class="form-control input-sm" v-model="limits"  @change="limit($event)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class=" col-md-3 col-sm-6">
            <div class="vue-table-filter-box">
                <input class="form-control input-sm" type="text" placeholder="Search..."
                       v-model="search" v-on:keyup="keymonitor">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                 <table class="table vue-table table-striped table-bordered">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Heading</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="homecontent in laravelData.data" :key="homecontent.id">
                    <td>{{homecontent.si}}</td>
                    <td>{{homecontent.type.charAt(0).toUpperCase() + homecontent.type.slice(1)}}</td>
                    <td>{{homecontent.title.slice(0, 50) + '...'}}</td>
                    <td>{{homecontent.heading.slice(0, 50) + '...'}}</td>


                    <td>
                    <span v-if="homecontent.status == 1" class="text-success">Active</span>
                    <span v-else class="text-danger">Inactive</span>
                    </td>
                    <td>
                        <router-link :to="{name: 'HomeContentEdit', params: {id: homecontent.id}}" class="btn btn-xs btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </router-link>
                    </td>
                </tr>
                </tbody>
            </table>
             </div>
            <div class="col-md-5"><br>
            </div>
            <div class="col-md-7 ">
                <pagination v-model="page" :records="laravelData.total ?? 0" :per-page="limits ?? 0" @paginate="getResults"/>
                <!--                    <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>-->
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
                axios.get('/settings/home-page/home-page-content?page=' + page + is_search + max_limit)
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
