<template>

    <div v-if="viewMode == 'on' && hasApplicationProcessPermission">
        <BatchProcess :processInfo="processInfo" :remarks_attachment="remarks_attachment"
            :delegateUserInfo="delegateUserInfo" :processVerificationData="processVerificationData"></BatchProcess>
    </div>


    <div v-if="viewMode == 'on'" class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="process-info-tab" data-toggle="pill" href="#process-info" role="tab"
                        aria-controls="process-info" aria-selected="true" @click="setActiveTab('process-info')"><i
                            class="fa fa-info-circle"></i> Application
                        Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payment-info-tab" data-toggle="pill" href="#payment-info" role="tab"
                        aria-controls="payment-info" aria-selected="false" @click="setActiveTab('payment-info')"><i
                            class="fas fa-money-bill"></i> Payment
                        Information</a>
                </li>

                <div v-if="['1x101', '3x303', '4x404', '13x303'].includes(Auth().user_type)">
                    <li class="nav-item">
                        <a class="nav-link" id="process-map-tab" data-toggle="pill" href="#process-map" role="tab"
                            aria-controls="process-map" aria-selected="false" @click="setActiveTab('process-map')"><i
                                class="fas fa-map"></i> Process Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="shadow-file-tab" data-toggle="pill" href="#shadow-file" role="tab"
                            aria-controls="shadow-file" aria-selected="false" @click="setActiveTab('shadow-file')"><i
                                class="far fa-file-archive"></i> Shadow
                            File History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="process-history-tab" data-toggle="pill" href="#process-history"
                            role="tab" aria-controls="process-history" aria-selected="false"
                            @click="setActiveTab('process-history')"><i class="fas fa-history"></i> Process
                            History</a>
                    </li>
                </div>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">

                <div v-if="activeTab == 'process-info'" class="tab-pane fade active show" id="process-info"
                    role="tabpanel" aria-labelledby="process-info-tab">

                    <div class="card well-sm bg-deep disabled color-palette no-margin mb-0">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="clearfix no-margin row">
                                        <label class="col-md-5 col-xs-5">Tracking no. </label>
                                        <div class="col-md-7 col-xs-7">
                                            <span>: {{ processInfo.tracking_no }}</span>
                                        </div>
                                    </div>

                                    <div class="clearfix no-margin row">
                                        <label class="col-md-5 col-xs-5">Date of submission </label>
                                        <div class="col-md-7 col-xs-7">
                                            <span>: {{ processInfo.submitted_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="clearfix no-margin row">
                                        <label class="col-md-5 col-xs-5">Current status </label>
                                        <div class="col-md-7 col-xs-7">
                                            <span>
                                                : {{ processInfo.status_name }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="clearfix no-margin row">
                                        <label class="col-md-5 col-xs-5">Current desk </label>
                                        <div class="col-md-7 col-xs-7">
                                            <span>
                                                : {{ processInfo.desk_name != 0 ? processInfo.desk_name : 'Applicant' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-danger btn-sm mt-3" title="Download Approval Copy"
                        :disabled="isPdfBtnDisabled" @click="downloadPdf($event)"><i class="fa fa-download"></i>
                        Application
                        Download as PDF</button>

                </div>

                <div v-if="activeTab == 'payment-info'" class="tab-pane fade" id="payment-info" role="tabpanel"
                    aria-labelledby="payment-info-tab">
                    <PaymentInfo :encoded_process_type_id="processInfo.encoded_process_type_id"
                        :encoded_ref_id="processInfo.encoded_ref_id"></PaymentInfo>
                </div>

                <div v-if="['1x101', '3x303', '4x404', '13x303'].includes(Auth().user_type)">
                    <div v-if="activeTab == 'process-map'" class="tab-pane fade" id="process-map" role="tabpanel"
                        aria-labelledby="process-map-tab">
                        <ProcessMap :encoded_process_type_id="processInfo.encoded_process_type_id"
                            :encoded_ref_id="processInfo.encoded_ref_id" :cat_id="processInfo.cat_id"></ProcessMap>
                    </div>

                    <div v-if="activeTab == 'shadow-file'" class="tab-pane fade" id="shadow-file" role="tabpanel"
                        aria-labelledby="shadow-file-tab">
                        <ShadowFile :module_name="processInfo.acl_name"
                            :encoded_process_list_id="processInfo.encoded_process_list_id"
                            :encoded_process_type_id="processInfo.encoded_process_type_id"
                            :encoded_ref_id="processInfo.encoded_ref_id"></ShadowFile>
                    </div>

                    <div v-if="activeTab == 'process-history'" class="tab-pane fade" id="process-history"
                        role="tabpanel" aria-labelledby="process-history-tab">
                        <ProcessHistory :encoded_process_list_id="processInfo.encoded_process_list_id"></ProcessHistory>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>


    <template v-if="Object.keys(this.processInfo).length">
        <!-- <ApplicationForm :openMode="openMode" :module_name="processInfo.acl_name"
            :encoded_ref_id="processInfo.encoded_ref_id" ref="applicationForm"></ApplicationForm> -->

        <component :is="setAppComponent"
            v-bind="{encoded_ref_id:processInfo.encoded_ref_id, encoded_process_type_id:processInfo.encoded_process_type_id, viewMode:viewMode, openMode:openMode}">
        </component>
    </template>

</template>

<script>
//Vue 3 is having a special function to define these async functions
import { defineAsyncComponent } from "vue";

import PaymentInfo from "./PaymentInfo.vue";
import ProcessMap from "./ProcessMap.vue";
import ShadowFile from "./ShadowFile.vue";
import ProcessHistory from "./ProcessHistory.vue";

import BatchProcess from "./BatchProcess.vue";
import html2pdf from 'html2pdf.js'
import moment from 'moment';
export default {
    components: { PaymentInfo, ProcessMap, ShadowFile, ProcessHistory, BatchProcess },
    data() {
        return {
            activeTab: 'process-info',
            processInfo: {},
            mode: '',
            openMode: '',
            viewMode: '',
            appFormUrl: '',
            hasApplicationProcessPermission: false,
            remarks_attachment: [],
            delegateUserInfo: null,
            processVerificationData: '',
            isPdfBtnDisabled: false,
        }
    },
    created() {
        this.getProcessInfo();
    },
    computed: {
        setAppComponent() {
            let appComponentName = '';

            if (this.openMode == 'add' || this.openMode == 'edit') {
                appComponentName = this.processInfo.acl_name + 'Form';
            } else {
                appComponentName = this.processInfo.acl_name + 'View';
            }

            return defineAsyncComponent(() => import(/* webpackChunkName: "AppView" */ `../../../../${this.processInfo.acl_name}/resources/js/components/${appComponentName}.vue`))
        },
    },
    methods: {

        setActiveTab(tabName) {
            this.activeTab = tabName;
        },
        getProcessInfo() {
            var app = this;
            axios.get('/vue/process/view/' + this.$route.params.app_id + '/' + this.$route.params.process_type_id).then(response => {

                app.processInfo = response.data.process_info;
                app.mode = response.data.mode;

                app.openMode = response.data.openMode;
                app.viewMode = response.data.viewMode;
                app.appFormUrl = response.data.url;
                app.hasApplicationProcessPermission = response.data.hasDeskOfficeWisePermission;
                app.delegateUserInfo = response.data.delegateUserInfo;
                app.processVerificationData = response.data.processVerificationData;
                app.remarks_attachment = response.data.remarks_attachment;

            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        downloadPdf(event) {

            this.isPdfBtnDisabled = true;
            var element = document.getElementById("applicationForm").innerHTML;

            var downloadTime = 'Download time: ' + moment(new Date()).format('DD-MMM-YYYY h:mm a');
            var options = {
                margin: [0.80, 0.50, 0.80, 0.50], //top, left, bottom, right
                // filename:     'myfile.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                },
                enableLinks: true,
            };

            var html = '<div style="margin-top: 60px">' + element + '</div>';

            //with options
            html2pdf().set(options).from(html).toPdf().get('pdf').then(function (pdf) {

                var pageCount = pdf.internal.getNumberOfPages();

                pdf.setPage(1);
                var pageWidth = pdf.internal.pageSize.getWidth();
                var pageHeight = pdf.internal.pageSize.getHeight();

                var image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAAAvCAYAAADOxsXZAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjg5NzMwRDVERjQ4MTExRTNCNzlBRjE0QUIxQzJCMjQ1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjg5NzMwRDVFRjQ4MTExRTNCNzlBRjE0QUIxQzJCMjQ1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ODk3MzBENUJGNDgxMTFFM0I3OUFGMTRBQjFDMkIyNDUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6ODk3MzBENUNGNDgxMTFFM0I3OUFGMTRBQjFDMkIyNDUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4kQ2fEAAAYNElEQVR42uxcB3hURdc+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";
                pdf.addImage(image, 'PNG', pageWidth / 2 - 0.60, 0.50, 1.20, 0.40);
                pdf.setFontSize(11);
                pdf.text("One Stop Service Provider", 3.50, 1.40);

                for (let j = 1; j < pageCount + 1; j++) {
                    pdf.setPage(j);
                    pdf.setFontSize(8);
                    pdf.setTextColor(32, 32, 32);
                    pdf.text(`${j} / ${pageCount}`, pageWidth - 1, pageHeight - 0.50);
                    pdf.text(downloadTime, 0.60, pageHeight - 0.50);
                }

            }).save(this.processInfo.tracking_no + '.pdf');

            this.isPdfBtnDisabled = false;
        }
    }
}
</script>

<style>

</style>