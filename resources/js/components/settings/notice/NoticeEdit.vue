<template>
  <div>
    <div class="card card-magenta border border-magenta">
      <div class="card-header">
        <h5 class="card-title pt-2 pb-2"><strong>Edit Notice</strong></h5>
      </div>
      <form @submit.prevent="saveForm()" enctype="multipart/form-data">
        <div class="card-body">
          <div class="col-md-11">
            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Heading:</label>
              <div :class="['col-md-8', allerrors.heading ? 'has-error' : '']">
                <input type="text" v-model="notice.heading" class="form-control">
                <span v-if="allerrors.heading" :class="['text-danger']">{{ allerrors.heading[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Details:</label>
              <div :class="['col-md-10', allerrors.details ? 'has-error' : '']">
                <editor
                        v-model="notice.details"
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
                <span v-if="allerrors.details" :class="['text-danger']">{{ allerrors.details[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Importance:</label>
              <div :class="['col-md-8', allerrors.importance ? 'has-error' : '']">
                <select v-model="notice.importance" class="form-control input-sm ">
                  <option value=""> Select one</option>
                  <option value="danger"> Danger</option>
                  <option value="info"> Info</option>
                  <option value="top">Top</option>
                  <option value="warning">Warning</option>
                </select>

                <span v-if="allerrors.importance" :class="['text-danger']">{{ allerrors.importance[0] }}</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="control-label col-md-2  required-star">Order:</label>
              <div :class="['col-md-8', allerrors.ordering_prefix ? 'has-error' : '']">
                <input type="number" v-model="notice.ordering_prefix" class="form-control" size="10x5" min="0">
                <span v-if="allerrors.ordering_prefix" :class="['text-danger']">{{ allerrors.ordering_prefix[0] }}</span>
              </div>
            </div>
            <div class="row form-group ">
              <label class="col-md-2  required-star">Status: </label>
              <div class="col-md-10">
                <div>
                  <label class="radio-inline">
                    <input v-model="notice.status" name="status" type="radio"
                            value="draft"> Draft &nbsp;
                  </label>
                  <label class="radio-inline">
                    <input v-model="notice.status" name="status" type="radio"
                           value="private"> Private &nbsp;
                  </label>
                  <label class="radio-inline">
                    <input v-model="notice.status" name="status" type="radio"
                            value="unpublished"> Unpublished &nbsp;
                  </label>
                  <label class="radio-inline">
                    <input v-model="notice.status" name="status" type="radio"
                            value="public"> Public &nbsp;
                  </label>
                </div>
                <span v-if="allerrors.status" :class="['text-danger']">{{ allerrors.status[0] }}</span>
              </div>
            </div>
           <div class="col-md-12">
               <router-link to="/notice-list" class="btn btn-default">Back</router-link>
               <div class="float-right">
                   <button type="submit" class="btn btn-primary">
                       <i class="fa fa-chevron-circle-right"></i> Save
                   </button>
               </div>
           </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>

import Editor from "@tinymce/tinymce-vue";

export default {
  data: function () {
    return {
      notice_id: null,
      allerrors: [],
      notice: {
        heading: '',
        heading_en: '',
        details: '',
        details_en: '',
        photo: null,
        notice_document: null,
        ordering_prefix: '',
        importance: '',
        status: '',
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
    app.notice_id = id;
    axios.get('/settings/edit-notice/' + id)
        .then(function (resp) {
          app.notice = resp.data;
        })
        .catch(function () {
          alert("Could not load your Notice")
        });
  },

  methods: {
    onFileChange(event) {
      let vm = this;
      vm.notice.photo = event.target.files[0];
    },
    onChangeNoticeDoc(event) {
      let vm = this;
      vm.notice.notice_document = event.target.files[0];
    },
    saveForm() {
      let app = this;
      const notice_data = new FormData();
      notice_data.append('heading', app.notice.heading);
      notice_data.append('heading_en', app.notice.heading_en);
      notice_data.append('photo', app.notice.photo);
      notice_data.append('notice_document', app.notice.notice_document);
      notice_data.append('details', app.notice.details);
      notice_data.append('details_en', app.notice.details_en);
      notice_data.append('importance', app.notice.importance);
      notice_data.append('ordering_prefix', app.notice.ordering_prefix);
      notice_data.append('status', app.notice.status);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/update-notice/' + app.notice_id, notice_data)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toast.success('Your data update successfully.');
              app.$router.push('/notice-list');
            }
          }).catch((error) => {
        app.allerrors = error.response.data.errors;
      });
    }
  }
}
</script>
