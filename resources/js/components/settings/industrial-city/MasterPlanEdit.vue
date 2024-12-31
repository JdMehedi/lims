<template>
  <div>
    <div class="card card-magenta border border-magenta">
      <div class="card-header">
        <h5 class="card-title pt-2 pb-2"><strong>Edit Master Plan</strong></h5>
      </div>

      <form @submit.prevent="updateMasterPlan()" id="masterPlanEditForm">
        <div class="card-body">
          <div class="col-md-10">

            <div class="row form-group">
              <label class="col-md-3 required-star">Name (English):</label>
              <div :class="['col-md-9', masterPlanFormErrors.name_en ? 'has-error' : '']">
                <input type="text" v-model="masterPlanForm.name_en" class="form-control">
                <span v-if="masterPlanFormErrors.name_en" :class="['text-danger']">{{
                    masterPlanFormErrors.name_en[0]
                  }}</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-md-3 required-star">Name (বাংলা):</label>
              <div :class="['col-md-9', masterPlanFormErrors.name ? 'has-error' : '']">
                <input type="text" v-model="masterPlanForm.name" class="form-control">
                <span v-if="masterPlanFormErrors.name" :class="['text-danger']">{{
                    masterPlanFormErrors.name[0]
                  }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Remarks (English):</label>
              <div :class="['col-md-9', masterPlanFormErrors.remarks_en ? 'has-error' : '']">
                <textarea v-model="masterPlanForm.remarks_en" class="form-control"></textarea>
                <span v-if="masterPlanFormErrors.remarks_en"
                      :class="['text-danger']">{{ masterPlanFormErrors.remarks_en[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Remarks (বাংলা):</label>
              <div :class="['col-md-9', masterPlanFormErrors.remarks ? 'has-error' : '']">
                <textarea v-model="masterPlanForm.remarks" class="form-control"></textarea>
                <span v-if="masterPlanFormErrors.remarks" :class="['text-danger']">{{
                    masterPlanFormErrors.remarks[0]
                  }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3">Document:</label>
              <div :class="['col-md-7', masterPlanFormErrors.document ? 'has-error' : '']">
                <input type="file" id="files" ref="files" class="form-control" v-on:change="setMasterPlanDocument"/>
                <span class="help-block">[File Format: *.jpg/ .jpeg/ .png/ .gif/ .pdf | Max size 2 MB]</span>
                <span v-if="masterPlanFormErrors.document" :class="['text-danger']">{{
                    masterPlanFormErrors.document[0]
                  }}</span>
              </div>
              <div class="col-md-2">
                <a class="btn btn-info" target="_blank" v-bind:href="'/'+ masterPlanForm.document"><i
                    class="fa fa-file-image-o"></i> Open document</a>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Status: </label>
              <div class="col-md-9">
                <label><input v-model="masterPlanForm.status" name="is_active" type="radio"
                               value="1"> Active&nbsp;</label>
                <label><input v-model="masterPlanForm.status" name="is_active" type="radio"
                               value="0"> Inactive</label>
                <span v-if="masterPlanFormErrors.status" :class="['text-danger']">{{
                    masterPlanFormErrors.status[0]
                  }}</span>
              </div>
            </div>

            <div class="col-md-12">
                <router-link to="/home-page/industrial-city" class="btn btn-default"><i
                    class="fa fa-chevron-circle-left"></i> Back
                </router-link>
                <button type="submit" class="btn btn-primary float-right">
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
export default {
  data: function () {
    return {
      id: null,
      masterPlanForm: {
        name: '',
        name_en: '',
        remarks: '',
        remarks_en: '',
        document: '',
        status: ''
      },
      masterPlanFormErrors: {}
    }
  },
  components: {},

  mounted() {
  },
  created() {
    let app = this;
    let id = app.$route.params.id;
    app.id = id;
    axios.get('/settings/home-page/industrial-city/master-plan/edit/' + id)
        .then(function (resp) {
          app.masterPlanForm = resp.data.masterPlan;
          app.city_id = resp.data.industrial_city_id;

        })
        .catch(function () {
          alert("Could not load your master plan data")
        });
  },

  methods: {
    setMasterPlanDocument(event) {
      this.masterPlanForm.document = event.target.files[0]
    },
    updateMasterPlan() {
      const app = this;
      const form = new FormData();
      form.append('name', app.masterPlanForm.name);
      form.append('name_en', app.masterPlanForm.name_en);
      form.append('remarks', app.masterPlanForm.remarks);
      form.append('remarks_en', app.masterPlanForm.remarks_en);
      form.append('mp_doc', app.masterPlanForm.document);
      form.append('status', app.masterPlanForm.status);
      form.append('master_plan_id', app.id);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/home-page/industrial-city/master-plan/update', form)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toast.success('Update master plan created successfully.');
              app.$router.push('/home-page/industrial-city/edit/'+app.city_id);
            }
          }).catch((error) => {
        app.masterPlanFormErrors = error.response.data.errors;
      });
    }
  }
}
</script>
