<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">Create New company</div>
            <div class="panel-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3  required-star">Name of Company:</label>
                            <div :class="['col-md-8', allerros.company_name ? 'has-error' : '']">
                                <input type="text" v-model="company.company_name" class="form-control">
                                <span v-if="allerros.company_name" :class="['text-danger']">{{ allerros.company_name[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3  required-star">Country</label>
                            <div :class="['col-md-8', allerros.country_id ? 'has-error' : '']">
                                <select v-model="company.country_id"  class="form-control input-sm isCountry" >
                                    <option value="">Select One</option>
                                    <option v-for="option in countryData" :key="option.id" :value="option.id">{{option.name}}</option>
                                </select>
                                <span v-if="allerros.country_id" :class="['text-danger']">{{ allerros.country_id[0] }}</span>
                            </div>
                        </div>

                        <div class="state_area hidden" >
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3  required-star">State</label>
                            <div :class="['col-md-8', allerros.state ? 'has-error' : '']">
                                <input type="text" v-model="company.state" class="form-control">
                                <span v-if="allerros.state" :class="['text-danger']">{{ allerros.state[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3  required-star">Province:</label>
                            <div :class="['col-md-8', allerros.province ? 'has-error' : '']">
                                <input type="text" v-model="company.province" class="form-control">
                                <span v-if="allerros.province" :class="['text-danger']">{{ allerros.province[0] }}</span>
                            </div>
                        </div>
                        </div>

                        <div class="division_area hidden" >
                            <div class="form-group col-md-12">
                                <label class="control-label col-md-3  required-star">Division</label>
                                <div :class="['col-md-8', allerros.division ? 'has-error' : '']">
                                    <select v-model="company.division" id="division" class="form-control input-sm division_req_field" >
                                        <option value="">Select One</option>
                                        <option v-for="option in divisionData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>
                                    </select>
                                    <span v-if="allerros.division" :class="['text-danger']">{{ allerros.division[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label col-md-3  required-star">District</label>
                                <div :class="['col-md-8', allerros.district ? 'has-error' : '']">
                                    <select v-model="company.district" id="district" class="form-control input-sm division_req_field" >
                                    </select>
                                    <span v-if="allerros.district" :class="['text-danger']">{{ allerros.district[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label col-md-3  required-star">Thana</label>
                                <div :class="['col-md-8', allerros.thana ? 'has-error' : '']">
                                    <select v-model="company.thana" id="thana" class="form-control input-sm division_req_field" >
                                    </select>
                                    <span v-if="allerros.thana" :class="['text-danger']">{{ allerros.thana[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <router-link to="/company-info" class="btn btn-default"><< Back</router-link>
                            <button type="submit" class="btn btn-primary pull-right">
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
    import {API} from '../../../custom.js';
    import $ from "jquery";
    const customClass = new API();
    export default {
        data: function () {
            return {
                allerros: [],
                success : false,
                company: {
                    country_id: '',
                    company_name: '',
                    state: '',
                    province: '',
                    division: '',
                    district: '',
                    thana: '',
                },
                countryData: [],
                divisionData: [],
                selected: null
            }
        },
        mounted() {
            $("#division").change(function () {
                var divisionId = $('#division').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "/settings/get-district-by-division-id",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select district</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#district").html(option);
                        $(self).next().hide();
                    }
                });
            });
            $("#district").change(function () {
                var districtId = $('#district').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "/settings/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select thana</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#thana").html(option);
                        $(self).next().hide();
                    }
                });
            });
            $(".isCountry").change(function () {
                var country_id = $(this).val();
                if(country_id == 18){ // if country == Bangladesh
                    $(".division_area").removeClass('hidden');
                    $(".state_area").addClass('hidden');

                    $(".division_req_field").addClass('required');
                    $(".state_req_field").removeClass('required');
                }else {
                    $(".state_area").removeClass('hidden');
                    $(".division_area").addClass('hidden');

                    $(".division_req_field").removeClass('required');
                    $(".state_req_field").addClass('required');
                }
            });
            $(".isCountry").trigger('change');
        },
        created () {
            this.getcountry();
            this.getdivision();
        },
// npm run watch
        methods: {
            saveForm() {
                var app = this;
                axios.post('/settings/store-company-info', this.company)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your company crate successfully.');
                            app.$router.push({path: '/company-info'});
                        }

                    } ).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
            },
            getcountry(){
                axios.get('/settings/get-country-name')
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.countryData = respdonse.data;
                    });

            },
            getdivision(){
                axios.get('/settings/get-division-name')
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.divisionData = respdonse.data;
                    });

            },
        }
    }
</script>
