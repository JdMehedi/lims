<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>Email & SMS Queue</strong></strong></h5>
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
                <br><br>
                <div class="col-md-12">
                <table class="table  dt-responsive ">
                    <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <th>Caption</th>
                        <th>EMAIL To</th>
                        <th>Email Status</th>
                        <th>SMS To</th>
                        <th>SMS Status</th>

                        <th width="45%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="$emailsms in laravelData.data" :key="$emailsms.id">
                        <td>{{$emailsms.tracking_no}}</td>
                        <td>{{$emailsms.caption}}</td>
                        <td>{{$emailsms.email_to}}</td>
                        <td>
                            <span v-if="$emailsms.email_status == 1" class="btn btn-xs btn-success">Sent</span>
                            <span v-else class="btn btn-xs btn-danger">Pending</span>
                        </td>
                        <td>{{$emailsms.sms_to}}</td>
                        <td>
                            <span v-if="$emailsms.sms_status == 1" class="btn btn-xs btn-success">Sent</span>
                            <span v-else class="btn btn-xs btn-danger">Pending</span>
                        </td>


                        <td>
                            <button class="btn btn-xs btn-info btn-sm" v-on:click="email($emailsms.id,'email')"> <i class="fa fa-at"></i> Resend email </button>&nbsp;
                            <button class="btn btn-xs btn-primary btn-sm" v-on:click="resendSMS($emailsms.id,'sms')"> <i class="fa fa-envelope-square"></i> Resend SMS</button>&nbsp;
                            <button class="btn btn-xs btn-success btn-sm" v-on:click="resendboth($emailsms.id,'both')"><i class="fa fa-folder-minus"></i> Resend Both</button>&nbsp;
                            <router-link :to="{name: 'EmailSmsQueueEdit', params: {id: $emailsms.id}}" class="btn btn-xs btn-warning">
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
                limits: 10,
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
                var app =this
                axios.get('/settings/email-sms-queue?page=' + page + is_search + max_limit)
                    .then(response => {
                        app.laravelData = response.data;
                    })
            },
            email:function(Id,email){
                var app = this;
                axios.get('/settings/resend-email-sms-queue/'+Id+'/'+email )
                    .then(resp => {
                        if (resp.data.status === true) {
                            app.$toast.success('Your Email will be resent successfully!.');
                            app.getResults();
                        }
                    }).catch(error => {
                    app.$toast.error('Something was wrong.');
                    app.allerros = error.response.data.errors;
                });

            },
            resendSMS:function(Id,sms){
                var app = this;
                axios.get('/settings/resend-email-sms-queue/'+Id+'/'+ sms )
                    .then(resp => {
                        if (resp.data.status === true) {
                            app.$toast.success('Your SMS will be resent successfully!.');
                            app.getResults();
                        }
                    }).catch((error) => {
                    app.$toast.error('Something was wrong.');
                    app.allerros = error.response.data.errors;
                });

            },
            resendboth:function(Id,both){
                var app = this;
                axios.get('/settings/resend-email-sms-queue/'+Id+'/'+both )
                    .then(resp => {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toast.success('Your SMS & Email will be resent successfully!.');
                            app.getResults();
                        }
                    }).catch((error) => {
                    app.$toast.error('Something was wrong.');
                    app.allerros = error.response.data.errors;
                });
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
