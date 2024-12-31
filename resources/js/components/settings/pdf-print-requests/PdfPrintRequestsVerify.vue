
<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading"> PDF print requests</div>
          <div class="panel-body">
            <div class="table-responsive">
              <form>

                <div class="col-sm-12">
                  <pre>{{ JSON.stringify(result, null, '\t') }}</pre>

                </div>
                <div class="col-sm-12">
                  <div class="col-sm-offset-5 col-sm-7">
                    <button type="button" class="btn btn-warning btn-circle btn-lg" style="padding:0px">JSON
                    </button>
                  </div>
                  {{ JSON.stringify(result) }}

                </div>
              </form>
              <div class="col-sm-12">
                <br>
                <router-link to="/pdf-print-requests" class="btn btn-default"> Back</router-link>
              </div>

            </div><!-- /.table-responsive -->
          </div><!-- /.panel-body -->
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
              result:'',
            }
        },
      created() {
        this.getResults();
      },

        methods: {
          getResults: function (){
            let id = this.$route.params.id;
            console.log(id)
            this.$http.get('/settings/pdf-print-request-verify/' + id)
                .then(response => {
                  this.result = response.data;
                })
          }
        }
    }
</script>
