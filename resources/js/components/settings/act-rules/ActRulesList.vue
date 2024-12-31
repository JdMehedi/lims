<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

      <div class="card">
            <div class="card-header bg-primary">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>List of Act & Rules</strong></strong></h5>
                </div>
                <div class="float-right">
                    <router-link :to="{name: 'ActRulesCreate'}" class="btn btn-success">Create Act & Rules</router-link>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body">
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
                <table class="table  dt-responsive">
                    <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Order</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="actrules in laravelData.data" :key="actrules.id">
                        <td>{{actrules.si}}</td>
                        <td>{{actrules.subject}}</td>
                        <td v-html="actrules.description">
                        </td>
                        <td>{{actrules.showing_order}}</td>
                        <td>
                            <span v-if="actrules.status == 1" class="text-success">Active</span>
                            <span v-else class="text-danger">In-active</span></td>
                        <td>
                            <router-link :to="{name: 'ActRulesEdit', params: {id: actrules.id}}" class="btn btn-xs btn-primary">
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
    // import {stripedContent} from '../../../services/CommonFunction';
    import axios from 'axios'
    export default {
        data() {
            return {
                // stripedContent: stripedContent,
                laravelData: {},
                search: '',
                limits:10,
                page: 1
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        // mounted() {
        //     console.log(this.$helpers.strippedContent("<span>testing</span>"));
        // },
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
                var app=this;
                axios.get('/settings/act-rules-list?page=' + page + is_search + max_limit)
                    .then(response => {
                        app.laravelData = response.data;
                    })
            },
            keymonitor: function (e) {
                this.getResults(1, this.search);
            },
            limit: function (e) {
                if (e.target.value == '') {
                    this.getResults(1);
                } else {
                    this.getResults(1);
                }


            }
        }
    }
</script>
<style>
    .card-header{
        padding: 5px 20px;
    }
</style>
