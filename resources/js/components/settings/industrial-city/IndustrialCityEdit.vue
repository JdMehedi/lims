<template>
  <div>
    <div class="card card-magenta border border-magenta">
      <div class="card-header">
        <h5 class="card-title pt-2 pb-2"><strong>Edit Industrial City</strong></h5>
      </div>

      <form @submit.prevent="saveForm()" id="industrialCity">
        <div class="card-body">
          <div class="col-md-11">

            <div class="row form-group ">
              <label class="col-md-3 required-star">Type: </label>
              <div class="col-md-9">
                <label><input v-model="industrialCity.type" name="is_type" type="radio" id="show" @click="show = true"
                              :checked="industrialCity.type" value="0"> Home&nbsp;</label>
                <label><input v-model="industrialCity.type" name="is_type" type="radio" id="hide" @click="show = false"
                              :checked="industrialCity.type" value="1"> Office</label>
                <span v-if="allerros.type" :class="['text-danger']">{{ allerros.type[0] }}</span>
              </div>
            </div>

            <div v-if="show && industrialCity.type == '0'">
            </div>
            <div v-else>
              <div class="row form-group">
                <label class="col-md-3 required-star">Office Short Code:</label>
                <div :class="['col-md-9', allerros.office_short_code ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.office_short_code" class="form-control">
                  <span v-if="allerros.office_short_code" :class="['text-danger']">{{ allerros.office_short_code[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3 required-star">Home Office Name:</label>
                <div :class="['col-md-9', allerros.homeOffice ? 'has-error' : '']">
                  <select v-model="industrialCity.h_office_id"  class="form-control input-sm" id="homeOffice" >
                    <option value="">Select One</option>
                    <option v-for="option in homeOffice" :key="option.id" :value="option.id">{{option.name}}</option>
                  </select>
                  <span v-if="allerros.homeOffice" :class="['text-danger']">{{ allerros.homeOffice[0] }}</span>
                </div>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Name (English):</label>
              <div :class="['col-md-9', allerros.name_en ? 'has-error' : '']">
                <input type="text" v-model="industrialCity.name_en" class="form-control">
                <span v-if="allerros.name_en" :class="['text-danger']">{{ allerros.name_en[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Name (বাংলা):</label>
              <div :class="['col-md-9', allerros.name ? 'has-error' : '']">
                <input type="text" v-model="industrialCity.name" class="form-control">
                <span v-if="allerros.name" :class="['text-danger']">{{ allerros.name[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">District (English):</label>
              <div :class="['col-md-9', allerros.district_en ? 'has-error' : '']">
                <select v-model="industrialCity.district_en"  class="form-control input-sm" id="district" >
                  <option value="">Select One</option>
                  <option v-for="option in districtsData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>
                </select>
                <span v-if="allerros.district_en" :class="['text-danger']">{{ allerros.district_en[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">District (বাংলা):</label>
              <div :class="['col-md-9', allerros.district ? 'has-error' : '']">
                <select v-model="industrialCity.district"  class="form-control input-sm" >
                  <option value="">নির্বাচন করুন </option>
                  <option v-for="option in districtsData" :key="option.id" :value="option.area_id">{{option.area_nm_ban}}</option>
                </select>
                <span v-if="allerros.district" :class="['text-danger']">{{ allerros.district[0] }}</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-md-3 required-star">Upazila (English):</label>
              <div :class="['col-md-9', allerros.upazila_en ? 'has-error' : '']">
                <select v-model="industrialCity.upazila_en"  class="form-control input-sm" id="upazila">
                  <option value="">Select District First</option>
                  <option v-for="option in upazilasData" :key="option.id" :value="option.area_id">{{option.area_nm}}</option>
                </select>
                <span v-if="allerros.upazila_en" :class="['text-danger']">{{ allerros.upazila_en[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Upazila (বাংলা):</label>
              <div :class="['col-md-9', allerros.upazila ? 'has-error' : '']">
                <select v-model="industrialCity.upazila"  class="form-control input-sm" id="upazilaBN">
                  <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                  <option v-for="option in upazilasData" :key="option.id" :value="option.area_id">{{option.area_nm_ban}}</option>
                </select>
                <span v-if="allerros.upazila" :class="['text-danger']">{{ allerros.upazila[0] }}</span>
              </div>
            </div>
            <div v-if="show && industrialCity.type == '0'">
              <div class="row form-group">
                <label class="col-md-3 required-star">Details ( English ):</label>
                <div :class="['col-md-9', allerros.details_en ? 'has-error' : '']">
                  <editor
                          v-model="industrialCity.details_en"
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
                  <span v-if="allerros.details_en" :class="['text-danger']">{{ allerros.details_en[0] }}</span>
                </div>
              </div>
              <div class="row form-group">
                <label class="col-md-3  required-star">Details (বাংলা ):</label>
                <div :class="['col-md-9', allerros.details ? 'has-error' : '']">
                  <editor
                          v-model="industrialCity.details"
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
                  <span v-if="allerros.details" :class="['text-danger']">{{ allerros.details[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Image:</label>
                <div :class="['col-md-7', allerros.files ? 'has-error' : '']">
                  <input type="file" id="files" ref="files" class="form-control" v-on:change="onFileChange"/>
                  <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif/ .svg | Max size 1 MB]</span>
                  <span v-if="allerros.image" :class="['text-danger']">{{ allerros.image[0] }}</span>
                </div>
                <div class="col-md-2" v-if="industrialCity.image !== ''">
                  <a class="btn btn-info" target="_blank" v-bind:href="'/'+ industrialCity.image"><i
                          class="fa fa-file-image-o"></i> View Photo</a>
                </div>
                <div class="col-md-2" v-else>

                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3 required-star">Latitude:</label>
                <div :class="['col-md-9', allerros.latitude ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.latitude" class="form-control">
                  <span v-if="allerros.latitude" :class="['text-danger']">{{ allerros.latitude[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3 required-star">Longitude:</label>
                <div :class="['col-md-9', allerros.longitude ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.longitude" class="form-control">
                  <span v-if="allerros.longitude" :class="['text-danger']">{{ allerros.longitude[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Establishment Year (স্থাপনকাল):</label>
                <div :class="['col-md-9', allerros.establish_year ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.establish_year" class="form-control">
                  <span v-if="allerros.establish_year" :class="['text-danger']">{{ allerros.establish_year[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Land Amount (জমির পরিমাণ (একরে)):</label>
                <div :class="['col-md-9', allerros.land_amount ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.land_amount" class="form-control">
                  <span v-if="allerros.land_amount" :class="['text-danger']">{{ allerros.land_amount[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Per Acre Price (একর প্রতি জমির মূল্য (লক্ষ টাকায়)):</label>
                <div :class="['col-md-9', allerros.per_acre_price ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.per_acre_price" class="form-control">
                  <span v-if="allerros.per_acre_price" :class="['text-danger']">{{ allerros.per_acre_price[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Plot used for Office & Others (অফিস ও অন্যান্য কাজে ব্যবহৃত প্লট):</label>
                <div :class="['col-md-9', allerros.used_plot ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.used_plot" class="form-control">
                  <span v-if="allerros.used_plot" :class="['text-danger']">{{ allerros.used_plot[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Industrial PLot Number (শিল্প প্লট সংখ্যা):</label>
                <div :class="['col-md-9', allerros.ind_plot_no ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_plot_no" class="form-control">
                  <span v-if="allerros.ind_plot_no" :class="['text-danger']">{{ allerros.ind_plot_no[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Total Allocated PLot (মোট বরাদ্দকৃত প্লট):</label>
                <div :class="['col-md-9', allerros.total_plot_allocated ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.total_plot_allocated" class="form-control">
                  <span v-if="allerros.total_plot_allocated" :class="['text-danger']">{{ allerros.total_plot_allocated[0] }}</span>
                </div>
              </div>

              <div class="col-md-12" style="margin-bottom: 15px;">
                <div class="col-md-12">
                  <h4>Industrial unit Details (বরাদ্দকৃত প্লটে শিল্প ইউনিটের বিবরণ)</h4>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Total (মোট):</label>
                <div :class="['col-md-9', allerros.ind_unit_total ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_unit_total" class="form-control">
                  <span v-if="allerros.ind_unit_total" :class="['text-danger']">{{ allerros.ind_unit_total[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Under Production (উৎপাদনরত):</label>
                <div :class="['col-md-9', allerros.ind_unit_under_prod ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_unit_under_prod" class="form-control">
                  <span v-if="allerros.ind_unit_under_prod" :class="['text-danger']">{{ allerros.ind_unit_under_prod[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Under Construction/ Not Started Yet (নির্মানাধীন/নির্মাণ কাজ শুরু হয়নি):</label>
                <div :class="['col-md-9', allerros.ind_unit_under_cons ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_unit_under_cons" class="form-control">
                  <span v-if="allerros.ind_unit_under_cons" :class="['text-danger']">{{ allerros.ind_unit_under_cons[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Stopped (বন্ধ):</label>
                <div :class="['col-md-9', allerros.ind_unit_off ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_unit_off" class="form-control">
                  <span v-if="allerros.ind_unit_off" :class="['text-danger']">{{ allerros.ind_unit_off[0] }}</span>
                </div>
              </div>

              <div class="row form-group">
                <label class="col-md-3">Plot Awaiting for Allocation (বরাদ্দের অপেক্ষায় প্লট সংখ্যা (অনুন্নত)):</label>
                <div :class="['col-md-9', allerros.ind_unit_allocate_wait ? 'has-error' : '']">
                  <input type="text" v-model="industrialCity.ind_unit_allocate_wait" class="form-control">
                  <span v-if="allerros.ind_unit_allocate_wait" :class="['text-danger']">{{ allerros.ind_unit_allocate_wait[0] }}</span>
                </div>
              </div>

            </div>


            <div class="row form-group">
              <label class="col-md-3">Order:</label>
              <div :class="['col-md-9', allerros.order ? 'has-error' : '']">
                <input type="number" v-model="industrialCity.order" class="form-control" size="10x5" min="0">
                <span v-if="allerros.order" :class="['text-danger']">{{ allerros.order[0] }}</span>
              </div>
            </div>

            <div class="row form-group ">
              <label class="col-md-3 required-star">Status: </label>
              <div class="col-md-9">
                <label><input v-model="industrialCity.status" name="is_active" type="radio"
                               value="1"> Active&nbsp;</label>
                <label><input v-model="industrialCity.status" name="is_active" type="radio"
                              value="0"> Inactive</label>
                <span v-if="allerros.status" :class="['text-danger']">{{ allerros.status[0] }}</span>
              </div>
            </div>
              <div class="col-md-12">
                  <router-link to="/home-page/industrial-city" class="btn btn-default"><i
                      class="fa fa-chevron-circle-left"></i> Back
                  </router-link>
                  <button type="submit" class="btn btn-primary float-right">
                      <i class="fa fa-chevron-circle-right"></i> Save
                  </button>
              </div>

          </div>
        </div>
      </form>
    </div>


    <div class="card card-magenta border border-magenta">

      <div class="card-header">
          <div class="float-left">
              <h5 class="card-title pt-2 pb-2"><i class="fa fa-list"></i> List of Master Plan</h5>
          </div>
        <div class="float-right">
          <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#createMasterPlanModal">
            <i
                class="fa fa-plus"></i> Add New Master Plan
          </button>
        </div>
        <!-- /.box-tools -->
      </div>

      <div class="card-body">
          <div class="col-md-1 float-left">
              <select class="form-control col-md-12" v-model="limits"  @change="limit($event)">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
              </select>
          </div>
          <div class=" col-md-offset-8 col-md-3 float-right">
              <input class="form-control" type="text" placeholder="Search..."
                     v-model="search" v-on:keyup="keymonitor">
          </div>
          <br>
          <br>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table vue-table table-striped table-bordered">
                <thead>
                <tr>
                  <th>#SL</th>
                  <th>Name</th>
                  <th>Remarks</th>
                  <th>Document</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="industrial in masterPlanList.data" :key="industrial.id">
                  <td>{{ industrial.si }}</td>
                  <td>{{ industrial.name }}</td>
                  <td>{{ industrial.remarks }}</td>
                  <td>
                    <a v-if="industrial.document" class="btn btn-info btn-xs" target="_blank"
                       v-bind:href="'/'+ industrial.document"><i
                        class="fa fa-file-image-o"></i> Open document</a>
                  </td>
                  <td>
                  <span v-if="industrial.status == 1">
                    <span class="text-success">Active&nbsp;</span>
                  </span>
                    <span v-else>
                    <span class="text-danger">Inactive</span>
                  </span>
                  </td>
                  <td>
                    <router-link :to="{name: 'MasterPlanEdit', params: {id: industrial.id}}"
                                 class="btn btn-xs btn-primary">
                      <i class="fa fa-edit"></i> Edit
                    </router-link>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="createMasterPlanModal"
         aria-labelledby="createMasterPlanModal">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
              <h4 class="modal-title" id="gridSystemModalLabel">Create master plan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true" style="font-size: 22px;">&times;</span></button>
          </div>
          <form @submit.prevent="createMasterPlan()" id="masterPlanForm">
            <div class="modal-body">
            <div class="row form-group">
              <label class="col-md-3 required-star">Name (English):</label>
              <div :class="['col-md-9', masterPlanFormErrors.mp_name_en ? 'has-error' : '']">
                <input type="text" v-model="masterPlanForm.mp_name_en" class="form-control">
                <span v-if="masterPlanFormErrors.mp_name_en"
                      :class="['text-danger']">{{ masterPlanFormErrors.mp_name_en[0] }}</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-md-3 required-star">Name (বাংলা):</label>
              <div :class="['col-md-9', masterPlanFormErrors.mp_name_bn ? 'has-error' : '']">
                <input type="text" v-model="masterPlanForm.mp_name_bn" class="form-control">
                <span v-if="masterPlanFormErrors.mp_name_bn" :class="['text-danger']">{{
                    masterPlanFormErrors.mp_name_bn[0]
                  }}</span>
              </div>
            </div>


              <div class="row form-group">
                  <label class="col-md-3 required-star">Remarks (English):</label>
                  <div :class="['col-md-9', masterPlanFormErrors.mp_remarks_en ? 'has-error' : '']">
                    <textarea v-model="masterPlanForm.mp_remarks_en" class="form-control"></textarea>
                    <span v-if="masterPlanFormErrors.mp_remarks_en"
                          :class="['text-danger']">{{ masterPlanFormErrors.mp_remarks_en[0] }}</span>
                  </div>
                </div>
              <div class="row form-group">
                  <label class="col-md-3 required-star">Remarks (বাংলা):</label>
                  <div :class="['col-md-9', masterPlanFormErrors.mp_remarks_bn ? 'has-error' : '']">
                    <textarea v-model="masterPlanForm.mp_remarks_bn" class="form-control"></textarea>
                    <span v-if="masterPlanFormErrors.mp_remarks_bn"
                          :class="['text-danger']">{{ masterPlanFormErrors.mp_remarks_bn[0] }}</span>
                  </div>
                </div>
              <div class="row form-group">
                  <label class="col-md-3 required-star">Document:</label>
                  <div :class="['col-md-9', masterPlanFormErrors.mp_doc ? 'has-error' : '']">
                    <input type="file" id="mp_doc" class="form-control"
                           v-on:change="setMasterPlanDocument"/>
                    <span class="help-block">[File Format: *.jpg/ .jpeg/ .png/ .gif/ .pdf | Max size 2 MB]</span>
                    <span v-if="masterPlanFormErrors.mp_doc" :class="['text-danger']">{{
                        masterPlanFormErrors.mp_doc[0]
                      }}</span>
                  </div>
                </div>

            </div>
              <div class="modal-footer">
                  <div class="col-md-12">
                      <button type="submit" class="btn btn-primary float-right mr-1">Create Master Plan</button> &nbsp;
                      <button type="button" class="btn btn-default float-right mr-1" data-dismiss="modal">Close</button> &nbsp;
                  </div>
              </div>
          </form>

        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import Editor from "@tinymce/tinymce-vue";
export default {
  data: function () {
    return {
        show: true,
      id: null,
      allerros: [],
      industrialCity: {
        office_short_code: '',
        h_office_id: '',
        name_en: '',
        name: '',
        district_en: '',
        district: '',
        upazila_en: '',
        upazila: '',
        details_en: '',
        details: '',
        image: '',
        latitude: '',
        longitude: '',
        order: '',
        status: '',
        type: '',
        establish_year: '',
        land_amount: '',
        per_acre_price: '',
        used_plot: '',
        ind_plot_no: '',
        total_plot_allocated: '',
        ind_unit_total: '',
        ind_unit_under_prod: '',
        ind_unit_under_cons: '',
        ind_unit_off: '',
        ind_unit_allocate_wait: '',
      },
      districtsData: [],
      upazilasData: [],
      homeOffice: [],
      selected: null,
      masterPlanList: {},
      masterPlanForm: {
        mp_name_en: '',
        mp_name_bn: '',
        mp_remarks_en: '',
        mp_remarks_bn: '',
        mp_doc: ''
      },
      masterPlanFormErrors: {},
      limits: 10,
      search: '',
    }
  },
  components: {
    'editor': Editor
  },

  mounted() {
    $("#district").change(function () {
      $(this).after('<span class="loading_data">Loading...</span>');
      var self = $(this);
      var districtId = $('#district').val();
      if (districtId !== '') {
        $("#loaderImg").html("<img alt='...' style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
        $.ajax({
          type: "GET",
          url: "/settings/get-upazila-by-district-id",
          data: {
            districtId: districtId
          },
          success: function (response) {
            var option = '<option value="">Select One</option>';
            var optionTwo = '<option value="">নির্বাচন করুন</option>';
            if (response.responseCode == 1) {
              $.each(response.data, function (id, value) {
                option += '<option value="' + value.area_id + '">' + value.area_nm + '</option>';
                optionTwo += '<option value="' + value.area_id + '">' + value.area_nm_ban + '</option>';
              });
            }
            $("#upazila").html(option);
            $("#upazilaBN").html(optionTwo);
            self.next().hide();
          }
        });
      }else{
        $("#upazila").html('<option value="">Select District First</option>');
        $("#upazilaBN").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>');
        $(self).next().hide();
      }
    });

    $(document).ready(function () {
      $("#industrialCity").validate({
        errorPlacement: function () {
          return false;
        }
      });
    });
  },
  created() {
    this.getdistrict();
    this.getUpazila();
    let app = this;
    let id = app.$route.params.id;
    app.id = id;
    axios.get('/settings/home-page/edit-industrial-city/' + id)
        .then(function (resp) {
          app.industrialCity = resp.data.industryData;
          app.homeOffice = resp.data.homeOfficeData;
        })
        .catch(function () {
          alert("Could not load your News")
        });

    this.getMasterPlanList();
  },

  methods: {
    onFileChange(event) {
      this.industrialCity.image = event.target.files[0]
    },
    setMasterPlanDocument(event) {
      this.masterPlanForm.mp_doc = event.target.files[0]
    },

    saveForm() {
      const app = this;
      const form = new FormData();
      form.append('image', this.industrialCity.image);
      form.append('name', this.industrialCity.name);
      form.append('office_short_code', this.industrialCity.office_short_code);
      form.append('name_en', this.industrialCity.name_en);
      form.append('district', this.industrialCity.district);
      form.append('district_en', this.industrialCity.district_en);
      form.append('upazila_en', this.industrialCity.upazila_en);
      form.append('upazila', this.industrialCity.upazila);
      form.append('details', this.industrialCity.details);
      form.append('details_en', this.industrialCity.details_en);
      form.append('latitude', this.industrialCity.latitude);
      form.append('longitude', this.industrialCity.longitude);
      form.append('order', this.industrialCity.order);
      form.append('status', this.industrialCity.status);
      form.append('type', this.industrialCity.type);
      form.append('h_office_id', this.industrialCity.h_office_id);
      form.append('id', app.id);
      form.append('establish_year', this.industrialCity.establish_year);
      form.append('land_amount', this.industrialCity.land_amount);
      form.append('per_acre_price', this.industrialCity.per_acre_price);
      form.append('used_plot', this.industrialCity.used_plot);
      form.append('ind_plot_no', this.industrialCity.ind_plot_no);
      form.append('total_plot_allocated', this.industrialCity.total_plot_allocated);
      form.append('ind_unit_total', this.industrialCity.ind_unit_total);
      form.append('ind_unit_under_prod', this.industrialCity.ind_unit_under_prod);
      form.append('ind_unit_under_cons', this.industrialCity.ind_unit_under_cons);
      form.append('ind_unit_off', this.industrialCity.ind_unit_off);
      form.append('ind_unit_allocate_wait', this.industrialCity.ind_unit_allocate_wait);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/home-page/update-industrial-city', form)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toast.success('Your data update successfully.');
              app.$router.replace('/home-page/industrial-city');
            }
          }).catch((error) => {
        this.allerros = error.response.data.errors;
      });
    },
    createMasterPlan() {
      const app = this;
      const form = new FormData();
      form.append('mp_name_en', app.masterPlanForm.mp_name_en);
      form.append('mp_name_bn', app.masterPlanForm.mp_name_bn);
      form.append('mp_remarks_en', app.masterPlanForm.mp_remarks_en);
      form.append('mp_remarks_bn', app.masterPlanForm.mp_remarks_bn);
      form.append('mp_doc', app.masterPlanForm.mp_doc);
      form.append('city_id', app.id);

      axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
      axios.post('/settings/home-page/industrial-city/master-plan/create', form)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toast.success('New master plan created successfully.');
              $('#createMasterPlanModal').trigger('click');
              app.masterPlanForm.mp_name_en = '';
              app.masterPlanForm.mp_name_bn = '';
              app.masterPlanForm.mp_remarks_en = '';
              app.masterPlanForm.mp_remarks_bn = '';
              app.masterPlanForm.mp_doc = '';
              app.masterPlanFormErrors = {};
              app.getMasterPlanList();
            }
          }).catch((error) => {
          app.masterPlanFormErrors = error.response.data.errors;
      });
    },
    getdistrict(){
      axios.get('/settings/get-district')
              .then(respdonse => {
                this.districtsData = respdonse.data;
              });
    },
    getUpazila(){
      axios.get('/settings/get-upazila-name')
              .then(respdonse => {
                this.upazilasData = respdonse.data;
              });
    },
    getMasterPlanList(page) {
      if (typeof page === 'undefined') {
        page = 1;
      }
      const max_limit = '&limit=' + this.limits;
      let is_search = '';
      if (this.search) {
        is_search = '&search=' + this.search
      }
      var app =this;
      axios.get('/settings/home-page/industrial-city/master-plan-list/' + this.$route.params.id + '?page=' + page + is_search + max_limit)
          .then(response => {
              app.masterPlanList = response.data;
          })
    },

    keymonitor: function (e) {
      this.getMasterPlanList(1);
    },
    limit: function (e) {
      this.getMasterPlanList(1);
    }
  }
}
</script>
