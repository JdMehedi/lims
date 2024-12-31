<template>
    <div>
        <div class="card">
            <div class="card-header bg-primary">Act & Rules Form</div>
            <div class="card-body">
                <div class="col-md-9">
                <form @submit.prevent="saveForm()">
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Subject:</label>
                        <div :class="['col-md-8', allerros.subject ? 'has-error' : '']">
                            <input type="text" v-model="actrules.subject" class="form-control">
                            <span v-if="allerros.subject" :class="['text-danger']">{{ allerros.subject[0] }}</span>
                        </div>

                    </div>

                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Description:</label>
                        <div :class="['col-md-8', allerros.description ? 'has-error' : '']">
                            <editor
                                v-model="actrules.description"
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
                                    <!--:init="{

                                       }"-->

<!--&lt;!&ndash;-->                            <!--<textarea v-model="actrules.description" class=" form-control bnEng"></textarea>&ndash;&gt;
&lt;!&ndash;                            <textarea v-model="actrules.description" id="helpArticleContentDiv" v-tinymce class="form-control bnEng wysiwyg"></textarea>&ndash;&gt;-->
                            <span v-if="allerros.description" :class="['text-danger']">{{ allerros.description[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Order:</label>
                        <div :class="['col-md-8', allerros.showing_order ? 'has-error' : '']">
                            <input type="number" v-model="actrules.showing_order" class="form-control" size="10x5" min="0">
                        <span v-if="allerros.showing_order" :class="['text-danger']">{{ allerros.showing_order[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2 ">Pdf File:</label>
                        <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                            <input type="file" id="files" ref="files"  v-on:change="onFileChange"/>
                        <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="status" class="col-md-2  required-star">Status: </label>
                        <div class="col-md-10">
                            <label><input v-model="actrules.status" name="status" type="radio"
                                          :checked="actrules.status"  value="1" id="status"> Active&nbsp;</label>
                            <label><input v-model="actrules.status" name="status" type="radio"
                                          :checked="actrules.status"  value="0" id="status"> Inactive</label>
                            <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <router-link to="/act-rules" class="btn btn-default"> Back</router-link>
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
<!--<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">-->
<!--<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>-->

<script>
    import Editor from '@tinymce/tinymce-vue'
    import {API} from '../../../custom.js';
    const customClass = new API();
    export default {
        components: {
            'editor': Editor
        },
        data: function () {
            return {
                allerros: [],
                success : false,
                actrules: {
                    subject: '',
                    description: '',
                    showing_order: '',
                    status:'',
                    pdf_file:'',
                },
                // pdf_file : null,
            }
        },
        mounted() {
            // customClass.onlyNumber();
            // customClass.isEmail();
        },

        methods: {
            onFileChange(event) {
                this.actrules.pdf_file = event.target.files[0]
                // console.log( this.actrules.pdf_file);
            },
            saveForm() {
                var app = this;
                var form = new FormData();
                // form.append('name',this.attachment.name);
                form.append('file_data',this.actrules.pdf_file);
                form.append('subject',this.actrules.subject);
                form.append('description',this.actrules.description);
                form.append('showing_order',this.actrules.showing_order);
                form.append('status',this.actrules.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/store-act-rules', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your notice crate successfully.');
                            app.$router.push({path: '/act-rules'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            },

        }

    }
</script>

