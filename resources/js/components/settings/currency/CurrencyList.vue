<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>List of Currency</strong></strong></h5>
                </div>
                <div class="float-right">
                    <router-link :to="{name: 'CurrencyCreate'}" class="btn btn-success">Create Currency</router-link>
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
                <table class="table  dt-responsive ">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>USD($) value</th>
                        <th>BDT Value</th>
                        <th>Active status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="currency in laravelData.data" :key="currency.id">
                        <td>{{currency.si}}</td>
                        <td>{{currency.code}}</td>
                        <td>{{currency.name}}</td>
                        <td>{{currency.usd_value}}</td>
                        <td>{{currency.bdt_value}}</td>
                        <td class="text-center"> <span v-if="currency.is_active == 1" class="text-success">Active</span>
                            <span v-else class="text-danger">Inactive</span>
                        </td>
                        <td>
                            <router-link :to="{name: 'CurrencyEdit', params: {id: currency.id}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </router-link> &nbsp;
                            <button class="btn btn-xs btn-danger" v-on:click="ConfirmDelete(currency.id,'Currency')"><i class="fa fa-times"></i></button>
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
                var app =this;
                axios.get('/settings/currency-list?page=' + page + is_search + max_limit)
                    .then(response => {
                        app.laravelData = response.data;
                    })
            },
            keymonitor: function (e) {
                this.getResults(1);
            },
            limit: function (e) {
                this.getResults(1);
            },

            ConfirmDelete:function(Id,Currency){
                var app = this;
                var sure_del = confirm("Are you sure you want to delete this item?");
                if (sure_del) {
                axios.get('/settings/delete/'+Currency+'/'+Id )
                    .then(function (resp) {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toast.success('Data has been deleted successfully.');
                            app.$router.push('/currency-list');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
                }else {
                    return false;
                }

            }
        }
    }
</script>
