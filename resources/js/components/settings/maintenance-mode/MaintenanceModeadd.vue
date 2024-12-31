<template>
    <div>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">Operational Mode</div>
            <div class="card-body">
                <div class="col-md-10">
                    <form @submit.prevent="saveForm()">
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 required-star">Operation mode:
                                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                           title="The Maintenance mode option will take the system under maintenance, and general mode option will revert the system from maintenance."></i></label>
                                    <div class="col-md-9">
                                        <label class="radio-inline"><input v-model="maintenance.operation_mode" name="operation_mode" type="radio"
                                                      :checked="maintenance.operation_mode == 1" checked value="1" id="operation_mode" class="required selectOperationMode">
                                            General mode</label>
                                        <label class="radio-inline"><input v-model="maintenance.operation_mode" name="operation_mode" type="radio"
                                                      :checked="maintenance.operation_mode == 2"  value="2" id="operation_mode" class="required selectOperationMode">
                                            Maintenance mode</label>

                                        <span v-if="allerros.operation_mode" :class="['text-danger']">{{ allerros.operation_mode[0] }}</span>
                                    </div>
                                </div>
                                <br/>

                                <div class="maintenance_area">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            Allowed user types
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                               title="You may select user types to be allowed into the system while the maintenance period."></i>
                                        </legend>
                                        <div class="form-group">
                                            <label class="control-label col-md-2  required-star">User types:</label>
                                            <div class="col-md-9">
                                                <select v-model="maintenance.user_types_id"
                                                        class="maintenance_field form-control">
                                                    <option value="">Select One</option>
                                                    <option v-for="option in userData" :key="option.id" :value="option.id">{{option.type_name}}</option>

                                                </select>
                                                <span v-if="allerros.user_types" :class="['text-danger']">{{ allerros.user_types[0] }}</span>
                                                <br/>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br/>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            Allowed users
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                               title="Similar to user type"></i>
                                        </legend>
                                        <div class="form-group">
                                            <label class="col-sm-3">User email id:</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <input type="email" v-model="maintenance.user_email" class="form-control maintenance_field" placeholder="Enter user\'s email">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info maintenance_btn" type="submit" value="add_user"
                                                                name="submit_btn"> Add</button>
                                                    </span>
                                                </div>
                                                <span v-if="allerros.user_email" :class="['text-danger']">{{ allerros.user_email[0] }}</span>
                                            </div>
                                        </div>
                                        <br/>
                                        <br/>


                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <table id="user_list1" class="table table-striped table-bordered dt-responsive"
                                                       cellspacing="0"
                                                       width="100%" style="margin: 0">
                                                    <thead>
                                                    <tr>
                                                        <th>SN#</th>
                                                        <th>Email</th>
                                                        <th>Name</th>
                                                        <th>User type</th>
                                                        <th>Contact number</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
<!--                                                    @if(count($users) > 0)-->
<!--                                                    @foreach($users as $key => $user)-->
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <a class="btn btn-danger btn-xs maintenance_btn">Remove</a>
                                                        </td>
                                                    </tr>
<!--                                                    @endforeach-->
<!--                                                    @else-->
                                                    <tr>
                                                        <td colspan="6" style="text-align: center">
                                                            <span class="text-danger">No user in allowed list</span>
                                                        </td>
                                                        <td hidden></td>
                                                        <td hidden></td>
                                                        <td hidden></td>
                                                        <td hidden></td>
                                                        <td hidden></td>
                                                    </tr>
<!--                                                    @endif-->
                                                    </tbody>
                                                </table><br/><br/>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <br/>
                                    <div class="form-group">
                                        <label class="col-sm-3 required-star">Alert message:
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                               title="Alert message will be shown to user"></i></label>
                                        <div class="col-md-9">
                                            <textarea v-model="maintenance.alert_message" class="form-control bnEng maintenance_field" size="3x1"></textarea>
                                            <span v-if="allerros.alert_message" :class="['text-danger']">{{ allerros.alert_message[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <hr/>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="pull-left">
                                            <router-link to="users/lists" class="btn btn-default"><i class="fa fa-remove"></i>Close</router-link>
                                        </div>
                                        <div class="pull-right">
<!--                                            @if(ACL::getAccsessRight('settings','E'))-->
                                            <button type="submit" class="btn btn-primary pull-right">
                                                <i class="fa fa-chevron-circle-right"></i> Save
                                            </button>
<!--                                            @endif-->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
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
                userData:[],
                allerros: [],
                maintenance: {
                    operation_mode: '',
                    user_types: '',
                    user_email: '',
                    alert_message: '',

                },
            }

        },
        mounted() {
            // console.log('Component mounted.');
            $(".selectOperationMode").change(function () {
                var type = $('.selectOperationMode:checked').val();

                if (type == 1) {
                    $('.maintenance_field').attr('readonly', true);

                } else {
                    $('.maintenance_field').attr('readonly', false);
                }
             });
            },
        created() {
            this.getResults();
        },

        methods: {
            getResults(){
                this.$http.get('/settings/maintenance-mode-list')
                    .then(respdonse => {
                        console.log(respdonse.data);
                        this.userData = respdonse.data;
                    });

            },
            saveForm() {
                var app = this;
                axios.patch('/settings/need-help-update', this.operation_mode)
                    .then(function (resp) {
                        if (resp.data.status === true) {
                            app.$toaster.success('Your data update successfully.');
                            app.$router.push({path: '/need-help'});
                        }

                    } ).catch((error) => {
                    this.allerros = error.response.data.errors;
                });
            },
        }
    }
</script>
