<template>
    <div>
        <AppLoadingSpinnerVue ref="AppLoadingSpinnerVue"></AppLoadingSpinnerVue>

        <template v-if="['5x505'].includes('5x505') && [15, 32].includes(appInfo.status_id)">
            <CreatePayment :paymentInfo="{
                app_id: appInfo.id,
                process_type_id: appInfo.process_type_id,
                payment_step_id: payment_step_id,
                contact_name: getUserFullName(),
                contact_email: Auth().user_email,
                contact_phone: Auth().user_mobile,
                contact_address: Auth().contact_address,
                unfixed_amount_object: unfixed_amounts
            }"></CreatePayment>
        </template>

        <div id="paymentPanel"></div>

        <div class="card" style="border-radius: 10px;" id="applicationForm">
            <div style="padding: 10px 15px">
                <span class="section_head">Industry Registration</span>
            </div>

            <template v-if="Object.keys(appInfo).length != 0">

                <div class="card-body" style="padding: 0 15px">

                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            General Information
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Company Name Bangla</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.org_nm_bn }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Company Name English</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.org_nm }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Project Name</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.project_nm }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Registration Type</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.regist_name_bn }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Company Type</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.company_type_bn }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Business Category</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.business_category }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Invest Type</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.investment_type_bn }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Investing Country</label>
                                        <div class="col-7">
                                            <span>:
                                                <span v-for="(country, index) in investing_country" :key="index"> {{
                                                country.country_name }} ,</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Total Investment</label>
                                        <div class="col-7">
                                            <span>:</span><span class="input_ban"> {{ appInfo.investment_limit }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Industrial Class</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.ind_category_bn }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 row">
                                        <label class="col-5">Industrial Sector</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.ind_sector_bn }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 row">
                                        <label class="col-5">Industrial Sub Sector</label>
                                        <div class="col-7">
                                            <span>: {{ appInfo.ind_sub_sector_bn }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Necessary Attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <AppDocuments :encoded_ref_id="encoded_ref_id"
                                :encoded_process_type_id="encoded_process_type_id" :viewMode="viewMode"
                                :openMode="openMode" :doc_type_key="doc_type_key"></AppDocuments>
                        </div>
                    </div>
                </div>

            </template>
        </div>

    </div>
</template>

<script>

import AppLoadingSpinnerVue from '../../../../ProcessPath/resources/js/components/AppLoadingSpinner.vue';
import AppDocuments from '../../../../Documents/resources/js/components/AppDocuments.vue';
import CreatePayment from '../../../../SonaliPayment/resources/js/components/CreatePayment.vue'

export default {
    components: { AppLoadingSpinnerVue, AppDocuments, CreatePayment },
    props: {
        encoded_ref_id: {
            default: '',
            type: String,
            required: true
        },
        encoded_process_type_id: {
            default: '',
            type: String,
            required: true
        },
        viewMode: {
            default: '',
            type: String,
            required: true
        },
        openMode: {
            default: '',
            type: String,
            required: true
        }
    },
    data() {
        return {
            appInfo: {},
            investing_country: [],
            doc_type_key: '',
            payment_step_id: '',
            unfixed_amounts: {}
        }
    },
    created() {
        this.loadIndustryNewAppData();
    },
    methods: {
        loadIndustryNewAppData() {
            var app = this;

            axios.get('/vue/industry-new/view/' + app.encoded_ref_id).then(response => {

                if (response.data.responseCode == 0) {
                    app.$refs.AppLoadingSpinnerVue.setErrorMessage(response.data.html);
                } else {
                    app.appInfo = response.data.appInfo;
                    app.investing_country = response.data.investing_country;
                    app.payment_step_id = response.data.payment_step_id;
                    app.unfixed_amounts = response.data.unfixed_amounts;


                    var reg_type_id = app.appInfo.regist_type;
                    var company_type_id = app.appInfo.org_type;
                    var industrial_category_id = app.appInfo.ind_category_id;
                    var investment_type_id = app.appInfo.invest_type;
                    app.doc_type_key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;
                }

            }).catch(function (error) {
                // handle error
                app.$refs.AppLoadingSpinnerVue.setErrorMessage(error);
            })
                .then(function () {

                    // Stop app loaer
                    app.$refs.AppLoadingSpinnerVue.stopAppLoading();
                });
        }
    }
}
</script>

<style scoped>
label {
    font-weight: normal;
    font-size: 14px;
}

span {
    font-size: 14px;
}

.section_head {
    font-size: 24px;
    font-weight: 400;
    margin-top: 25px;
}

@media (min-width: 767px) {
    .addressField {
        width: 79.5%;
        float: right
    }
}

@media (max-width: 480px) {
    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 5px;
    }

    label {
        font-weight: normal;
        font-size: 13px;
    }

    span {
        font-size: 13px;
    }

    .panel-body {
        padding: 10px 0 !important;
    }

    .form-group {
        margin: 0;
    }

    .image_mobile {
        width: 100%;
    }
}
</style>