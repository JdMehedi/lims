<template>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="pull-left">
        <h5>
          <strong
            ><i class="fa fa-list"></i>
            <strong>Application Rollback Details</strong></strong
          >
        </h5>
      </div>
      <div class="pull-right">
        <a
          :href="appInfo.application_url"
          class="btn btn-default"
          target="_blank"
          >Open Application</a
        >
      </div>

      <div class="clearfix"></div>
    </div>

    <div class="panel-body">
      <br />
      <ol class="breadcrumb">
        <li>
          <strong>Tracking no. : </strong> {{ rollbackAppInfo.tracking_no }}
        </li>
        <li>
          <strong>App. Tracking no : </strong>
          {{ rollbackAppInfo.app_tracking_no }}
        </li>
        <li>
          <strong>Current Desk:</strong>
          <span v-if="appInfo.desk_id != 0"> {{ appInfo.desk_name }}</span>
          <span v-else> Applicant</span>
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
                    <label class="col-md-4"
                      ><strong>Submission Date:</strong></label
                    >
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
        <div class="col-md-12">
          <div class="panel panel-info">
            <div class="panel-heading">
              <div class="pull-left">
                <h5>
                  <strong
                    ><strong>
                      View Application Rollback Information</strong
                    ></strong
                  >
                </h5>
              </div>

              <div class="clearfix"></div>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Caption</th>
                      <th scope="col">Old Value</th>
                      <th scope="col">New Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ data[0].caption }}</td>
                      <td>{{ data[0].old_value }}</td>
                      <td>{{ data[0].new_value }}</td>
                    </tr>
                    <tr>
                      <td>{{ data[1].caption }}</td>
                      <td>{{ data[1].old_value }}</td>
                      <td>{{ data[1].new_value }}</td>
                    </tr>
                    <tr>
                      <td>{{ data[2].caption }}</td>
                      <td>{{ data[2].old_value }}</td>
                      <td>{{ data[2].new_value }}</td>
                    </tr>
                  </tbody>
                </table>
                <label>Rollback Remarks : </label> {{ rollbackAppInfo.remarks }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="panel-footer text-right">
      <router-link :to="{ name: 'AppRollbackList' }" class="btn btn-danger"
        ><i class="fa fa-times"></i> Close</router-link
      >
    </div>
  </div>
</template>

<script>
import router from "../../../routes";

export default {
  data() {
    return {
      appInfo: [],
      data: [],
      rollbackAppInfo: [],
      appId: "",
      processTypeId: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    let app = this;
    let id = app.$route.params.id;
    axios
      .get("/settings/app-rollback-details/" + id)
      .then(function (resp) {
        app.appInfo = resp.data.appInfo;
        app.data = resp.data.data;
        app.rollbackAppInfo = resp.data.rollbackAppInfo;
      })
      .catch(function () {
        alert("Your data could not be loaded");
      });
  },

  methods: {},
};
</script>