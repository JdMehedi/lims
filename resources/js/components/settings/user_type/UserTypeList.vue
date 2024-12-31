<template>

    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <div class="float-left">
                <h5><strong><i class="fa fa-list"></i> <strong>List of User Type</strong></strong></h5>
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
                        <th>Type Name</th>
                        <th>Security profile</th>
                        <th>Weekly off day</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(usertype,index) in laravelData.data" :key="index">
                        <td>{{usertype.si}}</td>
                        <td>{{usertype.type_name}}</td>
                        <td>{{usertype.profile_name}}</td>
                        <td>{{usertype.week_off_days}}</td>
                        <td>
                            <span v-if="usertype.status == 'active'" class="text-success" >Active &nbsp;</span>

                            <span v-else class="text-danger">Inactive</span>
                        </td>
                        <td>
                            <router-link v-if="usertype.type_id !='1x101'" :to="{name: 'UserTypeEdit', params: {id: usertype.id}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="col-md-6"><br>
<!--                Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries-->
            </div>
            <div class="col-md-6 ">
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
                axios.get('/settings/user-type-list?page=' + page + is_search + max_limit)
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
