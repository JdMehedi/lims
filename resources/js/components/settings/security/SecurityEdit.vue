<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">
                    Edit security profile
                </h5>
            </div>
            <div class="card-body">
                <div class="col-md-10">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Profile name:</label>
                            <div :class="['col-md-7', allerros.profile_name ? 'has-error' : '']">
                                <input type="text" v-model="security.profile_name" class="form-control">
                                <span v-if="allerros.profile_name" :class="['text-danger']">{{
                                        allerros.profile_name[0]
                                    }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3">Ip address:</label>
                            <div :class="['col-md-7', allerros.allowed_remote_ip ? 'has-error' : '']">
                                <input type="text" v-model="security.allowed_remote_ip" class="form-control">
                                <small>Example: 175.29.169.26,175.29.169.27</small>
                                <span v-if="allerros.allowed_remote_ip"
                                      :class="['text-danger']">{{ allerros.allowed_remote_ip[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3">Email address:</label>
                            <div :class="['col-md-7', allerros.user_email ? 'has-error' : '']">
                                <input type="text" v-model="security.user_email" class="form-control isEmail">
                                <span v-if="allerros.user_email" :class="['text-danger']">{{
                                        allerros.user_email[0]
                                    }}</span>
                            </div>
                        </div>
<!--                        <div class="row form-group">-->
<!--                            <label class="control-label col-md-2  required-star">Weekly off days:</label>-->
<!--                            <div :class="['col-md-8', allerros.week_off_days ? 'has-error' : '']">-->
<!--                                <input type="text" v-model="security.week_off_days" class="form-control">-->

<!--                                <span v-if="allerros.week_off_days"-->
<!--                                      :class="['text-danger']">{{ allerros.week_off_days[0] }}</span>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="row form-group">
                            <label class="control-label col-md-3 required-star">Weekly off days:</label>

                            <div :class="['col-md-7', allerros.week_off_days ? 'has-error' : '']">
                                <Multiselect mode="tags" :searchable="true" v-model="value" :options="options"/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  required-star">Working hour start time:</label>
                            <div :class="['col-md-7', allerros.work_hour_start ? 'has-error' : ''] ">
                                <Datepicker  v-model="security.work_hour_start" timePicker placeholder="Select Time" :is24="false"/>
                                <span v-if="allerros.work_hour_start"
                                      :class="['text-danger']">{{ allerros.work_hour_start[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3 required-star">Working hour end time:</label>
                            <div :class="['col-md-7', allerros.work_hour_end ? 'has-error' : ''] ">
                                <Datepicker v-model="security.work_hour_end" timePicker placeholder="Select Time" :is24="false"/>
                                <span v-if="allerros.work_hour_end"
                                      :class="['text-danger']">{{ allerros.work_hour_end[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3 required-star">Alert message:</label>
                            <div :class="['col-md-7', allerros.alert_message ? 'has-error' : ''] ">
                                <input type="text" v-model="security.alert_message" class="form-control">
                                <span v-if="allerros.alert_message"
                                      :class="['text-danger']">{{ allerros.alert_message[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="active_status" class="col-md-3 required-star">Active Status: </label>
                            <div class="col-md-7">
                                <label><input v-model="security.active_status" name="active_status" type="radio"
                                              value="yes" id="active_status">
                                    Active &nbsp;</label>
                                <label><input v-model="security.active_status" name="active_status" type="radio"
                                              value="no" id="active_status"> Inactive</label>
                                <span v-if="allerros.active_status"
                                      :class="['text-danger']">{{ allerros.active_status[0] }}</span>

                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/security" class="btn btn-default">Back</router-link>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i>Save information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Multiselect from '@vueform/multiselect'
import {ref} from "vue";
// import {API} from '../../../custom.js';
// const customClass = new API();
export default {
    components: {
        Multiselect, Datepicker
    },
    setup() {
        const time = ref();
        const time2 = ref();

        return {
            time,
            time2,
        }
    },
    data: function () {
        return {
            $id: null,
            value: [],
            // date22 : { "hours": 18, "minutes": 35, "seconds": 0 },
            options: ['SAT', 'SUN', 'MON', 'WED', 'THU', 'FRI'],
            allerros: [],
            security: {
                profile_name: '',
                allowed_remote_ip: '',
                user_email: '',
                week_off_days: '',
                work_hour_start: '',
                work_hour_end: '',
                active_status: '',
                alert_message: '',
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
        axios.get('/settings/edit-security/' + id)
            .then(function (resp) {
                app.value = resp.data.week_off_days.split(',');
                var timeArray = resp.data.work_hour_start.split(':');
                var timeArrayEnd = resp.data.work_hour_end.split(':');
                console.log(resp.data);


               app.security = resp.data;
                app.security.work_hour_start = {"hours": timeArray[0], "minutes": timeArray[1], "seconds": 0}
                app.security.work_hour_end = {"hours": timeArrayEnd[0], "minutes": timeArrayEnd[1], "seconds": 0}
            })
            .catch(function () {
                alert("Could not load your security profile")
            });

    },

    methods: {
        saveForm() {
            var app = this;
            var newCompany = app.security;


            var time = this.security.work_hour_start.hours + ':' + this.security.work_hour_start.minutes.toString();
            var timeEnd = this.security.work_hour_end.hours + ':' + this.security.work_hour_end.minutes.toString();
            let fd = new FormData();
            fd.append("profile_name", this.security.profile_name);
            fd.append("allowed_remote_ip", this.security.allowed_remote_ip);
            fd.append("user_email", this.security.user_email);
            // fd.append("week_off_days", this.security.week_off_days);
            fd.append("work_hour_start", time);
            fd.append("work_hour_end", timeEnd);
            fd.append("active_status", this.security.active_status);
            fd.append("alert_message", this.security.alert_message);

            this.value.forEach((item) => {
                fd.append('week_off_days[]', item);
            });


            axios.post('/settings/update-security/' + app.id, fd)
                .then(function (resp) {
                    if (resp.data.status === true) {
                        app.$toast.success('Your data update successfully.');
                        app.$router.push('/security');
                    }
                }).catch((error) => {
                app.allerros = error.response.data.errors;
            });
        },
    }
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
