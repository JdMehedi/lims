<template>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="pull-left">
        <h5><strong><i class="fa fa-list"></i> <strong>Rollback List</strong></strong></h5>
      </div>
      <div class="pull-right">
        <router-link :to="{name: 'AppRollbackSearch'}" class="btn btn-default"><i class="fa fa-plus"></i> New Application Rollback</router-link>
      </div>
      <div class="clearfix"></div>

    </div>

    <div class="panel-body"><br>
      <div class="col-md-1">
        <select class="form-control col-md-1" v-model="limits"  @change="limit($event)">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </div>
      <div class=" col-md-offset-8 col-md-3">
        <input class="form-control pull-right" type="text" placeholder="Search..."
               v-model="search" v-on:keyup="keymonitor">


      </div>
      <div class="col-md-12">
        <table class="table  dt-responsive ">
          <thead>
          <tr>
            <th>SL No.</th>
            <th>Tracking No.</th>
            <th>App Tracking No.</th>
            <th>Status</th>
            <th>Last Modified</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="data in laravelData.data" :key="data.id">
            <td>{{data.si}}</td>
            <td>{{data.tracking_no}}</td>
            <td>{{data.app_tracking_no}}</td>
            <td>
              <div v-if="data.status_id == 25">
                <span class='label label-success'>Approved</span>
              </div>
            </td>
            <td>
              {{data.user_name}}<br>
              {{data.updated_at}}
            </td>

            <td>
              <router-link :to="{name: 'AppRollbackView', params: {id: data.id}}" class="btn btn-xs btn-primary">
                <i class="fa fa-file"></i> Open
              </router-link>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-5"><br>
        Showing {{laravelData.from}} to {{laravelData.to}} of {{laravelData.total}} entries
      </div>
      <div class="col-md-7 ">
        <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>
      </div>
    </div>
  </div>
  <!--    </div>-->

</template>

<script>
export default {
  data() {
    return {
      laravelData: {},
      search: '',
      limits:10
    }
  },
  mounted() {
    console.log('Component mounted.')
  },
  created() {
    this.getResults();
  },

  methods: {
    getResults(page) {
      if (typeof page === 'undefined') {
        page = 1;
      }
      var max_limit = '&limit=' + this.limits;
      var is_search = '';
      if(this.search){
        is_search = '&search=' + this.search
      }
      this.$http.get('/settings/app-rollback/list?page=' + page + is_search + max_limit)
          .then(response => {
            this.laravelData = response.data;
          })
    },
    keymonitor: function (e) {
      this.getResults(1);
    },
    limit: function (e) {
      this.getResults(1);
    }
  }
}
</script>
