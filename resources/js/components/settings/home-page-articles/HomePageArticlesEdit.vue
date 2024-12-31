<template>

<div class="card card-magenta border border-magenta">
    <div class="card-header">
        <h5 class="card-title pt-2 pb-2">
        Home Page Articles Edit Form
        </h5>
    </div>
    <form @submit.prevent="saveForm()" id="HomeArticles">
        <div class="card-body">
            <div class="col-md-12">
                <div class="row form-group">
                    <label class="control-label col-md-2  required-star">Page Name:</label>
                    <div :class="['col-md-10', allerros.page_name ? 'has-error' : '']">
                        <input type="text" v-model="HomeArticles.page_name" class="form-control" readonly>
                        <span v-if="allerros.page_name" :class="['text-danger']">{{ allerros.page_name[0] }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-md-2  required-star">Page Content (বাংলা):</label>
                    <div :class="['col-md-10', allerros.page_content ? 'has-error' : '']">
                        <editor
                                v-model="HomeArticles.page_content"
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
                        <span v-if="allerros.page_content" :class="['text-danger']">{{ allerros.page_content[0] }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-md-2  required-star">Page Content (English) :</label>
                    <div :class="['col-md-10', allerros.page_content_en ? 'has-error' : '']">
                        <editor
                                v-model="HomeArticles.page_content_en"
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
                        <span v-if="allerros.page_content_en" :class="['text-danger']">{{ allerros.page_content_en[0] }}</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <router-link to="/home-page/home-page-articles" class="btn btn-default"><i
                        class="fa fa-chevron-circle-left"></i> Back</router-link>
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
    // import {API} from '../../../custom.js';
    import Editor from "@tinymce/tinymce-vue";
    // const customClass = new API();
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                HomeArticles: {
                    page_name: '',
                    page_content: '',
                    page_content_en: '',
                },
            }

        },
        components: {
            'editor': Editor
        },
        mounted() {

            $(document).ready(function () {
                $("#HomeArticles").validate({
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
            axios.get('/settings/home-page/edit-home-page-articles/' + id)
                .then(function (resp) {
                    app.HomeArticles = resp.data;
                })
                .catch(function () {
                    alert("Could not load your home page articles")
                });
        },

        methods: {

            saveForm() {
                var app = this;
                var newCompany = app.HomeArticles;
                var form = new FormData();
                form.append('page_name',this.HomeArticles.page_name);
                form.append('page_content',this.HomeArticles.page_content);
                form.append('page_content_en',this.HomeArticles.page_content_en);
                form.append('id',app.id);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/update-home-page-articles',form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/home-page/home-page-articles');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
