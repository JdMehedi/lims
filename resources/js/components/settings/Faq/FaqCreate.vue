<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">Area form</div>
            <div class="panel-body">
                <div class="col-md-9">
                <form @submit.prevent="saveForm()">
                    <div class="form-group col-md-12">
                        <label class="col-md-2  required-star">Area Type: </label>
                        <div class="col-md-10">
                            <label><input v-model="area.area_type" name="area_type" type="radio"
                                          :checked="area.area_type"  value="3" > Thana</label>
                            <label><input v-model="area.area_type" name="area_type" type="radio"
                                          :checked="area.area_type"  value="2" > District</label>
                            <label><input v-model="area.area_type" name="area_type" type="radio"
                                          :checked="area.area_type"  value="1" > Division</label>

                            <span v-if="allerros.area_type" :class="['text-danger']">{{ allerros.area_type[0] }}</span>
                        </div>

                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">Division:</label>
                        <div :class="['col-md-8', allerros.division ? 'has-error' : '']">
                            <select v-model="area.division"  class="form-control input-sm" >
                                <option value="">Select One</option>
                                <option v-for="option in divisionData" :key="option.id" :value="option.area_id"  @change='getdistrict()'>{{option.area_nm}}</option>
                            </select>
                            <span v-if="allerros.details" :class="['text-danger']">{{ allerros.details[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">District:</label>
                        <div :class="['col-md-8', allerros.district ? 'has-error' : '']">
                            <select v-model="area.district"  class="form-control input-sm" >
                                <option value="">Select One</option>
                                <option v-for="option in districtData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>


                            </select>
                            <span v-if="allerros.district" :class="['text-danger']">{{ allerros.district[0] }}</span>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">Area Name (English):</label>
                        <div :class="['col-md-8', allerros.area_nm ? 'has-error' : '']">
                            <input type="text" v-model="area.area_nm" class="form-control">
                            <span v-if="allerros.area_nm" :class="['text-danger']">{{ allerros.area_nm[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">Area Name (Bangla):</label>
                        <div :class="['col-md-8', allerros.area_nm_ban ? 'has-error' : '']">
                            <input type="text" v-model="area.area_nm_ban" class="form-control">
                            <span v-if="allerros.area_nm_ban" :class="['text-danger']">{{ allerros.area_nm_ban[0] }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <router-link to="/area-list" class="btn btn-default"><< Back</router-link>
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
    const customClass = new API();
    export default {

        data: function () {
            return {
                allerros: [],
                success : false,
                area: {
                    area_type: '',
                    division: '',
                    district: '',
                    area_nm: '',
                    area_nm_ban:'',
                },
                divisionData: [],
                districtData:[],
                selected: null
            }
        },
        mounted() {

            // customClass.isEmail();
        },
        created () {
            this.getdivision();
           // this.getdistrict();
        },

        methods: {
            saveForm() {
                var app = this;
                axios.post('/settings/store-area', this.area)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your area crate successfully.');
                            app.$router.push({path: '/area-list'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            },
            getdivision(){
                axios.get('/settings/get-division-name')
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.divisionData = respdonse.data;
                    });

            },
            getdistrict(){
                axios.get('/settings/get-district-name',
                    {
                    params: {division: this.division}
                    })
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.districtData = respdonse.data;
                    });
            },
        }
    }
</script>
