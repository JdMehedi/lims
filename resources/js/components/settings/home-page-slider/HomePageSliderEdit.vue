<template>

<div class="card card-magenta border border-magenta">
    <div class="card-header"><h5  class="card-title pt-2 pb-2">Home page slider</h5></div>
    <form @submit.prevent="saveForm()">
    <div class="card-body">
        <div class="col-md-10">
            <div class="row form-group">
                <label class="control-label col-md-2 required-star">Slider Image:</label>
                <div :class="['col-md-6', allerros.files ? 'has-error' : '']">
                    <input type="file" id="files"  class="form-control" ref="files"  v-on:change="onFileChange"/>
                    <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                    <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif | Max size 1 MB]</span>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-info" target="_blank" v-bind:href="'/'+ slider.slider_image"><i
                            class="fa fa-file-image-o"></i> View Photo</a>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-md-2 ">Title:</label>
                <div :class="['col-md-8', allerros.slider_title ? 'has-error' : '']">
                    <input type="text" v-model="slider.slider_title" class="form-control">
                    <span v-if="allerros.slider_title" :class="['text-danger']">{{ allerros.slider_title[0] }}</span>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-md-2">URL:</label>
                <div :class="['col-md-8', allerros.slider_url ? 'has-error' : '']">
                    <input type="text" v-model="slider.slider_url" class="form-control">
                    <span v-if="allerros.slider_url" :class="['text-danger']">{{ allerros.slider_url[0] }}</span>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-md-2 ">Slider Type:</label>
                <div :class="['col-md-8', allerros.slider_type ? 'has-error' : '']">
                    <select v-model="slider.slider_type" class="form-control input-sm ">
                        <option value=""> Select one</option>
                        <option value="0"> Image</option>
                        <option value="1"> DOD</option>
                    </select>

                    <span v-if="allerros.slider_type" :class="['text-danger']">{{ allerros.slider_type[0] }}</span>
                </div>
            </div>
            <div class="row form-group ">
                <label class="col-md-2  required-star">Status: </label>
                <div class="col-md-10">
                    <label><input v-model="slider.status" name="status" type="radio"
                                   value="1" > Active&nbsp;</label>
                    <label><input v-model="slider.status" name="status" type="radio"
                                   value="0" > Inactive</label>
                    <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
                </div>
            </div>
            <div class="col-md-10">
                <router-link to="/home-page/home-page-slider" class="btn btn-default"><i
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
    import {API} from '../../../custom.js';
    const customClass = new API();
    export default {
        data: function () {
            return {
                id: null,
                allerros: [],
                slider: {
                    imagefile: '',
                    slider_title: '',
                    slider_url: '',
                    slider_type: '',
                    status:'',
                },
            }

        },
        mounted() {
            // customClass.onlyNumber();
            // customClass.isEmail();
        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            console.log(id);
            app.id = id;
            axios.get('/settings/home-page/edit-home-page-slider/' + id)
                .then(function (resp) {
                    app.slider = resp.data;
                })
                .catch(function () {
                    alert("Could not load your slider")
                });
        },

        methods: {
            onFileChange(event) {
                this.slider.imagefile = event.target.files[0]
                console.log(this.slider.imagefile);
            },
            saveForm() {
                var app = this;
                var newCompany = app.slider;
                var form = new FormData();
                form.append('slider_image',this.slider.imagefile);
                form.append('slider_url',this.slider.slider_url);
                form.append('slider_title',this.slider.slider_title);
                form.append('slider_type',this.slider.slider_type);
                form.append('status',this.slider.status);
                form.append('id',app.id);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/update-home-page-slider' ,form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/home-page/home-page-slider');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
