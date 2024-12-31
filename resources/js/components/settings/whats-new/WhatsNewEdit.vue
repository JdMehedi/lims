<template>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">Whats New form</div>
            <div class="panel-body">
                <div class="col-md-10">
                    <form @submit.prevent="saveForm()" id="whatsNew">
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-2  required-star">Title:</label>
                            <div :class="['col-md-8', allerros.title ? 'has-error' : '']">
                                <input type="text" v-model="whatsnew.title" class="form-control">
                                <span v-if="allerros.title" :class="['text-danger']">{{ allerros.title[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-2  required-star">description:</label>
                            <div :class="['col-md-8', allerros.description ? 'has-error' : '']">
                                <editor
                                        v-model="whatsnew.description"
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
                                <span v-if="allerros.description" :class="['text-danger']">{{ allerros.description[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="control-label col-md-2 required-star">Image:</label>
                            <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                                <input type="file" id="files" ref="files"  v-on:change="onFileChange"/>
                                <a v-bind:href="'/'+ whatsnew.image">{{whatsnew.image}}</a>
                                <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                                <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.jpg/ .jpeg | File size within 200 KB]</span><br/>
                            </div>
                        </div>
                        <div class="form-group col-md-12 ">
                            <label for="is_active" class="col-md-2  required-star">Status: </label>
                            <div class="col-md-10">
                                <label><input v-model="whatsnew.is_active" name="is_active" type="radio"
                                              :checked="whatsnew.is_active"  value="1" id="is_active"> Active</label>
                                <label><input v-model="whatsnew.is_active" name="is_active" type="radio"
                                              :checked="whatsnew.is_active"  value="0" id="is_active"> Inactive</label>
                                <span v-if="allerros.is_active" :class="['text-danger']">{{ allerros.is_active[0] }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <router-link to="/home-page/whats-new" class="btn btn-default"><< Back</router-link>
                            <button type="submit" class="btn btn-primary pull-right">
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
    import Editor from "@tinymce/tinymce-vue";
    // const customClass = new API();
    export default {
        data: function () {
            return {
                $id: null,
                allerros: [],
                whatsnew: {
                    title: '',
                    description: '',
                    imagefile: '',
                    is_active: '',

                },
            }

        },
        components: {
            'editor': Editor
        },

        mounted() {
            $(document).ready(function () {
                $("#whatsNew").validate({
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
            app.$id = id;
            axios.get('/settings/home-page/edit-whats-new/' + id)
                .then(function (resp) {
                    app.whatsnew = resp.data;
                })
                .catch(function () {
                    alert("Could not load your News")
                });

        },

        methods: {
            onFileChange(event) {
                this.whatsnew.imagefile = event.target.files[0]
                console.log(this.slider.imagefile);
            },
            saveForm() {
                var app = this;
                var newCompany = app.whatsnew;
                var form = new FormData();
                form.append('image',this.whatsnew.imagefile);
                form.append('title',this.whatsnew.title);
                form.append('description',this.whatsnew.description);
                form.append('is_active',this.whatsnew.is_active);
                form.append('id',app.$id);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/update-whats-new' ,form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your data update successfully.');
                            app.$router.replace('/home-page/whats-new');
                        }
                    }).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
