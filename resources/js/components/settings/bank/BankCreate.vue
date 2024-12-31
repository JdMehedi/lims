
<template>
    <div>
        <div class="card card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">
                Create new Bank
                </h5>
            </div>
            <div class="card-body">
                <div class="col-md-9">
                <form   @submit.prevent="saveForm()">
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Name</label>
                        <div :class="['col-md-8', allerros.name ? 'has-error' : '']">
                            <input type="text" v-model="bank.name" class="form-control">
                            <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2  required-star">Code</label>
                        <div :class="['col-md-8', allerros.code ? 'has-error' : '']">
                            <input type="text" v-model="bank.code" class="form-control">
                            <span v-if="allerros.code" :class="['text-danger']">{{ allerros.code[0] }}</span>
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
                            <input type="text" v-model="bank.phone" class="form-control onlyNumber">
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
                            <span v-if="allerros.location" :class="['text-danger']">{{ allerros.location[0] }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-md-2  ">Address</label>
                        <div :class="['col-md-8', allerros.address ? 'has-error' : '']">
                            <input type="text" v-model="bank.address" class="form-control ">
                            <span v-if="allerros.address" :class="['text-danger']">{{ allerros.address[0] }}</span>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <router-link to="/bank-list" class="btn btn-default">Back</router-link>
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
    export default {
        data: function () {
            return {
                allerros: [],
                success : false,
                bank: {
                    name: '',
                    address: '',
                    website: '',
                    email: '',
                    phone: '',
                    location: '',
                    code: '',
                },
            }
        },
        mounted() {

        },
        methods: {
            saveForm() {
                var app = this;
                axios.post('/settings/store-bank-v2', this.bank)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your bank crate successfully.');
                            app.$router.push({path: '/bank-list'});
                        }

                    } ).catch((error) => {
                        this.allerros = error.response.data.errors;
                });
            }
        }
    }
</script>
