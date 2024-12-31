<template>

<div class="card card-magenta border border-magenta">
    <div class="card-header"><h5  class="card-title pt-2 pb-2">Home page slider</h5></div>
    <form @submit.prevent="saveForm()">
    <div class="card-body">
        <div class="col-md-10">
            <div class="row form-group">
                <label class="control-label col-md-2 required-star">Slider Image:</label>
                <div :class="['col-md-8', allerros.files ? 'has-error' : '']">
                    <input type="file" id="files" ref="files" class="form-control" v-on:change="onFileChange"/>
                    <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                    <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif | Max size 1 MB]</span>
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
                                  :checked="slider.status"  value="1" > Active &nbsp;</label>
                    <label><input v-model="slider.status" name="status" type="radio"
                                  :checked="slider.status"  value="0"> Inactive</label>
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
    <!--<div class="panel-footer">
        <div class="pull-left">
            <router-link to="/home-page/home-page-slider" class="btn btn-default"><i
                    class="fa fa-chevron-circle-left"></i> Back</router-link>
        </div>
        <div class="pull-right">
            <button type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        </div>
        <div class="clearfix"></div>
    </div>-->

    </form>
</div>

</template>

<script>
    import {API} from '../../../custom.js';
    const customClass = new API();
    export default {
        data: function () {
            return {
                allerros: [],
                success : false,
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

            // customClass.isEmail();
        },


        methods: {
            onFileChange(event) {
                this.slider.imagefile = event.target.files[0]
                console.log(this.slider.imagefile);
            },
            saveForm() {
                var app = this;
                var form = new FormData();
                form.append('slider_image',this.slider.imagefile);
                form.append('slider_url',this.slider.slider_url);
                form.append('slider_title',this.slider.slider_title);
                form.append('slider_type',this.slider.slider_type);
                form.append('status',this.slider.status);

                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/store-home-page-slider', form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your slider crate successfully.');
                            app.$router.push({path: '/home-page/home-page-slider'});
                        }

                    } ).catch((error) => {
                        app.allerros = error.response.data.errors;
                });
            },

        }
    }
</script>
