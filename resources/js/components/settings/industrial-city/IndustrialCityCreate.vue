<template>
  <div>
    <div class="card">
      <div class="card-header bg-primary">
        <h5><strong>New Industrial City</strong></h5>
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

            <div v-if="show">
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
                </select>
                <span v-if="allerros.upazila_en" :class="['text-danger']">{{ allerros.upazila_en[0] }}</span>
              </div>
            </div>

            <div class="row form-group">
              <label class="col-md-3 required-star">Upazila (বাংলা):</label>
              <div :class="['col-md-9', allerros.upazila ? 'has-error' : '']">
                <select v-model="industrialCity.upazila"  class="form-control input-sm" id="upazilaBN">
                  <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                </select>
                <span v-if="allerros.upazila" :class="['text-danger']">{{ allerros.upazila[0] }}</span>
              </div>
            </div>

            <div v-if="show">
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
                <label class="col-md-3 required-star">Image:</label>
                <div :class="['col-md-9', allerros.files ? 'has-error' : '']">
                  <input type="file" id="files" ref="files" class="form-control" v-on:change="onFileChange"/>
                  <span class="help-block">[File Format: *.jpg/ .jpeg/.png/ .gif/ .svg | Max size 1 MB]</span>
                  <span v-if="allerros.image" :class="['text-danger']">{{ allerros.image[0] }}</span>
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
                <h4>Industrial unit Details (বরাদ্দকৃত প্লটে শিল্প ইউনিটের বিবরণ)</h4>
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
                <input type="number" v-model="industrialCity.order" class="form-control">
                <span v-if="allerros.order" :class="['text-danger']">{{ allerros.order[0] }}</span>
              </div>
            </div>

            <div class="row form-group ">
              <label class="col-md-3 required-star">Status: </label>
              <div class="col-md-9">
                <label><input v-model="industrialCity.status" name="is_active" type="radio"
                              :checked="industrialCity.status" value="1"> Active&nbsp;</label>
                <label><input v-model="industrialCity.status" name="is_active" type="radio"
                              :checked="industrialCity.status" value="0"> Inactive</label>
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
  </div>
</template>

<script>
  import Editor from "@tinymce/tinymce-vue";
export default {
  data: function () {
    return {
        show: true,
      allerros: [],
      success: false,
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
        type: '0',
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
      homeOffice: [],
      selected: null,
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
        $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
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
  },
  created () {
    this.getdistrict();
    let app = this;
    axios.get('/settings/home-page/get-homeOffice')
        .then(function (resp) {
          app.homeOffice = resp.data.homeOfficeData;
        })
        .catch(function () {
          alert("Could not load your News")
        });
  },
  methods: {
    onFileChange(event) {
      this.industrialCity.image = event.target.files[0]

    },
    saveForm() {
        const app = this;
        const form = new FormData();
      form.append('image', this.industrialCity.image);
      form.append('name', this.industrialCity.name);
      form.append('office_short_code', this.industrialCity.office_short_code);
      form.append('h_office_id', this.industrialCity.h_office_id);
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
      axios.post('/settings/home-page/store-industrial-city', form)
          .then(function (resp) {
            if (resp.data.status === true) {
              app.$toast.success('Your news crate successfully.');
              app.$router.push({path: '/home-page/industrial-city'});
            }
          }).catch((error) => {
        app.allerros = error.response.data.errors;
      });
    },
    getdistrict(){
      axios.get('/settings/get-district')
              .then(respdonse => {
                this.districtsData = respdonse.data;
              });
    },
  }
}
</script>
