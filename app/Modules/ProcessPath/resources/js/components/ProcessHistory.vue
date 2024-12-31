<template>
    <div>
        <div class="table-responsive">
            <table class="table  table-striped table-bordered table-hover no-margin">
                <thead>
                    <tr>
                        <th width="10%" class="text-center">On Desk</th>
                        <th width="15%">Updated By</th>
                        <th width="15%">Status</th>
                        <th width="15%">Process Time</th>
                        <th width="25%">Remarks</th>
                        <th width="3%">Attachment</th>
                    </tr>
                </thead>
                <tbody v-if="processHistory.length > 0">
                    <tr v-for="(history, key) in processHistory" v-bind:key="key">
                        <td class="text-center">{{ history.deskname }}</td>
                        <td>
                            {{ history.user_full_name }}

                            <span v-if="typeof processHistory[parseInt(key) + 1] != 'undefined'">
                                <span v-if="processHistory[parseInt(key) + 1].deskname != 'Applicant'">
                                    [Desk: {{ processHistory[parseInt(key) + 1].deskname }}]
                                </span>

                                <span v-else>
                                    [{{ processHistory[parseInt(key) + 1].deskname }}]
                                </span>
                            </span>

                            <span v-else>
                                [Desk: {{ history.deskname }}]
                            </span>
                        </td>
                        <td>{{ history.status_name }}</td>
                        <td>{{ TimestampToReadableDate(history.updated_at) }}</td>
                        <td>{{ history.process_desc }}</td>
                        <td>
                            <div v-if="history.files">
                                <a v-for="(file, index) in history.files.split(',')" target="_blank" :href="file"
                                    class="btn btn-primary btn-xs  download" :key="index">
                                    <i class="fa fa-file-pdf-o"></i> Open File
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>

                <tbody v-else>
                    <tr class="text-center text-bold">
                        <td colspan="6">No data available in table</td>
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
        encoded_process_list_id: ''
    },
    data() {
        return {
            processHistory: []
        }
    },
    created() {
        this.getProcessHistory();
    },
    methods: {
        getProcessHistory() {
            var app = this;
            axios.get('/vue/process/history/' + app.encoded_process_list_id).then(response => {
                app.processHistory = response.data;
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