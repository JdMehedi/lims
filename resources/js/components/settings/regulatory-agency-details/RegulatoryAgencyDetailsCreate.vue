<template>
    <div class="panel panel-primary">
        <div class="panel-heading">Regulatory Agency Details Create Form</div>
        <form @submit.prevent="saveForm()" id="agencydetails">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2  required-star">Regulatory Agency :</label>
                        <div :class="['col-md-8', allerros.regulatory_agencies_id ? 'has-error' : '']">
                            <select v-model="agencydetails.regulatory_agencies_id" id="fiat" class="form-control input-sm" >
                                <option value="">Select One</option>
                                <option v-for="option in regulatoryAgency" :key="option.id" :value="option.id">{{option.name}}</option>
                            </select>
                            <span v-if="allerros.regulatory_agencies_id" :class="['text-danger']">{{ allerros.regulatory_agencies_id[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2 required-star">Service Name :</label>
                        <div :class="['col-md-8', allerros.service_name ? 'has-error' : '']">
                            <input type="text" v-model="agencydetails.service_name" class="form-control">
                            <span v-if="allerros.service_name" :class="['text-danger']">{{ allerros.service_name[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-md-2  required-star">Is Online: </label>
                        <div class="col-md-10">
                            <label><input v-model="agencydetails.is_online"  type="radio"
                                          :checked="agencydetails.is_online"  value="1" > YES</label>
                            <label><input v-model="agencydetails.is_online"  type="radio"
                                          :checked="agencydetails.is_online"  value="0" > NO </label>
                            <span v-if="allerros.is_online" :class="['text-danger']">{{ allerros.is_online[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-2">Methods of receiving service :</label>
                        <div :class="['col-md-8', allerros.method_of_recv_service ? 'has-error' : '']">
                            <editor
                                    v-model="agencydetails.method_of_recv_service"
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
                            <span v-if="allerros.method_of_recv_service" :class="['text-danger']">{{ allerros.method_of_recv_service[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-2">Who Get Service :</label>
                        <div :class="['col-md-8', allerros.who_get_service ? 'has-error' : '']">
                            <editor
                                    v-model="agencydetails.who_get_service"
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
                            <span v-if="allerros.who_get_service" :class="['text-danger']">{{ allerros.who_get_service[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-2">Documents :</label>
                        <div :class="['col-md-8', allerros.documents ? 'has-error' : '']">
                            <editor
                                    v-model="agencydetails.documents"
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
                            <span v-if="allerros.documents" :class="['text-danger']">{{ allerros.documents[0] }}</span>
                        </div>
                    </div>



                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2 ">Fees :</label>
                        <div :class="['col-md-8', allerros.fees ? 'has-error' : '']">
                            <input type="text" v-model="agencydetails.fees" class="form-control">
                            <span v-if="allerros.fees" :class="['text-danger']">{{ allerros.fees[0] }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-md-2  required-star">Status: </label>
                        <div class="col-md-10">
                            <label><input v-model="agencydetails.status" name="is_active" type="radio"
                                          :checked="agencydetails.status"  value="1" > Active</label>
                            <label><input v-model="agencydetails.status" name="is_active" type="radio"
                                          :checked="agencydetails.status"  value="0" > Inactive</label>
                            <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <router-link to="/home-page/regulatory-agency-details" class="btn btn-default"><i
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
                agencydetails: {
                    regulatory_agencies_id: '',
                    service_name: '',
                    is_online: '',
                    method_of_recv_service: '',
                    who_get_service: '',
                    documents: '',
                    fees: '',
                    status: '',

                },
                regulatoryAgency:[],
                selected: null

            }
        },
        components: {
            'editor': Editor
        },
        mounted() {

        },
        created () {
            this.getRegulatoryAgency();
        },

        methods: {
            saveForm() {
                var app = this;
                var form = new FormData();
                form.append('regulatory_agencies_id',this.agencydetails.regulatory_agencies_id);
                form.append('service_name',this.agencydetails.service_name);
                form.append('is_online',this.agencydetails.is_online);
                form.append('method_of_recv_service',this.agencydetails.method_of_recv_service);
                form.append('who_get_service',this.agencydetails.who_get_service);
                form.append('documents',this.agencydetails.documents);
                form.append('fees',this.agencydetails.fees);
                form.append('status',this.agencydetails.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/store-regulatory-agency-details', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your new regulatory agency create successfully.');
                            app.$router.push({path: '/home-page/regulatory-agency-details'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            },
            getRegulatoryAgency(){
                axios.get('/settings/home-page/regulatory-agency-details/regulatory-agency')
                    .then(respdonse => {
                        this.regulatoryAgency = respdonse.data;
                    });

            },

        }
    }
</script>
