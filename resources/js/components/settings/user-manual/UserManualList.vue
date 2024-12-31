<template>
<div class="card card-magenta border border-magenta">
    <div class="card-header">
        <div class="float-left">
            <h5><strong><i class="fa fa-list"></i> <strong>List of User manual</strong></strong></h5>
        </div>
        <div class="float-right">
            <router-link :to="{name: 'UserManualCreate'}" class="btn btn-success"> <i class="fa fa-plus"></i> Add New</router-link>
        </div>
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
            <div class="table-responsive">
                <table class="table vue-table table-striped table-bordered">
                <thead>
                <tr>
                    <th>SL</th>
                    <th width="15%">License Name</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th width="10%">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="usermanual in laravelData.data" :key="usermanual.id">
                    <td>{{usermanual.si}}</td>
                    <td>{{usermanual.typeName}}</td>
                    <td><a :href="usermanual.file" class="btn btn-xs btn-success" target="_blank" >pdf file</a></td>
                    <td>
                        <span v-if="usermanual.status == 1"  class="text-success">Active</span>
                        <span v-else class="text-danger">Inactive</span>
                    </td>
                    <td>{{usermanual.order}}</td>
                    <td width="10%">
                        <router-link :to="{name: 'UserManualEdit', params: {id: usermanual.id}}" class="btn btn-xs btn-primary">
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
                var app = this;
                axios.get('/settings/user-manual?page=' + page + is_search + max_limit)
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
