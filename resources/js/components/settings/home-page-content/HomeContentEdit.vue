<template>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            <h5 class="card-title pt-2 pb-2">Home Page content Edit</h5>
        </div>
        <form @submit.prevent="saveForm()" id="homeContent">
            <div class="card-body">
                <div class="col-md-12">
                    <div class="row form-group ">
                        <label class="col-md-2  required-star">Type: </label>
                        <div class="col-md-10">
                            <label><input v-model="homeContent.type" name="type" type="radio"
                                           value="chairman" > Chairman &nbsp;</label>
                            <label><input v-model="homeContent.type" name="type" type="radio"
                                           value="necessary info" > Necessary info&nbsp; </label>
                            <label><input v-model="homeContent.type" name="type" type="radio"
                                          value="related" > related&nbsp; </label>
                            <label><input v-model="homeContent.type" name="type" type="radio"
                                           value="resource" > Resource&nbsp; </label>
                            <label><input v-model="homeContent.type" name="type" type="radio"
                                           value="others" > Others&nbsp; </label>
                            <span v-if="allerros.type" :class="['text-danger']">{{ allerros.type[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Title (English):</label>
                        <div :class="['col-md-8', allerros.title_en ? 'has-error' : '']">
                            <input type="text" v-model="homeContent.title_en" class="form-control">
                            <span v-if="allerros.title_en" :class="['text-danger']">{{ allerros.title_en[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Title (বাংলা ):</label>
                        <div :class="['col-md-8', allerros.title ? 'has-error' : '']">
                            <input type="text" v-model="homeContent.title" class="form-control">
                            <span v-if="allerros.title" :class="['text-danger']">{{ allerros.title[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Heading (English):</label>
                        <div :class="['col-md-8', allerros.heading_en ? 'has-error' : '']">
                            <input type="text" v-model="homeContent.heading_en" class="form-control">
                            <span v-if="allerros.heading_en" :class="['text-danger']">{{ allerros.heading_en[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Heading (বাংলা ):</label>
                        <div :class="['col-md-8', allerros.heading ? 'has-error' : '']">
                            <input type="text" v-model="homeContent.heading" class="form-control">
                            <span v-if="allerros.heading" :class="['text-danger']">{{ allerros.heading[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-2 required-star">Details ( English ):</label>
                        <div :class="['col-md-8', allerros.details_en ? 'has-error' : '']">
                            <editor
                                    v-model="homeContent.details_en"
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
                            <span v-if="allerros.details_en" :class="['text-danger']">{{ allerros.details_en[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-2  required-star">Details (বাংলা ):</label>
                        <div :class="['col-md-8', allerros.details ? 'has-error' : '']">
                            <editor
                                    v-model="homeContent.details"
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
                            <span v-if="allerros.details" :class="['text-danger']">{{ allerros.details[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2 required-star">Image:</label>
                        <div :class="['col-md-6', allerros.files ? 'has-error' : '']">
                            <input type="file" id="files" ref="files" class="form-control" v-on:change="onFileChange"/>
                            <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                            <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif/ | Max size 1 MB]</span>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-info" target="_blank" v-bind:href="'/'+ homeContent.image"><i
                                    class="fa fa-file-image-o"></i> View Photo</a>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2 required-star">Icon:</label>
                        <div :class="['col-md-6', allerros.files ? 'has-error' : '']">
                            <input type="file" id="icon" ref="files" class="form-control" v-on:change="onFileChangeicon"/>
                            <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                            <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif/ | Max size 1 MB]</span>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-info" target="_blank" v-bind:href="'/'+ homeContent.icon"><i
                                    class="fa fa-file-image-o"></i> View Photo</a>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Details Url:</label>
                        <div :class="['col-md-8', allerros.details_url ? 'has-error' : '']">
                            <input type="text" v-model="homeContent.details_url" class="form-control">
                            <span v-if="allerros.details_url" :class="['text-danger']">{{ allerros.details_url[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Order:</label>
                        <div :class="['col-md-8', allerros.order ? 'has-error' : '']">
                            <input type="number" v-model="homeContent.order" class="form-control" size="10x5" min="0">
                            <span v-if="allerros.order" :class="['text-danger']">{{ allerros.order[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group ">
                        <label class="col-md-2  required-star">Status: </label>
                        <div class="col-md-10">
                            <label><input v-model="homeContent.status" name="is_active" type="radio"
                                           value="1" > Active&nbsp;</label>
                            <label><input v-model="homeContent.status" name="is_active" type="radio"
                                            value="0" > Inactive</label>
                            <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <router-link to="/home-page/home-page-content" class="btn btn-default"><i
                            class="fa fa-chevron-circle-left"></i> Back
                        </router-link>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
                    </div>
                </div>
            </div>
         </form>
    </div>
</template>

<script>
    import Editor from "@tinymce/tinymce-vue";
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                homeContent: {
                    type: '',
                    title_en: '',
                    title: '',
                    heading_en: '',
                    heading: '',
                    details: '',
                    details_en: '',
                    details_url: '',
                    order: '',
                    status: '',

                },
            }

        },
        components: {
            'editor': Editor
        },

        mounted() {
            $(document).ready(function () {
                $("#homeContent").validate({
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
            axios.get('/settings/home-page/edit-home-page-content/' + id)
                .then(function (resp) {
                    app.homeContent = resp.data;
                })
                .catch(function () {
                    alert("Could not load your News")
                });

        },

        methods: {
            onFileChange(event) {
                this.homeContent.imagefile = event.target.files[0]
                console.log(this.slider.imagefile);
            },
            onFileChangeicon(event) {
                this.homeContent.iconfile = event.target.files[0]
                console.log(this.homeContent.iconfile);

            },
            saveForm() {
                var app = this;
                var newCompany = app.homeContent;
                var form = new FormData();
                form.append('image',this.homeContent.imagefile);
                form.append('icon',this.homeContent.iconfile);
                form.append('type',this.homeContent.type);
                form.append('title',this.homeContent.title);
                form.append('title_en',this.homeContent.title_en);
                form.append('heading_en',this.homeContent.heading_en);
                form.append('heading',this.homeContent.heading);
                form.append('details',this.homeContent.details);
                form.append('details_en',this.homeContent.details_en);
                form.append('details_url',this.homeContent.details_url);
                form.append('order',this.homeContent.order);
                form.append('status',this.homeContent.status);
                form.append('id',app.id);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/update-home-page-content' ,form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/home-page/home-page-content');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
