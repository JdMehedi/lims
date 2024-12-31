<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header"><h5 class="card-title pt-2 pb-2">Change the details of PDF</h5></div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Tracking no :</label>
                            <div :class="['col-md-9', allerros.tracking_no ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.tracking_no" class="form-control">
                                <span v-if="allerros.tracking_no" :class="['text-danger']">{{ allerros.tracking_no[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Reg Key :</label>
                            <div :class="['col-md-9', allerros.reg_key ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.reg_key" class="form-control">
                                <span v-if="allerros.reg_key" :class="['text-danger']">{{ allerros.reg_key[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">URL Requests :</label>
                            <div :class="['col-md-9', allerros.url_requests ? 'has-error' : '']">
                                <textarea v-model="pdfprint.url_requests" class="form-control" rows= 4, cols= 54></textarea>
                                <span v-if="allerros.url_requests"
                                      :class="['text-danger']">{{ allerros.url_requests[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Pdf Type :</label>
                            <div :class="['col-md-9', allerros.pdf_type ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.pdf_type" class="form-control">
                                <span v-if="allerros.pdf_type"
                                      :class="['text-danger']">{{ allerros.pdf_type[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Certificate Name :</label>
                            <div :class="['col-md-9', allerros.certificate_name ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.certificate_name" class="form-control">
                                <span v-if="allerros.certificate_name"
                                      :class="['text-danger']">{{ allerros.certificate_name[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">PDF server URL :</label>
                            <div :class="['col-md-9', allerros.pdf_server_url ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.pdf_server_url" class="form-control">
                                <span v-if="allerros.pdf_server_url"
                                      :class="['text-danger']">{{ allerros.pdf_server_url[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Job Sending status :</label>
                            <div :class="['col-md-9', allerros.job_sending_status ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.job_sending_status" class="form-control">
                                <span v-if="allerros.job_sending_status"
                                      :class="['text-danger']">{{ allerros.job_sending_status[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Job Sending  Response :</label>
                            <div :class="['col-md-9', allerros.job_sending_response ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.job_sending_response" class="form-control">
                                <span v-if="allerros.job_sending_response"
                                      :class="['text-danger']">{{ allerros.job_sending_response[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">No of try job sending :</label>
                            <div :class="['col-md-9', allerros.no_of_try_job_sending ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.no_of_try_job_sending" class="form-control">
                                <span v-if="allerros.no_of_try_job_sending"
                                      :class="['text-danger']">{{ allerros.no_of_try_job_sending[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Job receiving status :</label>
                            <div :class="['col-md-9', allerros.job_receiving_status ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.job_receiving_status" class="form-control">
                                <span v-if="allerros.job_receiving_status"
                                      :class="['text-danger']">{{ allerros.job_receiving_status[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Job receiving response :</label>
                            <div :class="['col-md-9', allerros.job_receiving_response ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.job_receiving_response" class="form-control">
                                <span v-if="allerros.job_receiving_response"
                                      :class="['text-danger']">{{ allerros.job_receiving_response[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">No of try job receiving :</label>
                            <div :class="['col-md-9', allerros.no_of_try_job_receving ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.no_of_try_job_receving" class="form-control">
                                <span v-if="allerros.no_of_try_job_receving"
                                      :class="['text-danger']">{{ allerros.no_of_try_job_receving[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Prepared JSON :</label>
                            <div :class="['col-md-9', allerros.prepared_json ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.prepared_json" class="form-control">
                                <span v-if="allerros.prepared_json"
                                      :class="['text-danger']">{{ allerros.prepared_json[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Table Name :</label>
                            <div :class="['col-md-9', allerros.table_name ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.table_name" class="form-control">
                                <span v-if="allerros.table_name"
                                      :class="['text-danger']">{{ allerros.table_name[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Field Name :</label>
                            <div :class="['col-md-9', allerros.field_name ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.field_name" class="form-control">
                                <span v-if="allerros.field_name"
                                      :class="['text-danger']">{{ allerros.field_name[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Certificate Link :</label>
                            <div :class="['col-md-9', allerros.certificate_link ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.certificate_link" class="form-control">
                                <span v-if="allerros.certificate_link"
                                      :class="['text-danger']">{{ allerros.certificate_link[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Signatory :</label>
                            <div :class="['col-md-9', allerros.signatory ? 'has-error' : '']">
                                <input type="text" v-model="pdfprint.signatory" class="form-control">
                                <span v-if="allerros.signatory"
                                      :class="['text-danger']">{{ allerros.signatory[0] }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <router-link to="/pdf-print-requests" class="btn btn-default"> Back</router-link>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import {API} from '../../../custom.js';
    // const customClass = new API();
    export default {
        data: function () {
            return {
                $id: null,
                allerros: [],
                pdfprint: {
                    reg_key: '',
                    url_requests: '',
                    pdf_type: '',
                    certificate_name: '',
                    pdf_server_url: '',
                    job_sending_status: '',
                    job_sending_response: '',
                    no_of_try_job_sending: '',
                    job_receiving_status: '',
                    job_receiving_response: '',
                    no_of_try_job_receving: '',
                    prepared_json: '',
                    table_name: '',
                    field_name: '',
                    certificate_link: '',
                    signatory: '',
                },
            }
        },

        mounted() {
            // customClass.onlyNumber();
            // customClass.isEmail();
        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            app.id = id;
            axios.get('/settings/edit-pdf-print-requests/' + id)
                .then(function (resp) {
                    app.pdfprint = resp.data;
                    // console.log(app.pdfprint);
                })
                .catch(function () {
                    alert("Could not load your PDF Print")
                });
        },

        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.pdfprint;
                // console.log(app.id);
                axios.patch('/settings/update-pdf-print-requests/' + app.id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/pdf-print-requests');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
