<template>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <h3 class="card-title pt-2 pb-2"><i class="fa fa-list"></i> Application list
                <span v-if="this.process_type_info.id > 0">
                    ({{ this.process_type_info.name }})
                </span>
            </h3>
            <div class="card-tools">
                <!-- @if (ACL::getAccsessRight('user', 'A')) -->
                <!-- <a class="btn btn-default" href=""><i class="fa fa-plus"></i> New application</a> -->
                <!-- @endif -->
            </div>
            <!-- /.card-tools -->
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="my-desk-tab" data-toggle="tab" href="#my-desk" role="tab"
                        aria-controls="my-desk" aria-selected="true" @click="setActiveList('my-desk')">List</a>
                </li>
                <li v-if="Auth().user_type == '4x404'" class="nav-item">
                    <a class="nav-link" id="delegation-tab" data-toggle="tab" href="#delegation" role="tab"
                        aria-controls="delegation" aria-selected="false"
                        @click="setActiveList('my-delg-desk')">Delegation Desk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="favourite-tab" data-toggle="tab" href="#favourite" role="tab"
                        aria-controls="favourite" aria-selected="false"
                        @click="setActiveList('favorite_list')">Favourite</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="search-tab" data-toggle="tab" href="#searchTab" role="tab"
                        aria-controls="searchTab" aria-selected="false"
                        @click="setActiveList('process_search')">Search</a>
                </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="my-desk" role="tabpanel" aria-labelledby="my-desk-tab">

                    <template v-if="this.get_process_type_info">
                        <ProcessList v-if="this.desk == 'my-desk'" :listInfo="{
                            processType: this.process_type_info.id,
                            status: this.status,
                            desk: this.desk
                        }"></ProcessList>
                    </template>

                </div>

                <div class="tab-pane fade" id="delegation" role="tabpanel" aria-labelledby="delegation-tab">

                    <template v-if="this.get_process_type_info">
                        <ProcessList v-if="this.desk == 'my-delg-desk'" :listInfo="{
                            processType: this.process_type_info.id,
                            status: this.status,
                            desk: this.desk
                        }"></ProcessList>
                    </template>

                </div>

                <div class="tab-pane fade" id="favourite" role="tabpanel" aria-labelledby="favourite-tab">

                    <template v-if="this.get_process_type_info">
                        <ProcessList v-if="this.desk == 'favorite_list'" :listInfo="{
                            processType: this.process_type_info.id,
                            status: this.status,
                            desk: this.desk
                        }"></ProcessList>
                    </template>

                </div>

                <div class="tab-pane fade" id="searchTab" role="tabpanel" aria-labelledby="search-tab">

                    <template v-if="this.get_process_type_info">
                        <ProcessList v-if="this.desk == 'process_search'" :listInfo="{
                            processType: 0,
                            status: this.status,
                            desk: this.desk
                        }"></ProcessList>
                    </template>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProcessList from "./ProcessList.vue";
export default {
    components: { ProcessList },
    data() {
        return {
            process_type_info: {
                id: 0,
            },
            status: -1000,
            desk: 'my-desk',
            get_process_type_info: false
        }
    },
    created() {
        if (this.$route.params.process_type_id) {
            this.getProcessTypeInfo();
        } else {
            this.get_process_type_info = true;
        }
    },
    mounted() { },
    methods: {
        getProcessTypeInfo() {
            var app = this;
            axios.get('/vue/process-type/' + this.$route.params.process_type_id)
                .then(response => {
                    app.process_type_info = response.data;
                }).catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    app.get_process_type_info = true;
                });
        },
        setActiveList(list_type) {
            this.desk = list_type;
        }
    }
}
</script>

<style>
</style>