<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <h5 class="card-title pt-2 pb-2">
                Edit Branch
                </h5>
            </div>
            <div class="card-body">
                <div class="col-md-9">
                    <form @submit.prevent="saveForm()">
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Name</label>
                            <div :class="['col-md-8', allerros.bank_id ? 'has-error' : '']">
                                <select v-model="branch.bank_id" id="fiat" class="form-control input-sm" >
                                    <option v-for="option in bankData" :key="option.id" :value="option.id">{{option.name}}</option>
                                </select>
                                <span v-if="allerros.bank_id" :class="['text-danger']">{{ allerros.bank_id[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Branch name:</label>
                            <div :class="['col-md-8', allerros.branch_name ? 'has-error' : '']">
                                <input type="text" v-model="branch.branch_name" class="form-control">
                                <span v-if="allerros.branch_name" :class="['text-danger']">{{ allerros.branch_name[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Branch Code</label>
                            <div :class="['col-md-8', allerros.branch_code ? 'has-error' : '']">
                                <input type="text" v-model="branch.branch_code" class="form-control onlyNumber">
                                <span v-if="allerros.branch_code" :class="['text-danger']">{{ allerros.branch_code[0] }}</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2  required-star">Address:</label>
                            <div :class="['col-md-8', allerros.address ? 'has-error' : '']">
                                <input type="text" v-model="branch.address" class="form-control">
                                <span v-if="allerros.address" :class="['text-danger']">{{ allerros.address[0] }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-md-2 ">Manager Info</label>
                            <div :class="['col-md-8', allerros.manager_info ? 'has-error' : '']">
                                <input type="text" v-model="branch.manager_info" class="form-control">
                                <span v-if="allerros.manager_info" :class="['text-danger']">{{ allerros.manager_info[0] }}</span>
                            </div>
                        </div>


                        <div class="row form-group">
                            <label for="is_active" class="col-md-2 required-star">Active Status: </label>
                            <div class="col-md-6 ">
                                <label><input v-model="branch.is_active" name="is_active" type="radio"
                                                value="1" id="is_active">
                                    Active&nbsp;</label>
                                <label><input v-model="branch.is_active" name="is_active" type="radio"
                                                value="0" id="is_active"> Inactive</label>
                                <span v-if="allerros.is_active"
                                      :class="['text-danger']">{{ allerros.is_active[0] }}</span>

                            </div>
                        </div>

                        <div class="col-md-10">
                            <router-link to="/branch-list" class="btn btn-default">Back</router-link>
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
                branch_id: null,
                allerros: [],
                branch: {
                    bank_id: '',
                    branch_name: '',
                    branch_code: '',
                    address: '',
                    manager_info: '',
                    is_active: '',
                },
                bankData: [],
                selected: null
            }
        },

        mounted() {

        },
        created() {
            let app = this;
            let id = app.$route.params.id;
            app.branch_id = id;
            axios.get('/settings/edit-branch/' + id)
                .then(function (resp) {
                    app.branch = resp.data;
                })
                .catch(function () {
                    alert("Could not load your company")
                });
            app.getbank();
        },
        methods: {
            saveForm() {
                var app = this;
                var newCompany = app.branch;
                axios.post('/settings/update-branch/' + app.branch_id, newCompany)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toast.success('Your data update successfully.');
                            app.$router.push('/branch-list');
                        }
                    }).catch((error) => {
                    app.allerros = error.response.data.errors;
                });
            },
            getbank(){
                var app = this
                axios.get('/settings/get-bank-name')
                    .then(respdonse => {
                        app.bankData = respdonse.data;
                    });

            },
        }
    }
</script>
