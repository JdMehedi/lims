<template>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <div class="float-left">
                <h5><strong><i class="fa fa-list"></i> <strong>List of Area With Eligibility List of ISP License</strong></strong></h5>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="card-body">
            <div>
                <div class="col-md-1 float-left">
                    <select class="form-control col-md-12" v-model="limits" @change="limit($event)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-offset-8 col-md-3 float-right">
                    <input class="form-control" type="text" placeholder="Search..." v-model="search"
                           v-on:keyup="keymonitor">

                </div>
            </div>
            <br>
            <br>
            <div class="col-md-12">
                <table class="table  dt-responsive">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th @click="sortTable('area_nm')" style="cursor:pointer"><i class="fa fa-sort"></i> Area
                            Name</th>
                        <th @click="sortTable('area_nm_ban')" style="cursor:pointer"><i class="fa fa-sort"></i> Area
                            Name in Bangla</th>
                        <th>Area Type</th>
                        <th>Actual Eligibility</th>
                        <th>No. Of Existing ISP License</th>
                        <th>New ISP License Eligibility</th>
                        <th>Total Application Eligibility</th>
                        <th>No. Of Existing ISP Application</th>
                        <th>New ISP Application Eligibility</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="area in laravelData.data" :key="area.id">
                        <td>{{area.si}}</td>
                        <td>{{area.area_nm}}</td>
                        <td>{{area.area_nm_ban}}</td>
                        <td>
                            <span v-if="area.area_type == 1">Division &nbsp;</span>
                            <span v-if="area.area_type == 2">District &nbsp;</span>
                            <span v-if="area.area_type == 3">Thana</span>
                        </td>
                        <td>{{area.area_eligibility}}</td>
                        <td>{{area.area_existing_license}}</td>
                        <td>{{area.area_eligibility_license}}</td>
                        <td>{{area.area_total_application_eligibility}}</td>
                        <td>{{area.area_existing_isp_eligibility}}</td>
                        <td>{{area.remaining_isp_eligibility}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-5"><br>
                <!--                Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries-->
            </div>
            <div class="col-md-7 ">
                <!--                {{getResults}}-->
                <pagination v-model="page" :records="laravelData.total" :per-page="limits" @paginate="getResults" />
                <!--                <pagination class="float-right" :data="laravelData" @pagination-change-page="getResults"></pagination>-->
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
            limits: 10,
            currentSort: '',
            currentSortDir: 'asc',
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
        getResults(page, sort = '', column_name = '') {
            if (typeof page === 'undefined') {
                page = 1;
            }
            var max_limit = '&limit=' + this.limits;
            var is_search = '';
            if (this.search) {
                is_search = '&search=' + this.search
            }
            var app = this;
            axios.get('/settings/area-list?page=' + page + is_search + max_limit + column_name + sort)
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
        sortTable: function (short) {
            if (short === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = short;
            var sort = '&order=' + this.currentSortDir;
            var column_name = '&column_name=' + short;
            this.getResults(1, sort, column_name);
        }
    }
}
</script>
<style>
.card-header {
    padding: 5px 20px;
}
</style>

