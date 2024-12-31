<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5> Title & Logo </h5>
            </div>
            <div class="card-body">
                <div class="col-md-10">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-2 required-star">Image:</label>
                            <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                                <input type="file" id="files" ref="files"  v-on:change="onFileChange"/>
                                <a v-bind:href="'/'+ laravelData.logo">{{laravelData.logo}}</a>
                                <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                                <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.jpg/ .jpeg | File size within 200 KB]</span><br/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Title:</label>
                            <div :class="['col-md-8', allerros.title ? 'has-error' : '']">
                                <input type="text" v-model="laravelData.title" class="form-control">
                                <span v-if="allerros.title" :class="['text-danger']">{{ allerros.title[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Manage by:</label>
                            <div :class="['col-md-8', allerros.manage_by ? 'has-error' : '']">
                                <input type="text" v-model="laravelData.manage_by" class="form-control">
                                <span v-if="allerros.manage_by" :class="['text-danger']">{{ allerros.manage_by[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Help Link:</label>
                            <div :class="['col-md-8', allerros.help_link ? 'has-error' : '']">
                                <input type="text" v-model="laravelData.help_link" class="form-control">
                                <span v-if="allerros.help_link" :class="['text-danger']">{{ allerros.help_link[0] }}</span>
                            </div>
                        </div>


                        <div class="col-md-10">
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
    import {API} from '../../../custom.js';
    const customClass = new API();
    export default {
        data: function () {
            return {
                laravelData: [],
                allerros: [],
                laravelData: {
                    title: '',
                    imagefile: '',
                    manage_by: '',
                    help_link: '',
                },
            }

        },

        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getResults();

        },

        methods: {
            getResults(){
                axios.get('/settings/logo-edit')
                    .then(respdonse => {
                        this.laravelData = respdonse.data;
                });

             },
            onFileChange(event) {
                this.laravelData.imagefile = event.target.files[0]
                console.log(this.laravelData.imagefile);
            },
            saveForm() {
                var app = this;
                var newCompany = app.laravelData;
                var form = new FormData();
                form.append('company_logo',this.laravelData.imagefile);
                form.append('title',this.laravelData.title);
                form.append('manage_by',this.laravelData.manage_by);
                form.append('help_link',this.laravelData.help_link);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/update-logo', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            location.reload();
                            app.$toast.success('Your data update successfully.');
                            app.$router.push({path: '/edit-logo'});
                        }

                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
