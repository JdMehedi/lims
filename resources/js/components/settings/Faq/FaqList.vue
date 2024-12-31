<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>List of Area</strong></strong></h5>
                </div>
                <div class="pull-right">
                    <router-link :to="{name: 'AreaCreate'}" class="btn btn-success">Create Area</router-link>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="panel-body"><br>
                <div class="col-md-1">
                    <select class="form-control col-md-1" v-model="limits"  @change="limit($event)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class=" col-md-offset-8 col-md-3">
                    <input class="form-control pull-right" type="text" placeholder="Search..."
                           v-model="search" v-on:keyup="keymonitor">

                </div>
                <div class="col-md-12">
                <table class="table  dt-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Articles</th>
                        <th>Draft</th>
                        <th>Unpublished</th>
                        <th>Private</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="faq in laravelData.data" :key="faq.id">
                        <td>{{faq.si}}</td>
                        <td>{{faq.name}}</td>
                        <td>{{faq.noOfItems}}</td>
                        <td>{{faq.Unpublished}}</td>
                        <td>{{faq.Draft}}</td>
                        <td>
                            <router-link :to="{name: 'AreaEdit', params: {id: faq.id}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i> Open
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-md-6"><br>
                    Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries
                </div>
                <div class="col-md-6 ">
                    <pagination class="pull-right" :data="laravelData"
                                @pagination-change-page="getResults"></pagination>
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
                this.$http.get('/settings/faq/faq-cat?page=' + page + is_search + max_limit)
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
