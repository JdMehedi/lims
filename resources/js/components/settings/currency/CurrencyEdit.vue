<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">Currency form</h5>
            </div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Code:</label>
                            <div :class="['col-md-8', allerros.code ? 'has-error' : '']">
                                <input type="text" v-model="currency.code" class="form-control">
                                <span v-if="allerros.code" :class="['text-danger']">{{ allerros.code[0] }}</span>
                            </div>

                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Name:</label>
                            <div :class="['col-md-8', allerros.name ? 'has-error' : '']">
                                <input type="text" v-model="currency.name" class="form-control">
                                <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
                            </div>

                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2">USD($) value:</label>
                            <div :class="['col-md-8', allerros.usd_value ? 'has-error' : '']">
                                <input type="text" v-model="currency.usd_value" class="form-control">
                                <span v-if="allerros.usd_value" :class="['text-danger']">{{ allerros.usd_value[0] }}</span>
                            </div>

                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2">BDT Value:</label>
                            <div :class="['col-md-8', allerros.bdt_value ? 'has-error' : '']">
                                <input type="text" v-model="currency.bdt_value" class="form-control">
                                <span v-if="allerros.bdt_value" :class="['text-danger']">{{ allerros.bdt_value[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="is_active" class="col-md-2 required-star">Active Status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="currency.is_active" name="is_active" type="radio"
                                               value="1" id="is_active">
                                    Active &nbsp;</label>
                                <label><input v-model="currency.is_active" name="is_active" type="radio"
                                               value="0" id="is_active"> Inactive</label>
                                <span v-if="allerros.is_active"
                                      :class="['text-danger']">{{ allerros.is_active[0] }}</span>

                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/currency-list" class="btn btn-default float-left"> Back</router-link>
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
                allerros: [],
                success : false,
                currency: {
                    code: '',
                    name: '',
                    usd_value: '',
                    bdt_value:'',
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
            app.$encrypted_id = id;
            axios.get('/settings/edit-currency/' + id)
                .then(function (resp) {
                    app.currency = resp.data;
                })
                .catch(function () {
                    alert("Could not load your Currency")
                });
        },

        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.currency;
                axios.patch('/settings/update-currency/' + app.$encrypted_id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/currency-list');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
