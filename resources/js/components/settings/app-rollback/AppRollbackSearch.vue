<template>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="pull-left">
        <h5>
          <strong
            ><i class="fa fa-list"></i>
            <strong>Application Search</strong></strong
          >
        </h5>
      </div>
      <div class="pull-right">
        <router-link :to="{ name: 'AppRollbackList' }" class="btn btn-warning"
        ><i class="fa fa-angle-double-left"></i> Go back</router-link
      >
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="panel-body">
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-9 col-md-offset-3">
            <label>Search Application: </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-5 col-md-offset-3">
            <input type="text" v-model="search_app" class="form-control" />
          </div>
          <div class="col-md-1">
            <!--            <router-link :to="{name: 'AppRollbackOpen', params: { search_app }}" class="btn btn-primary">Search</router-link>-->
            <button class="btn btn-primary" @click="searchApp()">Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import router from "../../../routes";

export default {
  data() {
    return {
      search_app: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {},

  methods: {
    searchApp() {
      let tracking_no_id = this.search_app;
      axios
        .get("/settings/app-rollback-search/" + tracking_no_id)
        .then(function (resp) {
          const tracking_no = resp.data;
          if (tracking_no != null) {
            router.push({ name: "AppRollbackOpen", params: { tracking_no } });
          } else {
            alert("Sorry! Application not found");
          }
        })
        .catch(function () {
          alert("Your data could not be loaded");
        });
    },
  },
};
</script>