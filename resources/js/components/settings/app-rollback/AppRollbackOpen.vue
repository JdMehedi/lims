<template>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="pull-left">
        <h5><strong><i class="fa fa-list"></i> <strong>Application Rollback</strong></strong></h5>
      </div>
      <div class="pull-right">
        <a :href="appInfo.application_url" class="btn btn-default" target="_blank">Open Application</a>
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="panel-body"><br>

        <ol class="breadcrumb">
          <li>
            <strong>Tracking no. : </strong> {{ appInfo.tracking_no }}
          </li>
          <li>
            <strong>Current Status : </strong> {{ appInfo.status_name }}
          </li>
          <li>
            <strong>Current Desk :</strong>
            <span v-if="appInfo.desk_id != 0"> {{ appInfo.desk_name }}</span>
            <span v-else> Applicant</span>
          </li>
          <li>
            <strong>Current Desk User :</strong>
            <span v-if="appInfo.user_id != 0"> {{ appInfo.desk_user_name }}</span>
            <span v-else> N/A</span>
          </li>
        </ol>

      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-info">
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group col-md-6">
                    <label class="col-md-4"><strong>Company:</strong></label>
                    <div class="col-md-8">
                      {{ appInfo.org_nm }}
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label class="col-md-4"><strong>Submission Date:</strong></label>
                    <div class="col-md-8">
                      {{ appInfo.submitted_at }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label col-md-12">Apply Status:</label>
            <div :class="['col-md-12', allerros.status_id ? 'has-error' : '']">
              <select v-model="rollbackData.status_id"  class="form-control input-sm" id="apply_status">
                <option value="">Select One</option>
                <option v-for="option in apply_status" :key="option.id" :value="option.id +'-'+option.status_name">{{option.status_name}}</option>
                <span v-if="allerros.status_id" :class="['text-danger']">{{ allerros.status_id[0] }}</span>
              </select>
            </div>
          </div>
        </div>

        <div id="sendToDeskOfficer">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label col-md-12">Send to Desk:</label>
              <div :class="['col-md-12', allerros.desk_id ? 'has-error' : '']">
                <select v-model="rollbackData.desk_id" class="form-control input-sm" id="send_desk" @change="onDeskChange($event)">
                  <option value="">Select One</option>
                  <option value="0">Applicant</option>
                  <option v-for="option in send_desk" :key="option.id" :value="option.id">{{option.desk_name}}</option>
                  <span v-if="allerros.desk_id" :class="['text-danger']">{{ allerros.desk_id[0] }}</span>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-4" id="user_info">
            <div class="form-group">
              <label class="control-label col-md-12">Send to User:</label>
              <div :class="['col-md-12', allerros.user_id ? 'has-error' : '']">
                <select v-model="rollbackData.user_id" class="form-control input-sm">
                  <option value="">Select One</option>
                  <option v-for="option in user_list" :key="option.user_id" :value="option.user_id">{{option.user_first_name}}</option>
                  <span v-if="allerros.user_id" :class="['text-danger']">{{ allerros.user_id[0] }}</span>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group maxTextCountDown">
            <label class=" col-md-12">Remarks <span class="text-danger" style="font-size: 9px; font-weight: bold">(Maximum length 5000)</span></label>
            <div class=" col-md-12">
              <textarea v-model="rollbackData.remarks" class="form-control" id="remarks" placeholder="Enter Remarks" maxlength="5000" rows="3"/>
              <span v-if="allerros.remarks" :class="['text-danger']">{{ allerros.remarks[0] }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>

    </div>
    <div class="panel-footer">
      <div class="pull-left">
<!--        Last updated : 3 months ago by Abdus Samad-->
      </div>
      <div class="pull-right">
        <router-link :to="{name: 'AppRollbackList'}" class="btn btn-default"><i class="fa fa-times"></i>Close</router-link>
        <button type="button" class="btn btn-primary" @click="updateStatus()">
          <i class="fa fa-chevron-circle-right"></i> Update
        </button>
        <!--        @endif-->
      </div>
      <div class="clearfix"></div>
    </div>
  </div>

</template>

<script>

import router from "../../../routes";

export default {
  props: ['search_app'],
  data() {
    return {
      allerros: [],
      appInfo: [],
      apply_status: [],
      send_desk: [],
      user_list: [],
      send_user: '',
      rollbackData: {
        status_id: '',
        desk_id: '',
        user_id: '',
        remarks: '',
      }
    }
  },
  mounted() {

  },
  created() {
    let app = this;
    let tracking_no = app.$route.params.tracking_no;
    axios.get('/settings/app-rollback-open/' + tracking_no)
        .then(function (resp) {
          app.appInfo = resp.data.appInfo;
          app.apply_status = resp.data.status;
          app.send_desk = resp.data.desk;
        })
        .catch (function () {
          alert('Your data could not be loaded');
        });
  },

  methods: {
    onDeskChange(event) {
      const app = this;
      // console.log(event.target.value)
      let desk_id = event.target.value;
      if(desk_id == 0){
        document.getElementById("user_info").style.display = "none";
      }else{
        document.getElementById("user_info").style.display = "block";
      }
      if (desk_id != '') {
        axios.get('/settings/app-rollback/get-user-by-desk/'+ desk_id)
            .then(function (resp) {
              app.user_list = resp.data.data;
            })
            .catch (function () {
              alert('Your data could not be loaded');
            });
      }
    },
    updateStatus(){
      const app = this;
      const form = new FormData();
      form.append('status_id',app.rollbackData.status_id);
      form.append('desk_id',app.rollbackData.desk_id);
      form.append('user_id',app.rollbackData.user_id);
      form.append('remarks',app.rollbackData.remarks);
      form.append('process_list_id',app.appInfo.process_list_id);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/app-rollback/update',form)
          .then(function (resp) {
            if (resp.data.responseCode === 1) {
              app.$toaster.success('Your data update successfully.');
              router.push({ name: 'AppRollbackList'})
            }else if(resp.data.responseCode === 2){
              app.$toaster.error('Your selected data did not match with the process map');
            }
          }).catch((error) => {
        this.allerros = error.response.data.errors;
      });
    }
  },
}
</script>
