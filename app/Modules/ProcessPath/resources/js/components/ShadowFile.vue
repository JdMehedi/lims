<template>
    <div>
        <button type="button" class="btn btn-warning float-right" id="request_shadow_file" @click="shadowFileRequest()"
            :disabled="isDisabled">
            <b>Request to create shadow file</b>
        </button>
        <div class="clearfix"></div>

        <div class="table-responsive">
            <table class="table  table-striped table-bordered table-hover no-margin">
                <thead>
                    <tr>
                        <th width="15%">Updated By</th>
                        <th width="15%">Time to generate</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody v-if="shadowFileList.length > 0">
                    <tr v-for="(file, key) in shadowFileList" v-bind:key="key">
                        <td>{{ getUserFullName() }}</td>
                        <td>{{ TimestampToReadableDate(file.updated_at) }}</td>
                        <td>
                            <a v-if="file.file_path != ''" download="" :href="file.file_path"
                                class="btn btn-primary show-in-view btn-xs  download">
                                <i class="fa fa-save"></i> Download
                            </a>

                            <div v-else>
                                <button class="btn btn-danger show-in-view btn-xs">
                                    Requested
                                </button>
                                <button class="btn btn-warning show-in-view btn-xs">
                                    In-progress
                                </button>
                            </div>

                        </td>
                    </tr>
                </tbody>

                <tbody v-else>
                    <tr class="text-center text-bold">
                        <td colspan="3">No data available in table</td>
                    </tr>
                </tbody>
            </table>
        </div><!-- /.table-responsive -->
    </div>
</template>

<script>
import moment from 'moment';

export default {
    props: {
        module_name: '',
        encoded_process_list_id: '',
        encoded_process_type_id: '',
        encoded_ref_id: ''
    },
    data() {
        return {
            isDisabled: false,
            shadowFileList: []
        }
    },
    created() {
        this.getShadowFileHistory();
    },
    methods: {
        shadowFileRequest() {
            var app = this;
            app.isDisabled = true;
            axios.get('/process-path/request-shadow-file/', {
                params: {
                    module_name: app.module_name,
                    ref_id: app.encoded_ref_id,
                    process_id: app.encoded_process_list_id,
                    process_type_id: app.encoded_process_type_id
                }
            }).then(response => {
                if (response.data.responseCode == 1) {
                    this.$toast.success('Shadow file request generated successfully');
                    this.getShadowFileHistory();
                }
                
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    app.isDisabled = false;
                });
        },
        getShadowFileHistory() {
            var app = this;
            axios.get('/vue/process/shadow-file-hist/' + app.encoded_process_type_id + '/' + app.encoded_ref_id).then(response => {
                app.shadowFileList = response.data;
                
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        TimestampToReadableDate(timestamp) {
            return moment(timestamp).fromNow();
        },
    }
}
</script>

<style>
</style>