<template>
  <div>
    <div class="card">
      <div class="card-header bg-primary">
        <h5><b>Service Details Form</b></h5>
      </div>
      <form @submit.prevent="saveForm()" id="service_details">
        <div class="card-body">
          <div class="col-md-9">
            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Process Type :</label>
              <div :class="['col-md-8', allerrors.process_type_id ? 'has-error' : '']">
                <select v-model="servicedetails.process_type_id" id="fiat" class="form-control input-sm required">
                  <option value="">Select One</option>
                  <option v-for="option in processData" :key="option.id" :value="option.id">{{ option.name }}</option>
                </select>
                <span v-if="allerrors.process_type_id" :class="['text-danger']">{{ allerrors.process_type_id[0] }}</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="control-label col-md-2 ">Attachment:</label>
              <div :class="['col-md-8', allerrors.files ? 'has-error' : '']">
                <input type="file" class="form-control" id="files" ref="files" v-on:change="onFileChange"/>
                <span v-if="allerrors.files" :class="['text-danger']">{{ allerrors.files[0] }}</span>
                <span class="help-block" style="margin-bottom: 0">[File Format: *.pdf | File size within 2 MB]</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Ordering:</label>
              <div :class="['col-md-8', allerrors.ordering ? 'has-error' : '']">
                <input type="number" v-model="servicedetails.ordering" class="form-control" required>
                <span v-if="allerrors.ordering" :class="['text-danger']">{{ allerrors.ordering[0] }}</span>
              </div>
            </div>
            <div class="row form-group ">
              <label  class="col-md-2  required-star">Active Status: </label>
              <div class="col-md-10">
                <label class="radio-inline"><input v-model="servicedetails.status" name="status" type="radio"
                                                    value="1"  class="required"> Active&nbsp;</label>
                <label class="radio-inline"><input v-model="servicedetails.status" name="status" type="radio"
                                                    value="0" class="required" checked> Inactive</label>
                <span v-if="allerrors.status" :class="['text-danger']">{{ allerrors.status[0] }}</span>
              </div>
            </div>
              <div class="col-md-12">
                  <router-link to="/service-details" class="btn btn-default"> Back</router-link>
                  <button type="submit" id="submit" class="btn btn-primary float-right">
                      <i class="fa fa-chevron-circle-right"></i> Save
                  </button>
              </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>


<script>

    // import {API} from '../../../custom.js';
    // const customClass = new API();

    export default {
        data: function () {
            return {
                allerrors: [],
                success: false,
                servicedetails: {
                    process_type_id: '',
                    attachment: '',
                    ordering: '',
                    status: '',
                },
                processData: [],
                selected: null
            }
        },
        mounted() {

        },
        created() {
            this.getprocess();
        },

        methods: {
            onFileChange(event) {
                this.servicedetails.attachment = event.target.files[0]
            },

            saveForm(){
                var app = this;
                var form = new FormData();
                form.append('attachment', this.servicedetails.attachment);
                form.append('process_type_id', this.servicedetails.process_type_id);
                form.append('ordering', this.servicedetails.ordering);
                form.append('status', this.servicedetails.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/service-details-store', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your service details create successfully.');
                            app.$router.push({path: '/service-details'});

                        }

                    }).catch((error) => {
                    this.allerrors = error.response.data.errors;
                });
            },

            getprocess(){
                var app =this
                axios.get('/settings/service-details-create')
                    .then(respdonse => {
                        app.processData = respdonse.data;
                    });

            },

        }

    }
</script>
