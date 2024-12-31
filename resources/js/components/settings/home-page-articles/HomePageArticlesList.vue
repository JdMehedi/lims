<template>

    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <div class="float-left">
                <h5 class="card-title pt-2 pb-2"><strong><i class="fa fa-list"></i> <strong>List of Home Page Articles</strong></strong></h5>
            </div>
<!--            <div class=" box-tools pull-right">-->
<!--                <router-link :to="{name: 'HomePageArticlesCreate'}" class="btn btn-success"><i class="fa fa-plus"></i> Create New</router-link>-->
<!--            </div>-->
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
                <div class="table-responsive">
                    <table class="table vue-table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Page Name</th>
                            <th>Page Content</th>
                            <th>Page Content English</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="homepagearticles in laravelData.data" :key="homepagearticles.id">
                            <td>{{homepagearticles.si}}</td>
                            <td>{{homepagearticles.page_name}}</td>
                            <td>{{homepagearticles.page_content.slice(0, 50) + '...'}}</td>
                            <td>{{homepagearticles.page_content_en.slice(0, 50) + '...'}}</td>
                            <td>
                                <router-link :to="{name: 'HomePageArticlesEdit', params: {id: homepagearticles.id}}" class="btn btn-xs btn-primary">
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
                axios.get('/settings/home-page/home-page-articles?page=' + page + is_search + max_limit)
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
