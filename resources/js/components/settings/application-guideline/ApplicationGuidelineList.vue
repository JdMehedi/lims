<template>
  <div class="card card-primary border border-primary">
    <div class="card-header">
      <div class="float-left">
        <h5><strong><i class="fa fa-list"></i> <strong>Application guideline list</strong></strong></h5>
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="card"><br>
      <div class="col-md-1">
        <select class="form-control col-md-1" v-model="limits" @change="limit($event)">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </div>
      <div class=" col-md-offset-8 col-md-3">
        <input class="form-control float-right" type="text" placeholder="Search..."
               v-model="search" v-on:keyup="keymonitor">


      </div>
      <div class="col-md-12">
        <table class="table  dt-responsive">
          <thead>
          <tr>
            <th>Sl</th>
            <th>Application name</th>
            <th>Application status</th>
            <th>Guideline status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="service in laravelData.data" :key="service.id">
            <td>{{ service.si }}</td>
            <td>{{ service.name }}</td>
            <td>
              <span v-if="service.status == 1" class="text-success">Active</span>
              <span v-else class="text-danger">In-active</span>
            </td>
            <td>
              <span v-if="service.guideline_status == 1" class="text-success">Active</span>
              <span v-else class="text-danger">In-active</span>
            </td>
            <td>
              <router-link :to="{name: 'ApplicationGuidelineEdit', params: {id: service.id}}"
                           class="btn btn-xs btn-primary">
                <i class="fa fa-edit"></i> Edit
              </router-link>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-5"><br>
        Showing {{ laravelData.from }} to {{ laravelData.to }} of {{ laravelData.total }} entries
      </div>
      <div class="col-md-7 ">
        <pagination :limit="2" class="float-right" :data="laravelData" @pagination-change-page="getResults"></pagination>
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
      limits: 10
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
      if (this.search) {
        is_search = '&search=' + this.search
      }
      this.$http.get('/settings/application-guideline?page=' + page + is_search + max_limit)
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
