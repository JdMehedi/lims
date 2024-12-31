<template>
    <div>
        <div v-if="this.listInfo.desk == 'process_search'" class="row">
            <div class="col-2">
                <div class="form-group">
                    <label for="ProcessType">Service:</label>
                    <select class="form-control" v-model="this.searchProcessType"
                        @change="getStatusListByProcess($event.target.value)">
                        <option selected value="">Search by process type</option>
                        <option v-for="process in processTypes" :value="process.id" v-bind:key="process.id">
                            {{ process.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label for="searchProcessStatus">Status:</label>
                    <select class="form-control" v-model="this.searchProcessStatus">
                        <option selected value="">Search by process status</option>
                        <option v-for="status in searchProcessStatusList" :value="status.id" v-bind:key="status.id">
                            {{ status.status_name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="search_text">Search text: <i class="fa fa-info-circle" data-placement="right"
                            data-toggle="tooltip"
                            title="Only the application's tracking number and reference data will be searched by the keyword you provided."></i></label>
                    <input type="text" v-model="this.searchProcessText" class="form-control"
                        placeholder="Type at least 3 characters" id="search_text">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label for="searchTimeLine">Date within:</label>
                    <select class="form-control" v-model="this.searchTimeLine">
                        <option selected value="">Select time range</option>
                        <option v-for="range in searchTimeLineList" :value="range.id" v-bind:key="range.id">
                            {{ range.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label for="date_within">Of</label>
                    <Datepicker v-model="this.searchProcessDateWithin" :enableTimePicker="false" type="date"
                        :maxDate="new Date()" placeholder="Select Date" autoApply />
                </div>

            </div>
            <div class="col-1">
                <div class="form-group">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-info" @click="globalSearch()">Search</button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="statusBox">

                <div v-for="(statusBox, key) in statusWiseApps" :key="key" class="statusBox-inner"
                    v-bind:style="{ border: '1px solid' + statusBox.color + '!important' }">

                    <a href="javascript:void(0)" class="statusWiseList" v-bind:style="{background: statusBox.color }"
                        @click="filterStatusBoxData(statusBox.process_type_id, statusBox.id)">

                        <div class="statusBox-body" v-bind:style="{background: statusBox.color}"
                            :title="statusBox.status_name">

                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="statusBox-title" :id="statusBox.status_name">
                                        {{ statusBox.totalApplication }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="statusBox-number">
                                        {{ statusBox.status_name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="dt-header clearfix mb-2">
                    <div class="float-left">
                        <select class="form-control col-md-12" v-model="limits" @change="limit($event)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="float-right">
                        <div style="display: inline-flex;">

                            <select v-if="this.listInfo.desk == 'my-desk'" class="form-control mr-2"
                                v-model="this.currentProcessType" @change="changeProcessType($event)">
                                <option selected value="0">Search by process type</option>
                                <option v-for="process in processTypes" :value="process.id" v-bind:key="process.id">
                                    {{ process.name }}
                                </option>
                            </select>

                            <input v-if="this.listInfo.desk != 'process_search'" class="form-control" type="text"
                                placeholder="Search..." v-model="search" v-on:keyup="keymonitor">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th @click="sortTable('tracking_no')" style="cursor:pointer; width: 15%;"><i
                                        class="fa fa-sort"></i>
                                    Tracking no</th>
                                <th @click="sortTable('desk_name')" style="cursor:pointer"><i class="fa fa-sort"></i>
                                    Current desk</th>
                                <th @click="sortTable('process_name')" style="cursor:pointer"><i class="fa fa-sort"></i>
                                    Process type</th>
                                <th style="width: 35%">Reference data</th>
                                <th @click="sortTable('status_name')" style="cursor:pointer"><i class="fa fa-sort"></i>
                                    Status</th>
                                <th>Modified</th>
                                <th style="width: 7%">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="laravelData.total">
                            <tr v-for=" process in laravelData.data" :key="process.id">
                                <td>
                                    <i v-if="process.is_favourite > 0" style="color:#f0ad4e"
                                        class="fas fa-star fav_icon"
                                        title="Added to your favorite list. Click to remove."
                                        @click="removeFromFavouriteList(process.encoded_process_id)"></i>

                                    <i v-else class="far fa-star fav_icon" title="Add to your favorite list"
                                        @click="addToFavouriteList(process.encoded_process_id)"></i>

                                    {{ process.tracking_no }}
                                </td>
                                <td>{{ process.desk_id == 0 ? 'Applicant' : process.desk_name }}</td>
                                <td>{{ process.process_name }}</td>
                                <td>{{ convertProcessJsonToString(process.json_object) }}</td>
                                <td>{{ process.status_name }}</td>
                                <td>{{ TimestampToReadableDate(process.updated_at) }}</td>
                                <td>
                                    <button v-if="process.locked_by > 0 && process.locked_by != Auth().id"
                                        v-on:click="warn($event, 'The record locked by ' + process.locked_by_user + ', would you like to force unlock?', process.form_url, process.encoded_ref_id, process.encoded_process_type_id)"
                                        class="btn btn-xs btn-primary">
                                        <i class="fas fa-user-lock"></i>
                                        View
                                    </button>

                                    <router-link
                                        v-else-if="editButtonStatusArray.indexOf(process.status_id) >= 0 && process.created_by == Auth().id"
                                        :to="{ name: 'ApplicationEdit', params: { module: process.form_url, app_id: process.encoded_ref_id, process_type_id: process.encoded_process_type_id } }"
                                        class="btn btn-xs btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </router-link>

                                    <router-link v-else
                                        :to="{ name: 'ApplicationView', params: { module: process.form_url, app_id: process.encoded_ref_id, process_type_id: process.encoded_process_type_id } }"
                                        class="btn btn-xs btn-primary">
                                        <i class="fas fa-folder-open"></i>
                                        View
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr class="text-center text-bold">
                                <td colspan="7">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <pagination v-model="page" :records="laravelData.total" :per-page="limits" @paginate="getResults" />
            </div>
        </div>
    </div>
</template>


<script>
import moment from 'moment';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    components: {
        Datepicker
    },
    props: {
        listInfo: {
            processType: {
                default: 0,
                type: Number
            },
            status: {
                default: -1000,
                type: Number
            },
            desk: {
                default: 'my-desk',
                type: String
            }
        }
    },
    data() {
        return {
            statusWiseApps: [],
            editButtonStatusArray: [-1, 5],
            searchTimeLineList: [
                { id: 1, name: '1 Day' },
                { id: 7, name: '1 Week' },
                { id: 15, name: '2 Weeks' },
                { id: 30, name: '1 Month' },
            ],
            currentProcessType: 0,
            processTypes: [],
            laravelData: {
                total: 0
            },
            search: '',
            limits: 10,
            currentSort: '',
            currentSortDir: 'asc',
            page: 1,
            process_search: false,
            searchProcessType: '',
            searchProcessStatus: '',
            searchProcessStatusList: [],
            searchProcessText: '',
            searchTimeLine: '',
            searchProcessDateWithin: '',
        }
    },
    mounted() { },
    created() {

        this.currentProcessType = this.listInfo.processType;

        if (this.listInfo.desk == 'my-desk' || this.listInfo.desk == 'process_search') {
            this.getActiveProcessTypes();
        }

        if (this.listInfo.desk == 'process_search') {
            this.process_search = true;
        }

        this.getResults();

        if (this.currentProcessType != 0) {
            this.getstatusWiseCountingBox();
        }
    },
    methods: {
        convertProcessJsonToString(string) {
            const parseJson = JSON.parse(string);
            let response = '';

            for (const [key, value] of Object.entries(parseJson)) {
                response += key + ': ' + value + ', ';
            }

            return response;
        },
        TimestampToReadableDate(timestamp) {
            return moment(timestamp).fromNow();
        },
        globalSearch() {
            this.process_search = true;
            this.getResults(1);
        },
        getResults(page, sort = '', column_name = '') {

            if (typeof page === 'undefined') {
                page = 1;
            }

            var app = this;

            axios.get('/vue/process/get-list/' + this.listInfo.status + '/' + this.listInfo.desk,
                {
                    params: {
                        process_type_id: this.currentProcessType,
                        page: page,
                        search: this.search,
                        limit: this.limits,
                        column_name: column_name,
                        sort: sort,
                        process_search: this.process_search,
                        search_type: this.searchProcessType,
                        search_status: this.searchProcessStatus,
                        search_time: this.searchTimeLine,
                        search_text: this.searchProcessText,
                        search_date: this.searchProcessDateWithin
                    }
                })
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

            this.getResults(1, this.currentSortDir, this.currentSort);
        },
        changeProcessType: function (e) {
            this.statusWiseApps = [];

            this.process_search = false;
            this.searchProcessType = '';
            this.searchProcessStatus = '';

            this.getstatusWiseCountingBox();

            this.getResults(1);
        },
        getActiveProcessTypes() {
            var app = this;
            axios.get('/vue/process-type')
                .then(response => {
                    app.processTypes = response.data;
                })
        },
        getStatusListByProcess($process_type_id) {
            var app = this;
            axios.get('/vue/process-type/' + $process_type_id + '/status')
                .then(response => {
                    app.searchProcessStatusList = response.data;
                })
        },
        warn: function (event, message, module, encoded_ref_id, encoded_process_type_id) {
            // now we have access to the native event
            if (event) {
                event.preventDefault();
            }
            const forceOpen = confirm(message);
            if (forceOpen) {
                this.$router.push({ name: 'ApplicationView', params: { module: module, app_id: encoded_ref_id, process_type_id: encoded_process_type_id } });
            }
        },
        addToFavouriteList(encoded_process_id) {
            var app = this;
            axios.post('/vue/process/favorite-data-store', {
                process_list_id: encoded_process_id
            }).then(response => {
                app.$toast.success('Added to your favourite list!');
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed
                    app.getResults(1);
                });
        },
        removeFromFavouriteList(encoded_process_id) {
            var app = this;
            axios.post('/vue/process/favorite-data-remove', {
                process_list_id: encoded_process_id
            }).then(response => {
                app.$toast.success('Removed from your favourite list!');
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed
                    app.getResults(1);
                });
        },
        getstatusWiseCountingBox() {
            var app = this;
            axios.get('/vue/process/status-wise-app-count/' + this.currentProcessType).then(response => {
                app.statusWiseApps = response.data;
            }).catch(function (error) {
                // handle error
                app.$toast.warning(error.response.data.message);
            })
                .then(function () {
                    // always executed
                });
        },
        filterStatusBoxData(process_type_id, status_id) {
            this.process_search = true;
            this.searchProcessType = process_type_id;
            this.searchProcessStatus = status_id;
            this.getResults(1);
        }
    }
};
</script>

<style>
.VuePagination__pagination.pagination.VuePagination__pagination {
    float: right;
}

.VuePagination__count.VuePagination__count {
    padding-top: 5px;
}

.fav_icon {
    cursor: pointer;
}

.statusBox {
    float: left;
    width: 100%;
    margin: 5px 3px 20px 3px;
    height: auto;
}

.statusBox-inner {
    display: inline-block;
    padding: 3px !important;
    font-weight: bold !important;
    height: 90px;
    margin: 5px;
    width: 100px;
}

.statusBox-title,
.statusBox-number {
    color: #fff;
    margin-top: 0;
    margin-bottom: 0;
    font-weight: bold;
}

.statusBox-title {
    font-size: 20px;
}

.statusBox-number {
    font-size: 13px;
}

.statusBox-body {
    color: white;
    padding: 10px 5px !important;
    height: 100%;
}
</style>