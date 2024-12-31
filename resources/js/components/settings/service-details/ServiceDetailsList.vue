<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card">
            <div class="card-header bg-primary">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>List of Service Details</strong></strong></h5>
                </div>
                <div class="float-right">
                    <router-link :to="{name: 'ServiceDetailsCreate'}" class="btn btn-success">Create New</router-link>
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
                        <th>Service name</th>
                        <th>Attached file</th>
                        <th>Ordering</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="service in laravelData.data" :key="service.id">
                        <td>{{service.si}}</td>
                        <td>{{service.name}}</td>
                        <td><a v-bind:href="'/'+ service.attachment" class="btn btn-xs btn-warning"><i class="fa fa-folder-open-o"></i> Open Attachment</a></td>
                        <td>{{service.ordering}}</td>
                        <td>
                            <span v-if="service.status == 1" class="label label-success">Active</span>
                            <span v-else class="label label-danger">In-active</span></td>
                        <td>
                            <router-link :to="{name: 'ServiceDetailsEdit', params: {id: service.id}}" class="btn btn-xs btn-primary">
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
                var app =this;
               axios.get('/settings/service-details?page=' + page + is_search + max_limit)
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
