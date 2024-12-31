<template>
<div class="card card-magenta border border-magenta">
    <div class="card-header">
        <h5 class="card-title pt-2 pb-2">User Manual Edit Form</h5>
    </div>
    <form @submit.prevent="saveForm()" id="user-manual">
        <div class="card-body">
            <div class="col-md-10">
                <div class="row form-group">
                    <label class="control-label col-md-2  required-star">License Name:</label>
                    <div :class="['col-md-8', allerros.typeName ? 'has-error' : '']">
                        <input v-model="usermanual.typeName" class="form-control" name="typeName" type="text">
<!--                        <select class="form-control" v-model="usermanual.typeName" >-->
<!--                            <option value="" >Select</option>-->
<!--                            <option v-for="(item,index) in processType" :key="index" :value="item.id"  >{{ item.name }}</option>-->
<!--                        </select>-->
                        <span v-if="allerros.typeName" :class="['text-danger']">{{ allerros.typeName[0] }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-md-2 ">Pdf File:</label>
                    <div :class="['col-md-6', allerros.files ? 'has-error' : '']">
                        <input type="file" id="files" ref="files"  class="form-control" v-on:change="onFileChange" accept="application/pdf"/>
                        <span v-if="allerros.files" :class="['text-danger']">{{ allerros.files[0] }}</span>
                        <span class="help-block">[File Format: *.pdf | Max size 10 MB]</span>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-info" target="_blank" v-bind:href="'/'+ usermanual.pdfFile"><i
                                class="fa fa-file-image-o"></i> View User Manual</a>
                    </div>
                </div>
                <div class="row form-group ">
                    <label  class="col-md-2  required-star">Status: </label>
                    <div class="col-md-10">
                        <label><input v-model="usermanual.status" name="status" type="radio"
                                        value="1" > Active&nbsp;</label>
                        <label><input v-model="usermanual.status" name="status" type="radio"
                                        value="0" > Inactive</label>
                        <span v-if="allerros.status" :class="['text-danger']">{{ allerros.usermanual[0] }}</span>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-md-2">Order:</label>
                    <div :class="['col-md-8', allerros.order ? 'has-error' : '']">
                        <input v-model="usermanual.order" class="form-control" name="order" type="number">
                        <span v-if="allerros.order" :class="['text-danger']">{{ allerros.order[0] }}</span>
                    </div>
                </div>
                <div class="col-md-10">
                    <router-link to="/home-page/user-manual" class="btn btn-default"><i
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
    import vSelect from "vue-select";
    // const customClass = new API();
    export default {
        data: function () {
            return {
                $id: null,
                allerros: [],
                usermanual: {
                    typeName: '',
                    files: '',
                    details: '',
                    termsCondition: '',
                    status:'',
                    order: '',
                },
                processType: []
            }

        },
        components: {
            'editor': Editor,
            'v-select': vSelect
        },
        mounted() {

            $(document).ready(function () {
                $("#user-manual").validate({
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
            axios.get('/settings/home-page/edit-user-manual/' + id)
                .then(function (resp) {
                    app.usermanual = resp.data;
                    app.usermanual.typeName = resp.data.typeName;
                })
                .catch(function () {
                    alert("Could not load your usermanual")
                });
            this.getProcessType();
        },

        methods: {
            onFileChange(event) {
                this.usermanual.imagefile = event.target.files[0]
            },
            saveForm() {
                var app = this;
                var newCompany = app.usermanual;
                var form = new FormData();
                form.append('pdfFile',this.usermanual.imagefile);
                form.append('typeName',this.usermanual.typeName);
                form.append('details',this.usermanual.details);
                form.append('termsCondition',this.usermanual.termsCondition);
                form.append('status',this.usermanual.status);
                form.append('order',this.usermanual.order);
                form.append('id',app.id);
                axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
                axios.post('/settings/home-page/update-user-manual',form)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.replace('/home-page/user-manual');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
            getProcessType(){
                let self = this;
                axios.get('/settings/get-process-type')
                    .then(function (resp) {
                        if (resp.data.responseStatus == 1) {
                            self.processType = resp.data.processData;
                            console.log(self.processType);
                        }

                    } ).catch((error) => {
                    alert(error)
                });
            }
        }
    }
</script>
