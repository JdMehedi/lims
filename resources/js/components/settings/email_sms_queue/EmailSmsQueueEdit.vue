<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">
                    Edit Email SMS Queue
                </h5>
            </div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Tracking no :</label>
                            <div :class="['col-md-9', allerros.tracking_no ? 'has-error' : '']">
                                <input type="text" v-model="emailsmsqueue.tracking_no" class="form-control">
                                <span v-if="allerros.tracking_no" :class="['text-danger']">{{ allerros.tracking_no[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">SMS To :</label>
                            <div :class="['col-md-9', allerros.sms_to ? 'has-error' : '']">
                                <input type="text" v-model="emailsmsqueue.sms_to" class="form-control">
                                <span v-if="allerros.sms_to" :class="['text-danger']">{{ allerros.sms_to[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">SMS Content :</label>
                            <div :class="['col-md-9', allerros.sms_content ? 'has-error' : '']">
                                <textarea v-model="emailsmsqueue.sms_content" class="form-control" rows= 4, cols= 54></textarea>
                                <span v-if="allerros.sms_content"
                                      :class="['text-danger']">{{ allerros.sms_content[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="sms_status" class="col-md-3 required-star">SMS sending status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="emailsmsqueue.sms_status" name="sms_status" type="radio"
                                              :checked="emailsmsqueue.sms_status == 1" checked value="1" id="sms_status"> Yes &nbsp;</label>
                                <label><input v-model="emailsmsqueue.sms_status" name="sms_status" type="radio"
                                              :checked="emailsmsqueue.sms_status == 1" checked value="0" id="sms_status"> No</label>
                                <span v-if="allerros.sms_status"
                                      :class="['text-danger']">{{ allerros.sms_status[0] }}</span>

                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Email to :</label>
                            <div :class="['col-md-9', allerros.email_to ? 'has-error' : '']">
                                <input type="text" v-model="emailsmsqueue.email_to" class="form-control">
                                <span v-if="allerros.email_to"
                                      :class="['text-danger']">{{ allerros.email_to[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Email CC :</label>
                            <div :class="['col-md-9', allerros.email_cc ? 'has-error' : '']">
                                <input type="text" v-model="emailsmsqueue.email_cc" class="form-control">
                                <span v-if="allerros.email_cc"
                                      :class="['text-danger']">{{ allerros.email_cc[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Email subject :</label>
                            <div :class="['col-md-9', allerros.email_subject ? 'has-error' : '']">
                                <input type="text" v-model="emailsmsqueue.email_subject" class="form-control">
                                <span v-if="allerros.email_subject"
                                      :class="['text-danger']">{{ allerros.email_subject[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-3  ">Email content :</label>
                            <div :class="['col-md-9', allerros.email_content ? 'has-error' : '']">
                                <editor
                                        v-model="emailsmsqueue.email_content"
                                        api-key="5tyznzq0zx85ayto1vep7l7jy3d4hsyf8mxev8jwuq5zqqwk"
                                        :init="{
                                         height: 300,
                                         menubar: true,
                                         plugins: [
                                           'advlist autolink lists link image charmap print preview anchor',
                                           'searchreplace visualblocks code fullscreen',
                                           'insertdatetime media table paste code help wordcount'
                                         ],
                                         toolbar:
                                           'undo redo | formatselect | bold italic backcolor | \
                                           alignleft aligncenter alignright alignjustify | \
                                           bullist numlist outdent indent | removeformat | help'
                                       }"
                                />
                                <span v-if="allerros.email_content"
                                      :class="['text-danger']">{{ allerros.email_content[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="sms_status" class="col-md-3 required-star">Email sending status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="emailsmsqueue.email_status" name="email_status" type="radio"
                                              :checked="emailsmsqueue.email_status == 1" checked value="1" id="email_status"> Yes &nbsp;</label>
                                <label><input v-model="emailsmsqueue.email_status" name="email_status" type="radio"
                                              :checked="emailsmsqueue.email_status == 1" checked value="0" id="email_status"> No</label>
                                <span v-if="allerros.email_status"
                                      :class="['text-danger']">{{ allerros.email_status[0] }}</span>

                            </div>
                        </div>


                        <div class="col-md-12">
                            <router-link to="/email-sms-queue" class="btn btn-default">Back</router-link>
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
    import Editor from '@tinymce/tinymce-vue'

    import {API} from '../../../custom.js';
    const customClass = new API();

    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                emailsmsqueue: {
                    tracking_no: '',
                    reg_key: '',
                    sms_to: '',
                    sms_content: '',
                    sms_status: '',
                    email_to: '',
                    email_cc: '',
                    email_subject: '',
                    email_content: '',
                    email_status: '',
                },
            }
        },
        components: {
            'editor': Editor
        },


        mounted() {
            // customClass.onlyNumber();
            // customClass.isEmail();
        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            app.id = id;
            axios.get('/settings/email-sms-queue-edit/' + id)
                .then(function (resp) {
                    app.emailsmsqueue = resp.data;
                })
                .catch(function () {
                    alert("Could not load your Email SMS Queue")
                });
        },
        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.emailsmsqueue;
                // console.log(app.id);
                var app =this;
                axios.patch('/settings/update-email-sms-queue/' + app.id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/email-sms-queue');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
