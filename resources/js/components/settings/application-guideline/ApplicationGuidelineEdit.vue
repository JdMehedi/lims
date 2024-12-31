<template>
  <div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h5><b>Application guideline Edit</b></h5>
      </div>
      <form @submit.prevent="saveForm()">

        <div class="panel-body">
          <div class="col-md-9">

            <div class="form-group col-md-12">
              <label class="control-label col-md-2">Process Type :</label>
              <div class="col-md-8">
                <b>{{ appGuideline.process_type }}</b>
              </div>
            </div>

            <div class="form-group col-md-12">
              <label class="control-label col-md-2">Guideline details:</label>
              <div :class="['col-md-8', allerros.guideline_details ? 'has-error' : '']">
                <editor
                    v-model="appGuideline.guideline_details"
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
                <span v-if="allerros.guideline_details" :class="['text-danger']">{{ allerros.guideline_details[0] }}</span>
              </div>
            </div>

            <div class="form-group col-md-12">
              <label class="control-label col-md-2 ">Guideline File:</label>
              <div :class="['col-md-8', allerros.guideline_file ? 'has-error' : '']">
                <input type="file" class="form-control" id="guideline_file" ref="guideline_file" v-on:change="onFileChange" accept="application/pdf"/>
                <span class="help-block">[File type: PDF | Max size: 3MB]</span>
                <a v-bind:href="'/'+ appGuideline.guideline_file" class="btn btn-xs btn-info" target="_blank">Open File</a>
                <span v-if="allerros.guideline_file" :class="['text-danger']">{{ allerros.guideline_file[0] }}</span>
              </div>
            </div>

            <div class="form-group col-md-12 ">
              <label for="status" class="col-md-2  required-star">Guideline Status: </label>
              <div class="col-md-10">
                <label class="checkbox-inline"><input v-model="appGuideline.guideline_status" name="guideline_status" type="radio"
                                                      :checked="appGuideline.guideline_status" value="1" id="status">
                  Active</label>
                <label class="checkbox-inline"><input v-model="appGuideline.guideline_status" name="guideline_status" type="radio"
                                                      :checked="appGuideline.guideline_status" value="0" id="status">
                  Inactive</label>
                <span v-if="allerros.guideline_status" :class="['text-danger']">{{ allerros.guideline_status[0] }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="panel-footer">
          <div class="pull-left">
            <router-link to="/application-guideline" class="btn btn-default"><< Back</router-link>
          </div>
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-chevron-circle-right"></i> Save
            </button>
          </div>
          <div class="clearfix"></div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
// import {API} from '../../../custom.js';
// const customClass = new API();
import Editor from "@tinymce/tinymce-vue";

export default {
  data: function () {
    return {
      $id: null,
      allerros: [],
      appGuideline: {
        process_type: '',
        guideline_details: '',
        guideline_file: '',
        guideline_status: '',
      },
      selected: null
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
    app.$id = id;
    axios.get('/settings/application-guideline/edit/' + id)
        .then(function (resp) {
          app.appGuideline.process_type = resp.data.name;
          app.appGuideline.guideline_details = resp.data.guideline_details;
          app.appGuideline.guideline_file = resp.data.guideline_file;
          app.appGuideline.guideline_status = resp.data.guideline_status;
        })
        .catch(function () {
          alert("Could not load your Application guideline")
        });
  },

  methods: {
    onFileChange(event) {
      this.appGuideline.guideline_file = event.target.files[0]
    },
    saveForm() {
      const app = this;
      const newCompany = app.appGuideline;
      const form = new FormData();
      form.append('guideline_file', this.appGuideline.guideline_file);
      form.append('guideline_details', this.appGuideline.guideline_details);
      form.append('guideline_status', this.appGuideline.guideline_status);
      form.append('id', app.$id);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/application-guideline-update', form)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toaster.success('Your data update successfully.');
              app.$router.replace('/application-guideline');
            }
          }).catch((error) => {
        this.allerros = error.response.data.errors;
      });
    }
  }
}
</script>
