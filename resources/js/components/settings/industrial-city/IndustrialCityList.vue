<template>
  <div class="card card-magenta border border-magenta">
    <div class="card-header">
        <div class="float-left">
            <h5 class="box-title"><i class="fa fa-list"></i> List of Industrial City</h5>
        </div>
        <div class="float-right">
            <router-link :to="{name: 'IndustrialCityCreate'}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add
                New City
            </router-link>
        </div>
      <!-- /.box-tools -->
    </div>

    <div class="card-body">
        <div class="col-md-1 float-left">
            <select class="form-control col-md-12" v-model="limits"  @change="limit($event)">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class=" col-md-offset-8 col-md-3 float-right">
            <input class="form-control" type="text" placeholder="Search..."
                   v-model="search" v-on:keyup="keymonitor">
        </div>
        <br>
        <br>
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table vue-table table-striped table-bordered">
          <thead>
          <tr>
            <th>SL</th>
            <th>Name</th>
            <th>District</th>
            <th>Upazila</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="industrial in laravelData.data" :key="industrial.id">
            <td>{{ industrial.si }}</td>
            <td>{{ industrial.name }}</td>
            <td>{{ industrial.district_name }}</td>
            <td>{{ industrial.upazila_name }}</td>
            <td>
              <span v-if="industrial.type == 1">Office</span>
              <span v-else>Home</span>
            </td>
            <td>
              <span v-if="industrial.status == 1">
                <span class="text-success">Active</span>
              </span>
              <span v-else>
                <span class="text-danger">Inactive</span>
              </span>
            </td>
            <td>
              <router-link :to="{name: 'IndustrialCityEdit', params: {id: industrial.id}}"
                           class="btn btn-xs btn-primary">
                <i class="fa fa-edit"></i> Edit
              </router-link> &nbsp;
              <router-link :to="{name: 'IndustrialCityEdit', params: {id: industrial.id}}"
                           class="btn btn-xs btn-warning">
                <i class="fa fa-folder-open"></i> Master-plan
              </router-link>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
        <div class="col-md-5">
        </div>
        <div class="col-md-7 ">
            <pagination v-model="page" :records="laravelData.total ?? 0" :per-page="limits ?? 0" @paginate="getResults"/>
            <!--                    <pagination :limit="2" class="pull-right" :data="laravelData" @pagination-change-page="getResults"></pagination>-->
        </div>
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
      limits: 10,
      page: 1
    }
  },
  mounted() {
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
      var app = this;
      axios.get('/settings/home-page/industrial-city?page=' + page + is_search + max_limit)
          .then(response => {
              app.laravelData = response.data;
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
