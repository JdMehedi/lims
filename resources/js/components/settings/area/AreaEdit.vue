<template>
    <div>
        <div class="card card card-magenta border border-magenta">
            <div class="card-header"><h5 class="card-title pt-2 pb-2">Area form</h5></div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()" id="area-info">
                        <div class="row form-group">
                            <label class="col-md-3  required-star">Area Type: </label>
                            <div class="col-md-9">
                                <label><input v-model="area.area_type"  type="radio"
                                                value="3" class="required area_type"> Thana &nbsp;</label>
                                <label><input v-model="area.area_type"  type="radio"
                                               value="2" class="required area_type"> District &nbsp;</label>
                                <label><input v-model="area.area_type"  type="radio"
                                                value="1" class="required area_type"> Division</label>
                                <span v-if="allerros.area_type" :class="['text-danger']">{{ allerros.area_type[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group" id="division_div" v-if="area.area_type!='1'">
                            <label class="control-label col-md-3  required-star">Division:</label>
                            <div :class="['col-md-7', allerros.division ? 'has-error' : '']">
                                <select v-model="area.division"  class="form-control input-sm" id="division" >
                                    <option v-for="option in divisionData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>
                                </select>
                                <span v-if="allerros.division" :class="['text-danger']">{{ allerros.division[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group" id="district_div">
                            <label class="control-label col-md-3  required-star">District:</label>
                            <div :class="['col-md-7', allerros.district ? 'has-error' : '']">

                                <select v-model="area.district"  class="form-control input-sm" id="district">
                                    <option v-for="option in districtData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>


                                </select>
                                <span v-if="allerros.district" :class="['text-danger']">{{ allerros.district[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Area Name (English):</label>
                            <div :class="['col-md-7', allerros.area_nm ? 'has-error' : '']">
                                <input type="text" v-model="area.area_nm" class="form-control">
                                <span v-if="allerros.area_nm" :class="['text-danger']">{{ allerros.area_nm[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Area Name (Bangla):</label>
                            <div :class="['col-md-7', allerros.area_nm_ban ? 'has-error' : '']">
                                <input type="text" v-model="area.area_nm_ban" class="form-control">
                                <span v-if="allerros.area_nm_ban" :class="['text-danger']">{{ allerros.area_nm_ban[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Process Name:</label>
                            <div :class="['col-md-7']">
                                <select class="form-control input-sm" id="process_name" >
                                    <option value="">Select One</option>
                                    <option value="1" selected>ISP License Issue</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Application Limit:</label>
                            <div :class="['col-md-7', allerros.app_limit ? 'has-error' : '']">
                                <input type="text" v-model="area.app_limit" class="form-control">
                                <span v-if="allerros.app_limit" :class="['text-danger']">{{ allerros.app_limit[0] }}</span>
                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/area-list" class="btn btn-default"> Back</router-link>
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
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                area: {
                    area_type: '',
                    division: '',
                    district: '',
                    area_nm: '',
                    area_nm_ban:'',
                    app_limit: '',
                },
                divisionData: [],
                districtData:[],
                is_division: 0,

            }

        },
        created() {
            this.getdivision();
            this.getDistrict();
            // console.log(ddd)
            let app = this;
            let id = app.$route.params.id;
            app.id = id;
            axios.get('/settings/edit-area/' + id)
                .then(function (resp) {
                    app.area = resp.data;
                    console.log(app.area);
                    var type = app.area.area_type;
                    app.area.area_type = type;
                    if (type == 1) {
                        $('#division_div').hide();
                        $('#division').removeClass('required');
                        $('#district_div').hide();
                        $('#district').removeClass('required');
                    } else if (type == 2) {
                        app.area.division = resp.data.pare_id;
                        app.area.district = resp.data.area_id;
                        $('#division_div').show();
                        $('#division').addClass('required');
                        $('#district_div').hide();
                        $('#district').removeClass('required');
                    } else if (type == 3) {
                        app.area.division = resp.data.division_id;
                        app.area.district = resp.data.pare_id;
                        $('#division_div').show();
                        $('#division').addClass('required');
                        $('#district_div').show();
                        $('#district').addClass('required');
                    }
                    // console.log(app.area)
                })
                .catch(function () {
                    alert("Could not load your Area")
                });
        },
        mounted() {
            $("#division").change(function () {
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                var divisionId = $('#division').val();
                $("#loaderImg").html("Loading..");
                $.ajax({
                    type: "GET",
                    url: "/settings/get-district-by-division-id",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#district").html(option);
                        self.next().hide();
                    }
                });
            });

            $('.area_type').change(function () {

                var type = $('.area_type:checked').val();
                if (type == 1) {
                    $('#division_div').hide();
                    $('#division').removeClass('required');
                    $('#district_div').hide();
                    $('#district').removeClass('required');
                } else if (type == 2) {
                    $('#division_div').show();
                    $('#division').addClass('required');
                    $('#district_div').hide();
                    $('#district').removeClass('required');
                } else if (type == 3) {
                    $('#division_div').show();
                    $('#division').addClass('required');
                    $('#district_div').show();
                    $('#district').addClass('required');
                }
            });
            $(document).ready(function () {
                $("#area-info").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
        },

        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.area;
                axios.patch('/settings/update-area/' + app.id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your area update successfully.');
                            app.$router.push({path: '/area-list'});
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
            getdivision(){
                var app = this;
                axios.get('/settings/get-division-name')
                    .then(respdonse => {
                        app.divisionData = respdonse.data;
                    });

            },
            getDistrict(){
                var app = this;
                axios.get('/settings/get-district')
                    .then(respdonse => {
                        app.districtData = respdonse.data;
                    });

            },
        }
    }
</script>
