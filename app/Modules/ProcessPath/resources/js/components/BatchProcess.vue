<template>
    <div class="alert alert-info" id="processBar">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-left">
                    <h4 class="no-margin no-padding" style="line-height: 30px">Application Process :</h4>
                </div>
                <div class="float-right">
                    <a data-toggle="modal" data-target="#remarksHistoryModal" class="float-right">
                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Last
                            Remarks</button>
                    </a>
                    <div class="modal fade" id="remarksHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title"><i class="fa fa-th-ask"></i> Last Remarks & Attachments</h4>
                                    <button type="button" class="close" data-dismiss="modal"><span
                                            aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="list-group">
                                        <span class="list-group-item" style="color: rgba(0,0,0,0.8);">
                                            <h4 class="list-group-item-heading">Remarks</h4>
                                            <p v-if="processInfo.status_id != 1" class="list-group-item-text">
                                                {{ processInfo.process_desc }}</p>
                                        </span>


                                        <div v-for="(remarks, index) in remarks_attachment" :key="index">
                                            <a target="_blank" v-bind:href="remarks.file" style="margin-top: 10px;"
                                                class="btn btn-primary btn-xs">
                                                <i class="fa fa-save"></i> Download Attachment
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer" style="text-align:left;">
                                    <button type="button" class="btn btn-danger btn-md pull-right" data-dismiss="modal">
                                        Close
                                    </button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-12">
                <hr style="border-top: 2px solid #32a9c2; margin-top: 10px; margin-bottom: 10px" />
            </div>
        </div>

        <form @submit.prevent="onSubmitProcess">

            <div class="row" v-if="delegateUserInfo">
                <div class="col-sm-12">

                    <div class="callout callout-danger">
                        <h5>You are processing delegated application!</h5>

                        <p>You are working <b>On-behalf
                                of</b> {{ delegateUserInfo.user_full_name }}, {{ delegateUserInfo.designation }}
                            &nbsp;({{ delegateUserInfo.user_email }})</p>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label class="required-star" for="status_id">Apply Status:</label>
                        <select :class="['form-control', allerros.status_id ? 'is-invalid' : '']"
                            v-model="this.statusId" @change="loadDeskByStatus($event)">
                            <option selected value="">Select status</option>
                            <option v-for="status in statusList" :value="status.id" v-bind:key="status.id">
                                {{ status.status_name }}
                            </option>
                        </select>
                        <div v-if="allerros.status_id" :class="['invalid-feedback']">{{ allerros.status_id[0] }}</div>
                    </div>
                </div>

                <div v-if="isDeskListVisible" class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="deskId">Send to Desk:</label>
                        <select class="form-control" v-model="this.deskId" @change="loadUserByDesk($event)">
                            <option selected value="">Select desk</option>
                            <option v-for="(deskName, deskIndex) in deskList" :value="deskIndex" v-bind:key="deskIndex">
                                {{ deskName }}
                            </option>
                        </select>
                    </div>
                </div>

                <div v-if="isUserListVisible" class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="userId">Select desk user:</label>
                        <select class="form-control" v-model="this.userId">
                            <option selected value="">Select user</option>
                            <option v-for="user in userList" :value="user.user_id" v-bind:key="user.user_id">
                                {{ user.user_full_name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="attach_file" :class="[is_file_required == 1 ? 'required-star' : '']">Attach file:
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=""
                                data-original-title="To select multiple files, hold down the CTRL or SHIFT key while selecting."></i>
                        </label>

                        <div class="custom-file">
                            <input type="file" :class="['custom-file-input', allerros.attach_file ? 'is-invalid' : '']"
                                id="attach_file" ref="attach_file" accept="application/pdf" multiple
                                v-on:change="handleFileUpload()">
                            <label class="custom-file-label" for="attach_file" ref="file" type="file">Choose
                                file</label>
                            <div v-if="allerros.attach_file" :class="['invalid-feedback']">{{ allerros.attach_file[0] }}
                            </div>
                        </div>
                        <small class="form-text text-muted">File: *.pdf | Maximum 2 MB</small>
                    </div>
                </div>

                <div v-if="isPinNumberVisible" class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="pin_number" class="required-star">Enter Pin Number:</label>
                        <input :class="['form-control', allerros.pin_number ? 'is-invalid' : '']" type="text"
                            v-model="this.pin_number" />
                        <div v-if="allerros.pin_number" :class="['invalid-feedback']">{{ allerros.pin_number[0] }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="remarks" :class="[is_remarks_required == 1 ? 'required-star' : '']">Remarks</label>
                        <textarea :class="['form-control', allerros.remarks ? 'is-invalid' : '']" v-model="remarks"
                            id="remarks" rows="2" cols="10" placeholder="Enter Remarks"></textarea>
                        <div v-if="allerros.remarks" :class="['invalid-feedback']">{{ allerros.remarks[0] }}</div>
                    </div>
                </div>
            </div>


            <!-- add-on form div -->
            <div class="row">
                <div class="col">
                    <div v-html="AddonFormResponseHTML"></div>
                </div>
            </div>

            <div class="row">
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary">Process</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        processInfo: '',
        remarks_attachment: [],
        delegateUserInfo: null,
        processVerificationData: ''
    },
    data() {
        return {
            processVerificationInterval: '',
            statusId: '',
            statusList: [],
            isDeskListVisible: false,
            deskId: '',
            deskList: [],
            isUserListVisible: false,
            userId: '',
            userList: [],
            attach_file: [],
            isPinNumberVisible: false,
            pin_number: '',
            is_remarks_required: 0,
            is_file_required: 0,
            remarks: '',
            AddonFormResponseHTML: '',
            allerros: [],
        }
    },
    created() {
        this.loadStatusList();
    },
    mounted: function () {
        // call after every 1 minutes
        this.processVerificationInterval = window.setInterval(() => {
            this.verifyProcessValidity()
        }, 60000)
    },
    watch: {
        statusId(newStatusId, oldStatusId) {
            if (newStatusId == 5 || newStatusId == 6) {
                this.is_remarks_required = 1;
            }
        }
    },
    methods: {
        handleFileUpload(e) {
            this.attach_file = this.$refs.attach_file.files;
        },
        loadStatusList() {
            var app = this;
            axios.get('/process-path/ajax/load-status-list', {
                params: {
                    process_list_id: app.processInfo.encoded_process_list_id,
                    cat_id: app.processInfo.encoded_cat_id
                }
            }).then(response => {
                app.statusList = response.data.data;

                if (response.data.suggested_status > 0) {
                    app.statusId = response.data.suggested_status;
                    app.loadDeskByStatus();
                } else {
                    app.statusId = '';
                }

            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        loadDeskByStatus() {
            var app = this;
            axios.post('/process-path/get-desk-by-status', {
                process_list_id: app.processInfo.encoded_process_list_id,
                status_from: app.processInfo.encoded_status_from,
                cat_id: app.processInfo.encoded_cat_id,
                statusId: app.statusId
            }).then(response => {

                if (Object.keys(response.data.data).length > 0) {
                    app.isDeskListVisible = true;
                    app.deskList = response.data.data;
                    app.deskId = Object.keys(app.deskList)[0];

                    app.loadUserByDesk();
                } else {
                    app.isDeskListVisible = false;
                    app.deskList = [];
                    app.deskId = '';

                    app.isUserListVisible = false;
                    app.userList = [];
                }

                // display pin number input, if pin is required
                if (response.data.pin_number == 1) {
                    app.isPinNumberVisible = true;
                } else {
                    app.isPinNumberVisible = false;
                }

                // Update add-on form response, if have
                app.AddonFormResponseHTML = response.data.html;


                // response.data.remarks == 1, statusId in [5,6] then required remarks field
                if (response.data.remarks == 1) {
                    app.is_remarks_required = 1;
                } else {
                    app.is_remarks_required = 0;
                }


                // Setup required condition for Attachment field if response.data.file_attachment == 1
                if (response.data.file_attachment == 1) {
                    app.is_file_required = 1;
                } else {
                    app.is_file_required = 0;
                }


                // if there are no desk then hide select desk list

            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        loadUserByDesk() {
            var app = this;
            axios.post('/process-path/get-user-by-desk', {
                process_list_id: app.processInfo.encoded_process_list_id,
                status_from: app.processInfo.encoded_status_from,
                desk_from: app.processInfo.encoded_desk_from,
                cat_id: app.processInfo.encoded_cat_id,
                statusId: app.statusId,
                desk_to: app.deskId,
                process_type_id: app.processInfo.encoded_process_type_id,
                office_id: app.processInfo.encoded_office_id
            }).then(response => {

                if (Object.keys(response.data.data).length > 0) {
                    app.isUserListVisible = true;
                    app.userList = response.data.data;
                } else {
                    app.isUserListVisible = false;
                    app.userList = [];
                }

            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        onSubmitProcess() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            let dataform = new FormData();

            dataform.append('_token', token.content);
            dataform.append('status_from', this.processInfo.encoded_status_from);
            dataform.append('desk_from', this.processInfo.encoded_desk_from);
            dataform.append('process_list_id', this.processInfo.encoded_process_list_id);
            dataform.append('cat_id', this.processInfo.encoded_cat_id);
            dataform.append('data_verification', this.processVerificationData);
            dataform.append('is_remarks_required', this.is_remarks_required);
            dataform.append('is_file_required', this.is_file_required);
            // dataform.append('attach_file', this.attach_file);

            for (var i = 0; i < this.attach_file.length; i++) {
                let file = this.attach_file[i];

                dataform.append('attach_file[' + i + ']', file);
            }

            dataform.append('status_id', this.statusId);
            dataform.append('desk_id', this.deskId);
            dataform.append('is_user', this.userId);
            dataform.append('remarks', this.remarks);

            if (this.isPinNumberVisible) {
                dataform.append('pin_number', this.pin_number);
            }

            if (this.delegateUserInfo) {
                dataform.append('on_behalf_user_id', this.delegateUserInfo.id);
            }

            axios.post('/vue/process/update', dataform,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {

                    this.allerros = [];

                    this.$toast.success(response.data.message);

                    // redirect to the list
                    this.$router.go(-1);

                }).catch((error) => {
                    if (error.response.data.errors) {
                        this.allerros = error.response.data.errors;
                    }

                    this.$toast.error(error.response.data.message != 'undefined' ? error.response.data.message : 'XMLHttpRequest error: ' + error.response.statusText);
                });
        },
        verifyProcessValidity() {
            axios.get('/process-path/check-process-validity', {
                params: {
                    data_verification: this.processVerificationData,
                    process_list_id: this.processInfo.encoded_process_list_id
                }
            }).then(response => {
                if (response.data.responseCode != 1) {
                    window.clearInterval(app.processVerificationInterval);

                    this.$toast.error('Sorry, the process data verification failed. Maybe another desk user is trying to process this application.');

                    // redirect to the list
                    this.$router.go(-1);
                }
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        }
    }
}
</script>

<style>
#processBar {
    border: 7px solid #32a9c2 !important;
    background: #bae6e1 !important;
    color: #000 !important;
    padding: 10px
}
</style>