<template>
<!--    <div class="col-lg-12">-->
        <!--<div class="form-group">-->
        <!--<router-link :to="{name: 'BankCreate'}" class="btn btn-success">Create new Bank</router-link>-->
        <!--</div>-->

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>PDF print requests</strong></strong></h5>
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
                <table class="table dt-responsive">
                    <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <!--<th>Job Sending Response</th>-->
                        <!--<th>Job Receiving Response</th>-->
                        <!--<th>Sending Status</th>-->
                        <th>Sending No Of Try</th>
                        <th>Receiving status</th>
                        <th>Receiving no of try</th>
                        <th>Prepare JSON</th>
                        <th>Certificate Link</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="pdfprint in laravelData.data" :key="pdfprint.id">

                        <td>{{pdfprint.tracking_no}}</td>
                        <!--<td>{{pdfprint.job_sending_response}}</td>-->
                        <!--<td>{{pdfprint.job_receiving_response}}</td>-->
                        <!--<td>{{pdfprint.locajob_sending_statustion}}</td>-->
                        <td>{{pdfprint.no_of_try_job_sending}}</td>
                        <td>{{pdfprint.job_receiving_status}}</td>
                        <td>{{pdfprint.no_of_try_job_receving}}</td>
                        <td>{{pdfprint.prepared_json}}</td>

                        <td v-if="pdfprint.certificate_link !== ''"><a :href="pdfprint.certificate_link" class="btn btn-xs btn-primary" target="_blank">Open Link</a></td>
                        <td v-else></td>

                        <td class="text-center" style="width: 18%">
                            <button class="btn btn-xs btn-danger" v-on:click="resendpdf(pdfprint.id)"> <i class="fa fa-envelope-square"></i> Resend</button> &nbsp;
                            <router-link :to="{name: 'PdfPrintRequestsEdit', params: {id: pdfprint.id}}" class="btn btn-xs btn-success">
                                <i class="fa fa-edit"></i> Edit   </router-link>
                            <!--<router-link :to="{name: 'PdfPrintRequestsVerify', params: {id: pdfprint.id+'/'+pdfprint.certificate_name}}" class="btn btn-xs btn-primary">-->
                                <!--<i class="fa fa-check"></i> Verify </router-link>-->
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
            // console.log(Auth().id)
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
                axios.get('/settings/pdf-print-requests?page=' + page + is_search + max_limit)
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
            resendpdf:function(Id){
                var app = this;
                // app.$toast.success('Your SMS & Email will be resent successfully!.');
                axios.get('/settings/resend-pdf-print-requests/'+Id)
                    .then(resp => {
                        // alert(resp);
                        if (resp.data.status === true) {
                            app.$toast.success('The pdf request will be resend successfully!');
                            app.getResults();
                        }
                    }).catch((error) => {
                    app.$toast.error('Something was wrong.');
                    app.allerros = error.response.data.errors;
                });

            }
        }
    }
</script>
