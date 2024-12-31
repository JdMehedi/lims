<template>
    <div>
        <div class="card">
            <div class="card-header bg-primary">Service Details Form</div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()" id="service_details">
                        <input type="hidden"  v-model="servicedetail.id">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Process Type :</label>
                            <div :class="['col-md-8', allerros.process_type_id ? 'has-error' : '']">
                                <select v-model="servicedetail.process_type_id" id="fiat" name="process_type" class="form-control input-sm" >
                                    <option value="">Select One</option>
                                    <option v-for="option in processData" :key="option.id" :value="option.id">{{option.name}}</option>
                                </select>
                                <span v-if="allerros.process_type_id" :class="['text-danger']">{{ allerros.process_type_id[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2 ">Attachment:</label>
                            <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                                <input type="file" id="files" ref="files"  v-on:change="onFileChange"/>
                                <a v-bind:href="'/'+ servicedetail.attachment">{{servicedetail.attachment}}</a>
                                <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                                <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | File size within 2 MB]</span><br/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Ordering:</label>
                            <div :class="['col-md-8', allerros.ordering ? 'has-error' : '']">
                                <input type="number" v-model="servicedetail.ordering" class="form-control" required>
                                <span v-if="allerros.ordering" :class="['text-danger']">{{ allerros.ordering[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group ">
                            <label for="status" class="col-md-2  required-star">Active Status: </label>
                            <div class="col-md-10">
                                <label><input v-model="servicedetail.status" name="status" type="radio"
                                                value="1" id="status" class="required"> Active&nbsp;</label>
                                <label><input v-model="servicedetail.status" name="status" type="radio"
                                               value="0" id="status" class="required"> Inactive</label>
                                <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <router-link to="/service-details" class="btn btn-default"> Back</router-link>
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
    // import {API} from '../../../custom.js';
    // const customClass = new API();
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                servicedetail: {
                    process_type_id: '',
                    attachment: '',
                    ordering:'',
                    status:'',
                    id:'',
                },
                processData: [],
                selected: null
            }
        },
        mounted() {

        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            app.id = id;
            axios.get('/settings/service-details-edit/' + id)
                .then(function (resp) {
                    app.servicedetail = resp.data;
                    console.log(app.servicedetail);
                })
                .catch(function () {
                    alert("Could not load your Service Details")
                });
            app.getprocess();
        },

        methods: {
            onFileChange(event) {
                this.servicedetail.attachment = event.target.files[0]
            },
            saveForm() {
                var app = this;
                var form = new FormData();
                form.append('attachment',this.servicedetail.attachment);
                form.append('process_type_id',this.servicedetail.process_type_id);
                form.append('ordering',this.servicedetail.ordering);
                form.append('status',this.servicedetail.status);
                form.append('id',this.servicedetail.id);
                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/service-details-update',form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.replace('/service-details');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
            },


            getprocess(){
                axios.get('/settings/service-details-create')
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.processData = respdonse.data;
                        // console.log(this.processData);
                    });

            },
        }
    }
</script>
