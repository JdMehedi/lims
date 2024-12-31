<template>
    <div>
        <div class="card">
            <div class="card-header bg-primary">Terms & Condition Form</div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()" id="condition">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Subject:</label>
                            <div :class="['col-md-8', allerros.subject ? 'has-error' : '']">
                                <input type="text" v-model="termscondition.subject" class="form-control">
                                <span v-if="allerros.subject" :class="['text-danger']">{{ allerros.subject[0] }}</span>
                            </div>

                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Description:</label>
                            <div :class="['col-md-8', allerros.description ? 'has-error' : '']">
                                <editor
                                        v-model="termscondition.description"
                                        api-key="5tyznzq0zx85ayto1vep7l7jy3d4hsyf8mxev8jwuq5zqqwk"
                                        :init="{
                                         height: 300,
                                         menubar: true,
                                         plugins: [
                                           'advlist autolink lists link image charmap print preview anchor',
                                           'searchreplace visualblocks code fullscreen',
                                           'insertdatetime media table paste code help wordcount'
                                         ],
                                         toolbar:
                                           'undo redo | formatselect | bold italic backcolor | \
                                           alignleft aligncenter alignright alignjustify | \
                                           bullist numlist outdent indent | removeformat | help'
                                       }"
                                />
                                <!--                            <textarea v-model="termscondition.description" class=" form-control bnEng"></textarea>-->
                                <!--                            <textarea v-model="actrules.description" id="helpArticleContentDiv" v-tinymce class="form-control bnEng wysiwyg"></textarea>-->
                                <span v-if="allerros.description" :class="['text-danger']">{{ allerros.description[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Order:</label>
                            <div :class="['col-md-8', allerros.showing_order ? 'has-error' : '']">
                                <input type="number" v-model="termscondition.showing_order" class="form-control" size="10x5" min="0">
                                <span v-if="allerros.showing_order" :class="['text-danger']">{{ allerros.showing_order[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2 ">Pdf File:</label>
                            <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                                <input type="file" id="files" ref="files"  v-on:change="onFileChange"/>
                                <a v-bind:href="'/'+ termscondition.pdf_link">{{termscondition.pdf_link}}</a>
                                <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group ">
                            <label for="status" class="col-md-2  required-star">Status: </label>
                            <div class="col-md-10">
                                <label><input v-model="termscondition.status" name="status" type="radio"
                                                value="1" id="status"> Active&nbsp;</label>
                                <label><input v-model="termscondition.status" name="status" type="radio"
                                                value="0" id="status"> Inactive</label>
                                <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <router-link to="/terms-condition" class="btn btn-default">Back</router-link>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import {API} from '../../../custom.js';
    // const customClass = new API();
    import Editor from "@tinymce/tinymce-vue";

    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                termscondition: {
                    subject: '',
                    description: '',
                    pdf_file:'',
                    showing_order: '',
                    status:'',
                },
            }
        },
        components: {
            'editor': Editor
        },
        mounted() {
            $(document).ready(function () {
                $("#condition").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            console.log(id);
            app.id = id;
            axios.get('/settings/edit-terms-condition/' + id)
                .then(function (resp) {
                    app.termscondition = resp.data;
                })
                .catch(function () {
                    alert("Could not load your Terms and Condition")
                });
        },

        methods: {
            onFileChange(event) {
                this.termscondition.pdf_file = event.target.files[0]
            },
            saveForm() {
                var app = this;
                var newCompany = app.termscondition;
                var form = new FormData();
                form.append('file_data',this.termscondition.pdf_file);
                form.append('subject',this.termscondition.subject);
                form.append('description',this.termscondition.description);
                form.append('showing_order',this.termscondition.showing_order);
                form.append('status',this.termscondition.status);
                form.append('id',app.id);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/update-terms-condition' ,form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/terms-condition');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
