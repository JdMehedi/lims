<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">
                Edit Bank
                </h5>
            </div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Name</label>
                            <div :class="['col-md-8', allerros.name ? 'has-error' : '']">
                                <input type="text" v-model="bank.name" class="form-control">
                                <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Code</label>
                            <div :class="['col-md-8', allerros.bank_code ? 'has-error' : '']">
                                <input type="text" v-model="bank.bank_code" class="form-control">
                                <span v-if="allerros.bank_code"
                                      :class="['text-danger']">{{ allerros.bank_code[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Email</label>
                            <div :class="['col-md-8', allerros.email ? 'has-error' : '']">
                                <input type="text" v-model="bank.email" class="form-control email">
                                <span v-if="allerros.email" :class="['text-danger']">{{ allerros.email[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Phone</label>
                            <div :class="['col-md-8', allerros.phone ? 'has-error' : '']">
                                <input type="text" v-model="bank.phone" class="form-control">
                                <span v-if="allerros.phone" :class="['text-danger']">{{ allerros.phone[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2 ">Website</label>
                            <div :class="['col-md-8', allerros.website ? 'has-error' : '']">
                                <input type="text" v-model="bank.website" class="form-control">
                                <span v-if="allerros.website" :class="['text-danger']">{{ allerros.website[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Location</label>
                            <div :class="['col-md-8', allerros.location ? 'has-error' : '']">
                                <input type="text" v-model="bank.location" class="form-control">
                                <span v-if="allerros.location"
                                      :class="['text-danger']">{{ allerros.location[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2">Address</label>
                            <div :class="['col-md-8', allerros.address ? 'has-error' : '']">
                                <input type="text" v-model="bank.address" class="form-control">
                                <span v-if="allerros.address" :class="['text-danger']">{{ allerros.address[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="is_active" class="col-md-2 required-star">Active Status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="bank.is_active" name="is_active" type="radio"
                                                value="1" id="is_active">
                                    Active &nbsp;</label>
                                <label><input v-model="bank.is_active" name="is_active" type="radio"
                                                value="0" id="is_active"> Inactive</label>
                                <span v-if="allerros.is_active"
                                      :class="['text-danger']">{{ allerros.is_active[0] }}</span>

                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/bank-list" class="btn btn-default"> Back</router-link>
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
    import axios from 'axios'
    export default {
        data: function () {
            return {
                bank_id: null,
                allerros: [],
                bank: {
                    name: '',
                    address: '',
                    website: '',
                    email: '',
                    phone: '',
                    location: '',
                    bank_code: '',
                    is_active: '',
                },
            }
        },

        mounted() {

        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            console.log(id);
            app.bank_id = id;
            axios.get('/settings/edit-bank-v2/' + id)
                .then(function (resp) {
                    app.bank = resp.data;
                })
                .catch(function () {
                    alert("Could not load your company")
                });
        },

        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.bank;
                axios.post('/settings/update-bank-v2/' + app.bank_id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/bank-list');
                        }
                    }).catch((error) => {
                    // app.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
