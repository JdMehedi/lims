<template>
    <div class="panel panel-primary">
        <div class="panel-heading">Regulatory Agency Create Form</div>
        <form @submit.prevent="saveForm()" id="agency">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">Name :</label>
                        <div :class="['col-md-8', allerros.name ? 'has-error' : '']">
                            <input type="text" v-model="agency.name" class="form-control">
                            <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Contact Name :</label>
                        <div :class="['col-md-8', allerros.contact_name ? 'has-error' : '']">
                            <input type="text" v-model="agency.contact_name" class="form-control">
                            <span v-if="allerros.contact_name" :class="['text-danger']">{{ allerros.contact_name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2 ">Designation :</label>
                        <div :class="['col-md-8', allerros.designation ? 'has-error' : '']">
                            <input type="text" v-model="agency.designation" class="form-control">
                            <span v-if="allerros.designation" :class="['text-danger']">{{ allerros.designation[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Mobile :</label>
                        <div :class="['col-md-8', allerros.mobile ? 'has-error' : '']">
                            <input type="text" v-model="agency.mobile" class="form-control">
                            <span v-if="allerros.mobile" :class="['text-danger']">{{ allerros.mobile[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Phone :</label>
                        <div :class="['col-md-8', allerros.phone ? 'has-error' : '']">
                            <input type="text" v-model="agency.phone" class="form-control">
                            <span v-if="allerros.phone" :class="['text-danger']">{{ allerros.phone[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Email :</label>
                        <div :class="['col-md-8', allerros.email ? 'has-error' : '']">
                            <input type="text" v-model="agency.email" class="form-control">
                            <span v-if="allerros.email" :class="['text-danger']">{{ allerros.email[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">URL :</label>
                        <div :class="['col-md-8', allerros.url ? 'has-error' : '']">
                            <input type="text" v-model="agency.url" class="form-control">
                            <span v-if="allerros.url" :class="['text-danger']">{{ allerros.url[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-md-2  required-star">Agency Type: </label>
                        <div class="col-md-10">
                            <label><input v-model="agency.agency_type" name="type" type="radio"
                                          :checked="agency.agency_type"  value="ipa" > IPA</label>
                            <label><input v-model="agency.agency_type" name="type" type="radio"
                                          :checked="agency.agency_type"  value="clp" > CLP </label>
                            <label><input v-model="agency.agency_type" name="type" type="radio"
                                          :checked="agency.agency_type"  value="utility" > Ulitily </label>
                            <span v-if="allerros.agency_type" :class="['text-danger']">{{ allerros.agency_type[0] }}</span>
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="col-md-2">Description :</label>
                        <div :class="['col-md-8', allerros.description ? 'has-error' : '']">
                            <editor
                                    v-model="agency.description"
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
                            <span v-if="allerros.description" :class="['text-danger']">{{ allerros.description[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Order:</label>
                        <div :class="['col-md-8', allerros.order ? 'has-error' : '']">
                            <input type="number" v-model="agency.order" class="form-control" size="10x5" min="0">
                            <span v-if="allerros.order" :class="['text-danger']">{{ allerros.order[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-md-2  required-star">Status: </label>
                        <div class="col-md-10">
                            <label><input v-model="agency.status" name="is_active" type="radio"
                                          :checked="agency.status"  value="1" > Active</label>
                            <label><input v-model="agency.status" name="is_active" type="radio"
                                          :checked="agency.status"  value="0" > Inactive</label>
                            <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <router-link to="/home-page/regulatory-agency" class="btn btn-default"><i
                            class="fa fa-chevron-circle-left"></i> Back</router-link>
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
                agency: {
                    name: '',
                    contact_name: '',
                    designation: '',
                    mobile: '',
                    phone: '',
                    email: '',
                    description: '',
                    url: '',
                    agency_type: '',
                    order: '',
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
            saveForm() {
                var app = this;
                var form = new FormData();
                form.append('name',this.agency.name);
                form.append('contact_name',this.agency.contact_name);
                form.append('designation',this.agency.designation);
                form.append('mobile',this.agency.mobile);
                form.append('phone',this.agency.phone);
                form.append('email',this.agency.email);
                form.append('description',this.agency.description);
                form.append('url',this.agency.url);
                form.append('agency_type',this.agency.agency_type);
                form.append('order',this.agency.order);
                form.append('status',this.agency.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/store-regulatory-agency', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your new regulatory agency create successfully.');
                            app.$router.push({path: '/home-page/regulatory-agency'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            },

        }
    }
</script>
