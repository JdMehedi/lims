<template>
        <div class="panel panel-primary">
            <div class="panel-heading">New Industrial Advisor</div>
            <form @submit.prevent="saveForm()" id="advisor">
                <div class="panel-body">
                    <div class="col-md-11">
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Name (English):</label>
                        <div :class="['col-md-9', allerros.name_en ? 'has-error' : '']">
                            <input type="text" v-model="advisor.name_en" class="form-control">
                            <span v-if="allerros.name_en" :class="['text-danger']">{{ allerros.name_en[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Name (বাংলা ):</label>
                        <div :class="['col-md-9', allerros.name ? 'has-error' : '']">
                            <input type="text" v-model="advisor.name" class="form-control">
                            <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Designation (English):</label>
                        <div :class="['col-md-9', allerros.designation_en ? 'has-error' : '']">
                            <input type="text" v-model="advisor.designation_en" class="form-control">
                            <span v-if="allerros.designation_en" :class="['text-danger']">{{ allerros.designation_en[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3 required-star">Designation (বাংলা ):</label>
                        <div :class="['col-md-9', allerros.designation ? 'has-error' : '']">
                            <input type="text" v-model="advisor.designation" class="form-control">
                            <span v-if="allerros.designation" :class="['text-danger']">{{ allerros.designation[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Office Name (English):</label>
                        <div :class="['col-md-9', allerros.office_en ? 'has-error' : '']">
                            <input type="text" v-model="advisor.office_en" class="form-control">
                            <span v-if="allerros.office_en" :class="['text-danger']">{{ allerros.office_en[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3 required-star">Office Name (বাংলা ):</label>
                        <div :class="['col-md-9', allerros.office ? 'has-error' : '']">
                            <input type="text" v-model="advisor.office" class="form-control">
                            <span v-if="allerros.office" :class="['text-danger']">{{ allerros.office[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3 required-star">Details ( English ):</label>
                        <div :class="['col-md-9', allerros.details_en ? 'has-error' : '']">
                            <editor
                                    v-model="advisor.details_en"
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
                            <span v-if="allerros.details_en" :class="['text-danger']">{{ allerros.details_en[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Details (বাংলা ):</label>
                        <div :class="['col-md-9', allerros.details ? 'has-error' : '']">
                            <editor
                                    v-model="advisor.details"
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
                            <span v-if="allerros.details" :class="['text-danger']">{{ allerros.details[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3 required-star">Image:</label>
                        <div :class="['col-md-9', allerros.files ? 'has-error' : '']">
                            <input type="file" id="files" ref="files" class="form-control" v-on:change="onFileChange"/>
                            <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif | Max size 1 MB]</span>
                            <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3 required-star">Mobile No:</label>
                        <div :class="['col-md-9', allerros.mobile_no ? 'has-error' : '']">
                            <input type="text" v-model="advisor.mobile_no" class="form-control">
                            <span v-if="allerros.mobile_no" :class="['text-danger']">{{ allerros.mobile_no[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-3  required-star">Email Address:</label>
                        <div :class="['col-md-9', allerros.email ? 'has-error' : '']">
                            <input type="text" v-model="advisor.email" class="form-control">
                            <span v-if="allerros.email" :class="['text-danger']">{{ allerros.email[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-md-3 required-star">Status: </label>
                        <div class="col-md-9">
                            <label><input v-model="advisor.status" name="is_active" type="radio"
                                          :checked="advisor.status"  value="1" > Active</label>
                            <label><input v-model="advisor.status" name="is_active" type="radio"
                                          :checked="advisor.status"  value="0" > Inactive</label>
                            <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                        </div>
                    </div>

                </div>
                </div>
                <div class="panel-footer">
                    <div class="pull-left">
                        <router-link to="/home-page/industrial-advisor" class="btn btn-default"><i
                                class="fa fa-chevron-circle-left"></i> Back
                        </router-link>
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
</template>

<script>
    import Editor from "@tinymce/tinymce-vue";
    export default {
        data: function () {
            return {
                allerros: [],
                success : false,
                advisor: {
                    name: '',
                    name_en: '',
                    designation_en: '',
                    designation: '',
                    office_en: '',
                    office: '',
                    details: '',
                    details_en: '',
                    mobile_no: '',
                    email: '',
                    status: '',
                },

            }
        },
        components: {
            'editor': Editor
        },
        mounted() {

        },

        methods: {
            onFileChange(event) {
                this.advisor.imagefile = event.target.files[0]

            },

            saveForm() {
                var app = this;
                var form = new FormData();
                form.append('profile_photo',this.advisor.imagefile);
                form.append('name',this.advisor.name);
                form.append('name_en',this.advisor.name_en);
                form.append('designation',this.advisor.designation);
                form.append('designation_en',this.advisor.designation_en);
                form.append('office_en',this.advisor.office_en);
                form.append('office',this.advisor.office);
                form.append('details',this.advisor.details);
                form.append('details_en',this.advisor.details_en);
                form.append('mobile_no',this.advisor.mobile_no);
                form.append('email',this.advisor.email);
                form.append('status',this.advisor.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/store-industrial-advisor', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your news crate successfully.');
                            app.$router.push({path: '/home-page/industrial-advisor'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            },

        }
    }
</script>
