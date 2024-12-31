<template>
    <div v-html="documentContent"></div>
</template>

<script>

export default {
    props: {
        encoded_process_type_id: '',
        encoded_ref_id: '',
        viewMode: '',
        openMode: '',
        doc_type_key: ''
    },
    data() {
        return {
            documentContent: ''
        }
    },
    created() {
        this.getAppDocuments();
    },
    methods: {
        getAppDocuments() {
            var app = this;
            app.isDisabled = true;
            axios.get('/documents/get-app-docs', {
                params: {
                    encoded_process_type_id: app.encoded_process_type_id,
                    encoded_app_id: app.encoded_ref_id,
                    view_mode: app.viewMode,
                    openMode: app.openMode,
                    doc_type_key: app.doc_type_key
                }
            }).then(response => {
                app.documentContent = response.data.html;

            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {

                });
        }
    }
}
</script>

<style>

</style>