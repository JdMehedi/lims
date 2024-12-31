<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
<style>
    .selecttionError{
        border:1px solid red !important;
        color:red !important;
    }
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
    }
    .p-r-0 {
        padding-right: 0;
    }

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
    }

    #company_type{
        pointer-events: none;
    }

    .m-l-auto {
        margin-left: auto;
    }
    /*.card-magenta:not(.card-outline)>.card-header {*/
    /*    display: inherit;*/
    /*}*/
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard>.content {
        overflow: visible;
    }

    .wizard>.actions {
        width: 70% !important;
    }
    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }
    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }
    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }
    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }
    .sub-header{
        background: #1C9D50 !important;
        padding: 2px 10px;
        color: #ffffff;
    }
    #total_fixed_ivst_million {
        pointer-events: none;
    }

    /*.wizard > .actions {*/
    /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

    /*form label {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*.table>thead:first-child>tr:first-child>th {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*td {*/
    /*    font-size: 14px;*/
    /*}*/

    .signature-upload-input {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
        background-color: blue;
    }

    .sign_div {
        position: relative;
    }

    .signature-upload {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        padding: 5px 10px;
        font-size: 1rem;
        text-align: center;
        color: #000;
        background-color: #F5FAFE;
        border-radius: 0 !important;
        border: 0;
        height: 160px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }

    .custom_error {
        outline: 1px dashed red;
    }

    .email {
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard>.actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard>.content>.body label {
            margin-top: .5em;
            margin-bottom: 0;
        }

        .tabInput {
            width: 120px;
        }

        .tabInput_sm {
            width: 75px;
        }

        .tabInputDate {
            width: 150px;
        }

        .table_responsive {
            overflow-x: auto;
        }

        /*.bootstrap-datetimepicker-widget {*/
        /*    position: relative !important;*/
        /*    top: 0 !important;*/
        /*}*/

        .prevMob {
            margin-top: 45px;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

    /*.wizard>.steps>ul>li {*/
    /*    width: 33.2% !important;*/
    /*}*/

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>

<div class="row"contact_signatory_photo_preview>
    <div class="col-md-12 col-lg-12" id="inputForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application for Internet Protocol Telephony Service Provider (IPTSP) License Amendment</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>

                {{--Basic Information--}}
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @include('common.subviews.licenseInfo', ['mode' => 'amendment', 'url' => 'iptsp-license-ammendment/fetchAppData'])
                    {{-- Company info --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'add'])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add'])

                    {{--contact person--}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'add'])

                    {{--Name of Authorized Signatory--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Name of Authorized Signatory
                            <span style="float: right; cursor: pointer;" class=" m-l-auto">
                               <i class="fa fa-info-circle text-dark" aria-hidden="true"></i>
                                {!! Form::checkbox('signatorySameAsContact', 'YES', false, ['id'=> 'signatorySameAsContact']) !!}
                                {!! Form::label('signatorySameAsContact', 'Same as Contact Person') !!}
                            </span>
                        </div>

                        <div class="card-wrapper"  style="padding: 15px 25px;">
                            <div class="card-body" style="padding: 0px 0px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_name', 'Name :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_person_name') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_signatory_person_name', '', ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'contact_signatory_name']) !!}
                                                {!! $errors->first('contact_signatory_person_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_designation', 'Designation :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_designation') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_signatory_designation', '', ['class' => 'form-control', 'placeholder' => 'Enter Designation', 'id' => 'contact_signatory_designation']) !!}
                                                {!! $errors->first('contact_signatory_designation', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_mobile', 'Mobile Number :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_mobile') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_signatory_mobile', '', ['class' => 'form-control mobile_number', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_signatory_mobile']) !!}
                                                {!! $errors->first('contact_signatory_mobile', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_email', 'Email :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_person_email') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_signatory_person_email', '', ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'contact_signatory_email']) !!}
                                                {!! $errors->first('contact_signatory_person_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_district_1', 'District :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_district') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_signatory_district', $districts, '', ['class' => 'form-control', 'id' => 'contact_signatory_district_1', 'onchange' => "getThanaByDistrictId('contact_signatory_district_1', this.value, 'contact_signatory_thana_1',0)"]) !!}
                                                {!! $errors->first('contact_signatory_district', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_thana_1', 'Upazila / Thana :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_thana') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_signatory_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'contact_signatory_thana_1']) !!}
                                                {!! $errors->first('contact_signatory_thana', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_signatory_person_address', 'Address :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_signatory_person_address') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_signatory_person_address', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'contact_signatory_person_address']) !!}
                                                {!! $errors->first('contact_signatory_person_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-bottom:0px!important;">
                                            {!! Form::label('contact_signatory_photo', 'Photograph :', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control input-sm"
                                                               name="contact_signatory_photo" id="contact_signatory_photo"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'contact_signatory_photo_preview', 'contact_signatory_photo_base64')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="contact_signatory_photo">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="contact_signatory_photo_preview" />
                                                            </figure>
                                                            <input type="hidden" id="contact_signatory_photo_base64"
                                                                   name="contact_signatory_photo_base64" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Details of Existing ISP License --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Details of Existing ISP License
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('isp_license_number', 'ISP License Number :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('isp_license_number') ? 'has-error' : '' }}">
                                            {!! Form::text('isp_license_number',$existing_isp_license->license_no ?? '',['class'=>'form-control','readonly'=>'readonly', 'id' => 'isp_license_number'] ) !!}
                                            {!! $errors->first('isp_license_number', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('isp_license_date_of_expire', 'ISP License Date of Expire :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-1"></div>
                                        <div class="col-md-7 {{ $errors->has('isp_license_date_of_expire') ? 'has-error' : '' }}">
                                            {!! Form::text('isp_license_date_of_expire',$existing_isp_license->expiry_date ?? '',['class'=>'form-control','readonly'=>true, 'id'=>'isp_license_date_of_expire'] ) !!}
                                            {!! $errors->first('isp_license_date_of_expire', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6"  >
                                    <div class="form-group row">
                                        {!! Form::label('types_of_isp_license', 'Types of ISP License :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('types_of_isp_license') ? 'has-error' : '' }}">
                                            {!! Form::select('types_of_isp_license',[
                                            ''=>'Select',
                                            '1'=>'Nationwide',
                                            '2'=>'Divisional',
                                            '3'=>'District',
                                            '4'=>'Thana/Upazila',],
                                            $existing_isp_license->isp_license_type ?? null,
                                            ['class'=>'form-control input_disabled','readonly'=>'readonly', 'id'=>'types_of_isp_license'] ) !!}
                                            {!! $errors->first('types_of_isp_license', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('isp_license_issue_date', 'ISP License Issue Date :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('isp_license_issue_date') ? 'has-error' : '' }}">
                                            {!! Form::text('isp_license_issue_date',$existing_isp_license->license_issue_date ?? '',['class'=>'form-control','readonly'=>true, 'id'=>'isp_license_issue_date'] ) !!}
                                            {!! $errors->first('isp_license_issue_date', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12"  >
                                    <div class="form-group row">
                                        {!! Form::label('multi_license', 'Other License Awarded by the commision to the License:', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-4 {{ $errors->has('multi_license') ? 'has-error' : '' }}">
                                            {!! Form::select('multi_license[]', $multiLicense, "", ['class'=>'form-control multiple_select2','style'=>'width:100%', 'data-rule-maxlength'=>'40', 'id'=>"multi_license","multiple"=>"multiple"]) !!}
                                            {!! $errors->first('multi_license', '<span class="help-block">:message</span>') !!}
                                        </div>
            
                                        <div class="col-md-4 {{ $errors->has('prev_license_copy') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control input-sm"
                                                               name="prev_license_copy" id="prev_license_copy"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>

                                                </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

                    {{-- Investment Information --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Investment Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('local_investment', 'Local Investment :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('local_investment') ? 'has-error' : '' }}">
                                            {!! Form::text('local_investment','',['class'=>'form-control investmentInfo','placeholder'=>'Enter Local Investment', 'id' => 'local_investment','onkeyup'=>'investmentInfo()'] ) !!}
                                            {!! $errors->first('local_investment', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('total_investment', 'Total Investment :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('total_investment') ? 'has-error' : '' }}">
                                            {!! Form::text('total_investment','',['class'=>'form-control','placeholder'=>'000','readonly'=>'readonly', 'id'=>'total_investment'] ) !!}
                                            {!! $errors->first('total_investment', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row pt-3">
                                        {!! Form::label('gross_revenue_eamed_in_last_year', 'Gross Revenue Eamed in Last Year :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('gross_revenue_eamed_in_last_year') ? 'has-error' : '' }}">
                                            {!! Form::text('gross_revenue_eamed_in_last_year','',['class'=>'form-control','placeholder'=>'Enter Foreign Investment', 'id'=>'gross_revenue_eamed_in_last_year'] ) !!}
                                            {!! $errors->first('gross_revenue_eamed_in_last_year', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6"  >
                                    <div class="form-group row">
                                        {!! Form::label('foreign_investment', 'Foreign Investment :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('foreign_investment') ? 'has-error' : '' }}">
                                            {!! Form::text('foreign_investment','',['class'=>'form-control investmentInfo','placeholder'=>'Enter Foreign Investment', 'id'=>'foreign_investment','onkeyup'=>'investmentInfo()'] ) !!}
                                            {!! $errors->first('foreign_investment', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('present_value_of_total_investment', 'Present Value of Total Investment :', ['class' => 'col-md-5']) !!}
                                        <div class="col-md-7 {{ $errors->has('present_value_of_total_investment') ? 'has-error' : '' }}">
                                            {!! Form::text('present_value_of_total_investment','',['class'=>'form-control','placeholder'=>'Enter Present Value of Total Investment', 'id'=>'present_value_of_total_investment'] ) !!}
                                            {!! $errors->first('present_value_of_total_investment', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-8 {{ $errors->has('gross_revenue_eamed_in_last_year_img') ? 'has-error' : '' }}">
                                            <input type="file"
                                                   style="border: none; margin-bottom: 5px;"
                                                   class="input-sm"
                                                   name="gross_revenue_eamed_in_last_year_img" id="gross_revenue_eamed_in_last_year_img"
                                                   accept="image/*,application/pdf"
                                                   size="300x300" />
                                        </div>
                                    </div>
                                    <div id="display_gross_file">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Employee Information --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Employee Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('employee_information', 'Employee Information :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('employee_information') ? 'has-error' : '' }}">
                                            {!! Form::text('employee_information','',['class'=>'form-control', 'id' => 'employee_information'] ) !!}
                                            {!! $errors->first('employee_information', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6"  >
                                    <div class="form-group row">
                                        {!! Form::label('total_it_specialist', 'Total IT Specialist :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('total_it_specialist') ? 'has-error' : '' }}">
                                            {!! Form::text('total_it_specialist','',['class'=>'form-control', 'id'=>'total_it_specialist'] ) !!}
                                            {!! $errors->first('total_it_specialist', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--IPTSP Information--}}
                <h3>IPTSP Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Type of IPTSP License Applied for --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Type of IPTSP License Applied for
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('type_of_iptsp_licensese', 'Types of IPTSP Licensese :', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('type_of_iptsp_licensese') ? 'has-error' : '' }}">
                                            {!! Form::select('type_of_iptsp_licensese', [''=>'Select',1=>'Nationwide',2=>'Divisional'], '', ['class' => 'form-control', 'id' => 'type_of_iptsp_licensese']) !!}
                                            {!! $errors->first('type_of_iptsp_licensese', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" id="division" style="display: none;">
                                    <div class="form-group row">
                                        {!! Form::label('iptsp_licensese_area_division', 'Divisional :', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                            {!! Form::select('iptsp_licensese_area_division',$division, '', ['class' => 'form-control', 'id' => 'iptsp_licensese_area_division','onchange' => "getIPTSPiDistrictByDivisionId('iptsp_licensese_area_division', this.value, ['coverage_district','coverage_out_of_district'],[],[]);"]) !!}
                                            {!! $errors->first('iptsp_licensese_area_division', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Coverage Area Information--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Coverage Area Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('coverage_area', 'Coverage Area :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('coverage_area') ? 'has-error' : '' }}">
                                            {!! Form::select('coverage_area',[''=>'Select',1=>'Nationwide',2=>'Divisional'],null,['class'=>'form-control input_disabled', 'readonly'=>'readonly', 'id'=>'coverage_area'] ) !!}
                                            {!! $errors->first('coverage_area', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('total_coverage_area', 'Total Coverage Area :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('total_coverage_area') ? 'has-error' : '' }}">
                                            {!! Form::text('total_coverage_area','', ['class'=>'form-control','placeholder'=>'In Square Kilometer', 'id'=>'total_coverage_area'] ) !!}
                                            {!! $errors->first('total_coverage_area', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('coverage_district', 'District :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('coverage_district') ? 'has-error' : '' }}">
                                            {!! Form::select('coverage_district[]', $multiDistricts, "", ['class'=>'form-control multiple_select2','style'=>'width:100%', 'data-rule-maxlength'=>'40', 'id'=>"coverage_district","multiple"=>"multiple"]) !!}
                                            {!! $errors->first('coverage_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{--Coverage Out of Area In Rural Area Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Coverage Out of Area In Rural Area Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('coverage_out_of_area', 'Coverage Area :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('coverage_out_of_area') ? 'has-error' : '' }}">
                                            {!! Form::select('coverage_out_of_area',[''=>'Select',1=>'Nationwide',2=>'Divisional'],'',['class'=>'form-control input_disabled','readonly'=>'readonly', 'id'=>'coverage_out_of_area'] ) !!}
                                            {!! $errors->first('coverage_out_of_area', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('total_coverage_out_of_area', 'Total Coverage Area :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('total_coverage_out_of_area') ? 'has-error' : '' }}">
                                            {!! Form::text('total_coverage_out_of_area','', ['class'=>'form-control','placeholder'=>'In Square Kilometer',  'id'=>'total_coverage_out_of_area'] ) !!}
                                            {!! $errors->first('total_coverage_out_of_area', '<span class="help-block">:message</span>') !!}
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('coverage_out_of_district', 'District :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 ">
                                            {!! Form::select('coverage_out_of_district[]', $multiDistricts, null, ['class'=>'form-control multiple_select2', 'style'=>'width:100%', 'id'=>"coverage_out_of_district","multiple"=>"multiple"]) !!}
                                            {!! $errors->first('coverage_out_of_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Date of Commencement of the Service  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Date of Commencement of the Service
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('commencement_date', 'Date :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('commencement_date') ? 'has-error' : '' }}">
                                            <div class="input-group date datetimepickerCommencement"
                                                 id="datepicker2" data-target-input="nearest">
                                                {{--calendarIcon class name must be in secound position in class list--}}
                                                {!! Form::text('commencement_date', '', ['class' => 'form-control calendarIcon', 'id' => 'commencement_date']) !!}
                                                <div class="input-group-append"
                                                     data-target="#datepicker2"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i
                                                            class="fa fa-calendar"></i></div>
                                                </div>
                                                {!! $errors->first('commencement_date', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >

                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Information of Existing Subscriber Level  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Information of Existing Subscriber Level
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('existing_subscriber_dial_up', 'Dial-up :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('existing_subscriber_dial_up') ? 'has-error' : '' }}">
                                            {!! Form::text('existing_subscriber_dial_up','',['class'=>'form-control', 'id'=>'existing_subscriber_dial_up'] ) !!}
                                            {!! $errors->first('existing_subscriber_dial_up', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('existing_subscriber_corporate', 'Corporate :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('existing_subscriber_corporate') ? 'has-error' : '' }}">
                                            {!! Form::text('existing_subscriber_corporate', '', ['class'=>'form-control', 'id'=>"existing_subscriber_corporate"]) !!}
                                            {!! $errors->first('existing_subscriber_corporate', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('existing_subscriber_individual', 'Individual :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('existing_subscriber_individual') ? 'has-error' : '' }}">
                                            {!! Form::text('existing_subscriber_individual', '', ['class'=>'form-control', 'id'=>"existing_subscriber_individual"]) !!}
                                            {!! $errors->first('existing_subscriber_individual', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('existing_subscriber_broadband', 'Broadband :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('existing_subscriber_broadband') ? 'has-error' : '' }}">
                                            {!! Form::text('existing_subscriber_broadband','', ['class'=>'form-control', 'id'=>'existing_subscriber_broadband'] ) !!}
                                            {!! $errors->first('existing_subscriber_broadband', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('existing_subscriber_name_with_corporate_clients_subscriber_number', 'Name of Corporate Clients with Subscriber Number :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('existing_subscriber_name_with_corporate_clients_subscriber_number') ? 'has-error' : '' }}">
                                            {!! Form::text('existing_subscriber_name_with_corporate_clients_subscriber_number','', ['class'=>'form-control', 'id'=>'existing_subscriber_name_with_corporate_clients_subscriber_number'] ) !!}
                                            {!! $errors->first('existing_subscriber_name_with_corporate_clients_subscriber_number', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Wired Network Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Wired Network Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_length_of_laid_cable', 'Length of Laid Cable :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_length_of_laid_cable') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_length_of_laid_cable','',['class'=>'form-control', 'id'=>'wired_network_length_of_laid_cable'] ) !!}
                                            {!! $errors->first('wired_network_length_of_laid_cable', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_optical_fiber', 'Optical Fiber :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_optical_fiber') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_optical_fiber', '', ['class'=>'form-control', 'id'=>"wired_network_optical_fiber"]) !!}
                                            {!! $errors->first('wired_network_optical_fiber', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_dsl', 'DSL :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_dsl') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_dsl', '', ['class'=>'form-control', 'id'=>"wired_network_dsl"]) !!}
                                            {!! $errors->first('wired_network_dsl', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_adsl', 'ADSL :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_adsl') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_adsl', '', ['class'=>'form-control', 'id'=>"wired_network_adsl"]) !!}
                                            {!! $errors->first('wired_network_adsl', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_utp', 'UTP :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_utp') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_utp','', ['class'=>'form-control', 'id'=>'wired_network_utp'] ) !!}
                                            {!! $errors->first('wired_network_utp', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_stp', 'STP :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_stp') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_stp','', ['class'=>'form-control', 'id'=>'wired_network_stp'] ) !!}
                                            {!! $errors->first('wired_network_stp', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('wired_network_other', 'Other :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wired_network_other') ? 'has-error' : '' }}">
                                            {!! Form::text('wired_network_other','', ['class'=>'form-control', 'id'=>'wired_network_other'] ) !!}
                                            {!! $errors->first('wired_network_other', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Bandwidth Details for Last Years Level  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Bandwidth Details for Last Years Level
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('bandwidth_lastyear_total_allocation', 'Total Allocation :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_total_allocation') ? 'has-error' : '' }}">
                                            {!! Form::text('bandwidth_lastyear_total_allocation','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_total_allocation'] ) !!}
                                            {!! $errors->first('bandwidth_lastyear_total_allocation', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('bandwidth_lastyear_total_utilization', 'Total utilization :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_total_utilization') ? 'has-error' : '' }}">
                                            {!! Form::text('bandwidth_lastyear_total_utilization','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_total_utilization'] ) !!}
                                            {!! $errors->first('bandwidth_lastyear_total_utilization', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('bandwidth_lastyear_iig', 'IIG :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_iig') ? 'has-error' : '' }}">
                                            {!! Form::text('bandwidth_lastyear_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_iig"]) !!}
                                            {!! $errors->first('bandwidth_lastyear_iig', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('bandwidth_lastyear_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_iplc') ? 'has-error' : '' }}">
                                            {!! Form::text('bandwidth_lastyear_iplc','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_iplc'] ) !!}
                                            {!! $errors->first('bandwidth_lastyear_iplc', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('bandwidth_lastyear_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_vsat') ? 'has-error' : '' }}">
                                            {!! Form::text('bandwidth_lastyear_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_vsat"]) !!}
                                            {!! $errors->first('bandwidth_lastyear_vsat', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                            {{-- Uplink Information--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Uplink Information</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_uplink_iig', 'IIG :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_uplink_iig') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_uplink_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_uplink_iig"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_uplink_iig', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_uplink_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_uplink_iplc') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_uplink_iplc','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_uplink_iplc'] ) !!}
                                                {!! $errors->first('bandwidth_lastyear_uplink_iplc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_uplink_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_uplink_vsat') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_uplink_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_uplink_vsat"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_uplink_vsat', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                            </fieldset>
                            <br>
                            {{-- Medium for Uplink Allocation--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Medium for Uplink Allocation</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_uplink_iig', 'IIG :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_uplink_iig') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_uplink_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_medium_uplink_iig"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_uplink_iig', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_uplink_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_uplink_iplc') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_uplink_iplc','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_medium_uplink_iplc'] ) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_uplink_iplc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_uplink_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_uplink_vsat') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_uplink_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_medium_uplink_vsat"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_uplink_vsat', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                            </fieldset>

                            {{-- Downlink Allocation Information --}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Downlink Allocation Information</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_downlink_iig', 'IIG:', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_downlink_iig') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_downlink_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_downlink_iig"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_downlink_iig', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_downlink_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_downlink_iplc') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_downlink_iplc','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_downlink_iplc'] ) !!}
                                                {!! $errors->first('bandwidth_lastyear_downlink_iplc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_downlink_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_downlink_vsat') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_downlink_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_downlink_vsat"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_downlink_vsat', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                            </fieldset>
                            <br>
                            {{-- Medium Downlink Allocation Information--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Medium Downlink Allocation Information</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_downlink_iig', 'IIG :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_downlink_iig') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_downlink_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_medium_downlink_iig"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_downlink_iig', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_downlink_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_downlink_iplc') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_downlink_iplc','',['class'=>'form-control', 'id'=>'bandwidth_lastyear_medium_downlink_iplc'] ) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_downlink_iplc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_medium_downlink_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_medium_downlink_vsat') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_medium_downlink_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_medium_downlink_vsat"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_medium_downlink_vsat', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                            </fieldset>
                            {{-- Provider Information--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Provider Information</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_provider_name', 'Provider Name :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_provider_name') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_provider_name', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_provider_name"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_provider_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_provider_iig', 'IIG :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_provider_iig') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_provider_iig', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_provider_iig"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_provider_iig', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_provider_iplc', 'IPLC :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_provider_iplc') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_provider_iplc', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_provider_iplc"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_provider_iplc', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bandwidth_lastyear_provider_vsat', 'VSAT :', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('bandwidth_lastyear_provider_vsat') ? 'has-error' : '' }}">
                                                {!! Form::text('bandwidth_lastyear_provider_vsat', '', ['class'=>'form-control', 'id'=>"bandwidth_lastyear_provider_vsat"]) !!}
                                                {!! $errors->first('bandwidth_lastyear_provider_vsat', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    {{-- Connected by the ISP Information --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Connected by the ISP Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="IPTSPlistOfISPinformation_tbl">
                                <thead>
                                <tr>
                                    <th width="7%" class="text-center">SL</th>
                                    <th width="45%">Institution Type</th>
                                    <th width="40%">Institution Name</th>
                                    <th width="8%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="IPTSPlistOfISPinformation_1">
                                    <td>1</td>
                                    <td>
                                        {!! Form::select("institution_type[1]",[
                                            "1"=>"School",
                                            "2"=>"College",
                                            "3"=>"University",
                                            ""=>"Select"
                                            ], null, ["class"=>"form-control institution_type", "id"=>"institution_type_1"]) !!}
                                    </td>
                                    <td><input type="text" class="form-control institution_name" id="institution_name_1" placeholder="Institution Name" name="institution_name[1]"></td>
                                    <td class="d-flex justify-content-center">
                                        <button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Average Minimam Growth Rate of Subscribers for per year Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Average Minimam Growth Rate of Subscribers for per year Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('subscriber_individual', 'Individual :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('subscriber_individual') ? 'has-error' : '' }}">
                                            {!! Form::text('subscriber_individual','',['class'=>'form-control', 'id'=>'subscriber_individual'] ) !!}
                                            {!! $errors->first('subscriber_individual', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('subscriber_corporate', 'Corporate :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('subscriber_corporate') ? 'has-error' : '' }}">
                                            {!! Form::text('subscriber_corporate','', ['class'=>'form-control', 'id'=>'subscriber_corporate'] ) !!}
                                            {!! $errors->first('subscriber_corporate', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- POP Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            POP Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('no_of_POP', 'No. of POP :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('no_of_POP') ? 'has-error' : '' }}">
                                            {!! Form::text('no_of_POP','',['class'=>'form-control', 'id'=>'no_of_POP'] ) !!}
                                            {!! $errors->first('no_of_POP', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('location', 'Location :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('location') ? 'has-error' : '' }}">
                                            {!! Form::text('location','', ['class'=>'form-control', 'id'=>'location'] ) !!}
                                            {!! $errors->first('location', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Wireless Network Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Wireless Network Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('wireless_number_of_bis_pop', 'Number of BIS/POP :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wireless_number_of_bis_pop') ? 'has-error' : '' }}">
                                            {!! Form::text('wireless_number_of_bis_pop','',['class'=>'form-control','placeholder'=>'Number of BIS/POP', 'id'=>'wireless_number_of_bis_pop'] ) !!}
                                            {!! $errors->first('wireless_number_of_bis_pop', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('wireless_frequency', 'Frequency :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('wireless_frequency') ? 'has-error' : '' }}">
                                            {!! Form::text('wireless_frequency','', ['class'=>'form-control', 'placeholder'=>'Frequency', 'id'=>'wireless_frequency'] ) !!}
                                            {!! $errors->first('wireless_frequency', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Backup Information  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Backup Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_number_of_vsat', 'Number of VSAT (If Applicable) :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('backup_info_of_number_of_vsat') ? 'has-error' : '' }}">
                                            {!! Form::text('backup_info_of_number_of_vsat','',['class'=>'form-control', 'id'=>'backup_info_of_number_of_vsat'] ) !!}
                                            {!! $errors->first('backup_info_of_number_of_vsat', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_uplink_allocation', 'Uplink Allocation :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('backup_info_of_uplink_allocation') ? 'has-error' : '' }}">
                                            {!! Form::text('backup_info_of_uplink_allocation','', ['class'=>'form-control', 'id'=>'backup_info_of_uplink_allocation'] ) !!}
                                            {!! $errors->first('backup_info_of_uplink_allocation', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_downlink_allocation', 'Downlink Allocation :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('backup_info_of_downlink_allocation') ? 'has-error' : '' }}">
                                            {!! Form::text('backup_info_of_downlink_allocation','',['class'=>'form-control', 'id'=>'backup_info_of_downlink_allocation'] ) !!}
                                            {!! $errors->first('backup_info_of_downlink_allocation', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_uplink_frequency', 'Uplink Frequency :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('backup_info_of_uplink_frequency') ? 'has-error' : '' }}">
                                            {!! Form::text('backup_info_of_uplink_frequency','', ['class'=>'form-control', 'id'=>'backup_info_of_uplink_frequency'] ) !!}
                                            {!! $errors->first('backup_info_of_uplink_frequency', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_downlink_frequency', 'Downlink Frequency :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('backup_info_of_downlink_frequency') ? 'has-error' : '' }}">
                                            {!! Form::text('backup_info_of_downlink_frequency','',['class'=>'form-control', 'id'=>'backup_info_of_downlink_frequency'] ) !!}
                                            {!! $errors->first('backup_info_of_downlink_frequency', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        {!! Form::label('backup_info_of_description', 'Description :', ['class' => 'col-md-2']) !!}
                                        <div class="col-md-10 {{ $errors->has('backup_info_of_description') ? 'has-error' : '' }}">
                                            {!! Form::textArea('backup_info_of_description','',['class'=>'form-control','placeholder'=>'Description', 'rows'=> 5, 'id'=>'backup_info_of_description'] ) !!}
                                            {!! $errors->first('backup_info_of_description', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Per Subscriber Average width  --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Per Subscriber Average width
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('per_subscriber_individual', 'Individual :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('per_subscriber_individual') ? 'has-error' : '' }}">
                                            {!! Form::text('per_subscriber_individual','',['class'=>'form-control','placeholder'=>'Individual ', 'id'=>'per_subscriber_individual'] ) !!}
                                            {!! $errors->first('per_subscriber_individual', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" >
                                    <div class="form-group row">
                                        {!! Form::label('per_subscriber_corporate', 'Corporate :', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('per_subscriber_corporate') ? 'has-error' : '' }}">
                                            {!! Form::text('per_subscriber_corporate','', ['class'=>'form-control', 'placeholder'=>'Corporate', 'id'=>'per_subscriber_corporate'] ) !!}
                                            {!! $errors->first('per_subscriber_corporate', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Attachment Declration--}}
                <h3>Attachment & Declration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{--Required Documents--}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Required Documents for attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>
                    {{--Declaration--}}
                    <div class="card card-magenta border border-magenta mt-4">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any Application for License of the Applicant any Share Holder/Partner ISP been rejected before?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div id="if_declaration_q1_yes" style="display: none">
                                                <div class="form-group row" style="margin-top:10px;">
                                                    {!! Form::label('declaration_q1_date', 'Date of Application :', ['class' => 'col-md-2', 'style' => 'font-weight:400' ]) !!}
                                                    <div class="col-md-4 {{ $errors->has('declaration_q1_date') ? 'has-error' : '' }}">
                                                        <div class="input-group date  declaration_q1_datetimepicker"
                                                             id="declaration_q1_datepicker"
                                                             data-target-input="nearest">
                                                            {!! Form::text('declaration_q1_date', '', ['class' => 'form-control calendarIcon', 'id' => 'declaration_q1_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#declaration_q1_datepicker"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('declaration_q1_date', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">
                                                    {{ Form::textarea('declaration_q1_text',null, array('class' =>'form-control input','id' => 'declaration_q1_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                                </div>
                                            </div>

                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant /Share Holder Partner hold any other Operator Licenses from the Commission?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;" >
                                                <div id="if_declaration_q2_yes" style="display: none;" >
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q2_service_list', 'Service List :', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q2_service_list') ? 'has-error' : '' }}">
                                                                    {!! Form::select('declaration_q2_service_list',[''=>'Select','1'=>'ISP','2'=>'NIX'], null, ['class' => 'form-control','id'=>'declaration_q2_service_list', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q2_service_list', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q2_license_number', 'License Number :', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q2_license_number') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q2_license_number', '', ['class' => 'form-control','id'=>'declaration_q2_license_number', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q2_license_number', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q2_company_name', 'Company Name :', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q2_company_name') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q2_company_name', '', ['class' => 'form-control','id'=>'declaration_q2_company_name', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q2_company_name', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q2_share_holder_name', 'Share Holder Name :', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q2_share_holder_name') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q2_share_holder_name', '', ['class' => 'form-control','id'=>'declaration_q2_share_holder_name', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q2_share_holder_name', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any other License of the Applicant/any Share Holder/Partner been rejected before?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div id="if_declaration_q3_yes" style="display: none">
                                                <div class="form-group row" style="margin-top:10px;">
                                                    {!! Form::label('declaration_q3_date', 'Date of Application :', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                                    <div class="col-md-4 {{ $errors->has('declaration_q3_date') ? 'has-error' : '' }}">
                                                        {{--                                                        datetimepicker4--}}
                                                        <div class="input-group date  declaration_q3_datetimepicker"
                                                             id="declaration_q3_datepicker"
                                                             data-target-input="nearest">
                                                            {!! Form::text('declaration_q3_date', '', ['class' => 'form-control calendarIcon', 'id' => 'declaration_q3_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#declaration_q3_datepicker"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('declaration_q3_date', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">
                                                    {{ Form::textarea('declaration_q3_text',null, array('class' =>'form-control input','id' => 'declaration_q3_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant/ its owner(s)/ any of its director(s)/ partner(s) were involved in any illegal call termination using VoIP technology?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q4">
                                                {{ Form::radio('declaration_q4', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}
                                                {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q4', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}
                                                {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                <div id="if_declaration_q4_yes" style="display: none;">
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q4_illegal_VoIP_activities', 'Period of Involvement in illegal VoIP activities: ', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q4_illegal_VoIP_activities') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q4_illegal_VoIP_activities', '', ['class' => 'form-control','id'=>'declaration_q4_illegal_VoIP_activities', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q4_illegal_VoIP_activities', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q4_case_no', 'Case No :', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q4_case_no') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q4_case_no', '', ['class' => 'form-control','id'=>'declaration_q4_case_no', 'placeholder' => '']) !!}
                                                                    {!! $errors->first('declaration_q4_case_no', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset class="scheduler-border declaration_q4_last_part">
                                                        <legend class="scheduler-border">Administrative fine paid to the Commission</legend>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row form-group">
                                                                    {!! Form::label('declaration_q4_amount', 'Amount :', ['class' => 'col-md-4']) !!}
                                                                    <div class="col-md-8{{ $errors->has('declaration_q4_amount') ? 'has-error' : '' }}">
                                                                        {!! Form::text('declaration_q4_amount', '', ['class' => 'form-control','id'=>'declaration_q4_amount', 'placeholder' => '']) !!}
                                                                        {!! $errors->first('declaration_q4_amount', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row form-group">
                                                                    {!! Form::label('declaration_q4_cheque_or_bank_draft_no', 'Cheque No./ Bank Draft No :', ['class' => 'col-md-4']) !!}
                                                                    <div class="col-md-8{{ $errors->has('declaration_q4_cheque_or_bank_draft_no') ? 'has-error' : '' }}">
                                                                        {!! Form::text('declaration_q4_cheque_or_bank_draft_no', '', ['class' => 'form-control','id'=>'declaration_q4_cheque_or_bank_draft_no', 'placeholder' => '']) !!}
                                                                        {!! $errors->first('declaration_q4_cheque_or_bank_draft_no', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="row form-group" >
                                                                {!! Form::label('', 'Undertaking given to the Commission:', ['class' => 'col-md-8','id'=>'given_commision_text']) !!}
                                                                <div class="col-md-4" id="declaration_q4_given_commision">
                                                                    {{ Form::radio('declaration_q4_given_commision', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_given_commision_yes']) }}
                                                                    {{ Form::label('declaration_q4_given_commision_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                                    {{ Form::radio('declaration_q4_given_commision', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_given_commision_no']) }}
                                                                    {{ Form::label('declaration_q4_given_commision_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>

                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Note--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Note:
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    <li>Application without the submission of complete documents and information will not be accepted.</li>
                                    <li>Payment should be made by a Pay order / Demand Draft in favor of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                                    <li>Application fee is not refundable.</li>
                                    <li>Application will not be accepted if information's do not fulfill the relevant terms and conditions of the Commission issued at various time.</li>
                                    <li>Any ISP' found involved with illegal VoIP business at any time shall not be eligible for any type of IPTSP License.</li>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Payment and Submit--}}
                <h3>Submit</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">

                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/iptsp-license-issue/list/'. Encryption::encodeId(1)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                    </button>
                </div>

                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn"
                        id="save_as_draft">Save as Draft
                </button>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">

        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>
    let selectCountry = '';

    $(document).ready(function() {

        $(document).on('change','#type_of_iptsp_licensese' ,function (){
            let type_of_iptsp_licensese = $(this).val();
            $("#coverage_area").val(type_of_iptsp_licensese);
            $("#coverage_out_of_area").val(type_of_iptsp_licensese);
        });

        //this is for mobile plugin input field width change.
        $("form").find('.iti').css({
            "width": "-moz-available",
            "width": "-webkit-fill-available"
        });
        $(document).on('keydown', '#local_wc_ivst_ccy', function(e) {
            if (e.which == 9) {
                e.preventDefault();
                $('#usd_exchange_rate').focus();
            }
        })
        $(document).on('keydown', '#usd_exchange_rate', function(e) {
            if (e.which == 9) {
                e.preventDefault();
                $('#ceo_taka_invest').focus();
            }
        })
        $(document).on('change', '.companyInfoChange', function(e) {
            $('#same_address').trigger('change');
        })
        $(document).on('blur', '.companyInfoInput', function(e) {
            $('#same_address').trigger('change');
        })
        $(document).on('change', '#permanentSameAsRegisterdAddress', function(e) {
            if(this.checked === true){
                let office_district = $("#office_district").val();
                let office_upazilla_thana = $("#office_upazilla_thana").val();
                $("#par_office_district").val(office_district);

                getThanaByDistrictId('par_office_district', office_district, 'par_office_upazilla_thana',office_upazilla_thana.trim());

                $("#par_office_address").val($("#office_address").val());
            }
            if(this.checked === false) {
                $("#par_office_upazilla_thana").val('');
                $("#par_office_address").val('');
                $("#par_office_district").val('');
            }
        })

        $(document).on('change', '#signatorySameAsContact', function(e) {
            if(this.checked === true){
                let contact_person_name = $("#contact_person_name_1").val();
                let contact_designation = $("#contact_designation_1").val();
                let contact_mobile = $("#contact_mobile_1").val();
                let contact_email = $("#contact_person_email_1").val();
                let contact_district = $("#contact_district_1").val();
                let contact_thana = $("#contact_thana_1").val() || '0';
                let contact_person_address = $("#contact_person_address_1").val();
                let contact_photo_base64 = $("#correspondent_contact_photo_base64_1").val()

                getThanaByDistrictId('contact_signatory_district_1', contact_district, 'contact_signatory_thana_1',contact_thana.trim());

                $("#contact_signatory_name").val(contact_person_name);
                $("#contact_signatory_designation").val(contact_designation);
                $("#contact_signatory_mobile").val(contact_mobile);
                $("#contact_signatory_email").val(contact_email);
                $("#contact_signatory_person_address").val(contact_person_address);
                $("#contact_signatory_district_1").val(contact_district);
                $("#contact_signatory_thana_1").val(contact_thana);
                $("#contact_signatory_photo_preview").attr('src',contact_photo_base64);
                $("#contact_signatory_photo_base64").val(contact_photo_base64);
                $("#contact_signatory_photo").addClass("signatoryImg");
                $("#contact_signatory_photo").removeClass("required");
                $("#contact_signatory_photo").removeClass("error");


            }
            if(this.checked === false) {
                $("#contact_signatory_name").val('');
                $("#contact_signatory_designation").val('');
                $("#contact_signatory_mobile").val('');
                $("#contact_signatory_email").val('');
                $("#contact_signatory_person_address").val('');
                $("#contact_signatory_district_1").val('');
                $("#contact_signatory_thana_1").val('');
                $("#contact_signatory_photo_preview").attr('src','');
                $("#contact_signatory_photo_base64").val('');
                $("#contact_signatory_photo").addClass("required");
                $("#contact_signatory_photo").addClass("error");
                $("#contact_signatory_photo").removeClass("signatoryImg");
            }
        })



        let check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function() {

            if (this.checked === false) {
                $('#company_factory_div').removeClass('hidden');
                this.checked = false;
                $("#company_factory_division_id").val("");
                $("#company_factory_district_id").val("");
                $("#company_factory_thana_id").val("");
                $("#company_factory_postCode").val("");
                $("#company_factory_address").val("");
                $("#company_factory_email").val("");
                $("#company_factory_mobile").val("");
            } else {
                this.checked = true;
                $('#company_factory_div').addClass('hidden');
                $("#company_factory_division_id").val("").removeClass('error');
                $("#company_factory_district_id").val("").removeClass('error');
                $("#company_factory_thana_id").val("").removeClass('error');
                $("#company_factory_postCode").val("").removeClass('error');
                $("#company_factory_address").val("").removeClass('error');
                $("#company_factory_email").val("").removeClass('error');
                $("#company_factory_mobile").val("").removeClass('error');
            }

        });



        // $("#same_address").trigger('change');

        $("#investment_type_id").change(function() {
            let investment_type_id = $('#investment_type_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            let self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-country-by-investment-type') }}",
                data: {
                    investment_type_id: investment_type_id
                },
                success: function(response) {
                    if (investment_type_id == 1) {
                        $('#investing_country_id').attr('multiple', 'multiple');
                        //Select2
                        $("#investing_country_id").select2();
                    } else {
                        if ($("#investing_country_id").data('select2')) {
                            $("#investing_country_id").select2('destroy');
                        }
                        $('#investing_country_id').removeAttr('multiple');
                    }

                    if (investment_type_id == 3) {
                        let option = "";
                    } else {
                        let option =
                            '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    }
                    selectCountry = "{{ $investing_country->country_id ?? '' }}";
                    if (response.responseCode == 1) {
                        $.each(response.data, function(id, value) {
                            let repId = (id.replace(' ', ''))
                            if ($.inArray(repId, selectCountry.split(',')) != -1) {
                                option += '<option value="' + repId +
                                    '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + epIrd + '">' + value +
                                    '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();
                    // multiple if type one
                    // multiple if type one
                    let country_ids =
                        "{{ isset($investing_country->country_id) ? $investing_country->country_id : '' }}";
                    @if (isset($companyInfo->invest_type) ? $companyInfo->invest_type == '1' : '')
                    $('#investing_country_id').val(country_ids.split(',')).change();
                    @else
                    $("#investing_country_id").val(country_ids).change();
                    @endif

                }
            });
        });

        $("#investment_type_id").trigger('change');
        $("#total_investment").trigger('change');
        $("#industrial_sector_id").change(function() {
            let industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            let self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function(response) {

                    let option =
                        '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function(id, value) {

                            option += '<option value="' + id + '">' + value +
                                '</option>';
                        });
                    }
                    $("#industrial_sub_sector_id").html(option);
                    @if (isset($companyInfo->ins_sub_sector_id))
                    $("#industrial_sub_sector_id").val(
                        "{{ $companyInfo->ins_sub_sector_id }}").change();
                    @endif
                    $(self).next().hide();
                }
            });
        });

        // $("#industrial_sector_id").trigger('change');

        // Sales (in 100%)
        $("#local_sales_per").on('keyup', function() {
            let local_sales_per = this.value;
            if (local_sales_per <= 100 && local_sales_per >= 0) {
                let cal = 100 - local_sales_per;
                $('#foreign_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        $("#foreign_sales_per").on('keyup', function() {
            let foreign_sales_per = this.value;
            if (foreign_sales_per <= 100 && foreign_sales_per >= 0) {
                let cal = 100 - foreign_sales_per;
                $('#local_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        //------- Manpower start -------//
        $('#manpower').find('input').keyup(function() {
            let local_male = $('#local_male').val() ? parseFloat($('#local_male').val()) : 0;
            let local_female = $('#local_female').val() ? parseFloat($('#local_female').val()) : 0;
            let local_total = parseInt(local_male + local_female);
            $('#local_total').val(local_total);


            let foreign_male = $('#foreign_male').val() ? parseFloat($('#foreign_male').val()) : 0;
            let foreign_female = $('#foreign_female').val() ? parseFloat($('#foreign_female').val()) :
                0;
            let foreign_total = parseInt(foreign_male + foreign_female);
            $('#foreign_total').val(foreign_total);

            let mp_total = parseInt(local_total + foreign_total);
            $('#mp_total').val(mp_total);

            let mp_ratio_local = parseFloat(local_total / mp_total);
            let mp_ratio_foreign = parseFloat(foreign_total / mp_total);

            //            mp_ratio_local = Number((mp_ratio_local).toFixed(3));
            //            mp_ratio_foreign = Number((mp_ratio_foreign).toFixed(3));

            //---------- code from bida old
            mp_ratio_local = ((local_total / mp_total) * 100).toFixed(2);
            mp_ratio_foreign = ((foreign_total / mp_total) * 100).toFixed(2);
            // if (foreign_total == 0) {
            //     mp_ratio_local = local_total;
            // } else {
            //     mp_ratio_local = Math.round(parseFloat(local_total / foreign_total) * 100) / 100;
            // }
            // mp_ratio_foreign = (foreign_total != 0) ? 1 : 0;
            // End of code from bida old -------------

            $('#mp_ratio_local').val(mp_ratio_local);
            $('#mp_ratio_foreign').val(mp_ratio_foreign);

        });
        LoadListOfDirectors();

        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }

                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex == 2) {
                    let returnFlag = false;
                    const multiple_selectelements = document.getElementsByClassName("multiple_select2");
                    for(const element of multiple_selectelements){
                        const parentElmentChild = element.parentElement.children;
                        if(parentElmentChild[0].classList.contains('required')){
                            const spanElement = parentElmentChild[1].children[0].children[0];
                            if(!parentElmentChild[0].value){
                                spanElement.classList.add("selecttionError");
                                returnFlag = true;
                            }else{
                                spanElement.classList.remove("selecttionError");
                            }
                        }
                    }
                    if(returnFlag) return false;
                }

                if (currentIndex > newIndex) {
                    return true;
                }

                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3) {
                    // let dec_validation_status = declarationSectionValidation();
                    // if(!dec_validation_status){
                    //     return dec_validation_status;
                    // }
                    let errorStatus = false;
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                if ($("#declaration_q4_yes").is(":checked") || $("#declaration_q4_no").is(":checked")) {
                                    errorStatus =  false;
                                }else{
                                    new swal({
                                        type: 'error',
                                        text: 'Please answer the Declaration section all question.',
                                    });
                                    errorStatus = true;
                                }
                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    }else{
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }
                    if($("#declaration_q4_yes").is(":checked")){
                        let checkeddval = $("input[name='declaration_q4_given_commision']:checked").val();
                        if(checkeddval == undefined || checkeddval == "" || checkeddval == null){
                            $("#declaration_q4_given_commision_yes").addClass('error');
                            $("#given_commision_text").addClass('text-danger');
                            errorStatus = true;

                        }else{
                            $("#given_commision_text").removeClass('text-danger');
                            $("#declaration_q4_given_commision_yes").removeClass('error');
                            errorStatus = false;
                        }
                    }

                    if(errorStatus) return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex > 0) {
                    form.find('#submitForm').css('display', 'none');
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex === 1) {
                    form.find('#submitForm').css('display', 'none');
                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'none');
                    // form.steps("next");
                }
                if (currentIndex === 3) {
                    form.find('#submitForm').css('display', 'inline');
                    // form.steps("next");
                }
                else {
                    form.find('#submitForm').css('display', 'none');
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('.previous').removeClass('prevMob');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        let popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")){
                $('#accaccept_termseptTerms').removeClass('error');
                $('#accept_terms').next('label').css('color', 'black');
                $('body').css({"display": "none"});
                $("form").submit();
            } else {
                $('#acceptTerms').addClass('error');
                return false;
            }
        });

        $('.finish').on('click', function (e) {
            openPreviewV2();
        });


        function attachmentLoad() {
            let reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            let company_type_id = parseInt($("#company_type_id").val()); //order 2
            let industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            let investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            let key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }
        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
        function SetErrorInShareOfInputField(){
            $( ".shareholder_share_of" ).each(function( index ) {
                $(this).addClass('error');
            });
        }

        let today = new Date();
        let yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: '01/01/' + (yyyy + 20),
            minDate: '01/01/1905'
        })

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{-- initail -input mobile plugin script start --}}
        $("#company_office_mobile").intlTelInput({
            hiddenInput: "company_office_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#company_factory_mobile").intlTelInput({
            hiddenInput: "company_factory_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        // $(".mobile_number").intlTelInput({
        //     // hiddenInput: "applicant_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#applicant_mobile").intlTelInput({
        //     hiddenInput: "applicant_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $(".contact_mobile").intlTelInput({
        //     // hiddenInput: "contact_mobile_1",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#contact_signatory_mobile").intlTelInput({
        //     hiddenInput: "contact_signatory_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#shareholder_mobile").intlTelInput({
        //     hiddenInput: "shareholder_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        $("#auth_person_mobile").intlTelInput({
            hiddenInput: "auth_person_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        {{-- initail -input mobile plugin script end --}}

        $('#business_category_id').on('change', function() {
            let businessCategoryId = $('#business_category_id').val();
            let oldBusinessCategoryId =
                '{{ isset($companyInfo->business_category_id) ? $companyInfo->business_category_id : '' }}';

            if (businessCategoryId != oldBusinessCategoryId) {
                $('#company_ceo_designation_id').val('');
            } else {
                $('#company_ceo_designation_id').val(
                    '{{ isset($companyInfo->designation) ? $companyInfo->designation : '' }}');
            }
        })

        $(function() {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
            $('.datetimepickerCommencement').datetimepicker({
                format: 'DD-MMM-YYYY',
                // maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

        //form & field operation
        $("#declaration_q1_yes").on('click', function() {
            $('#if_declaration_q1_yes').css('display','inline');
            // $('#declaration_q1_date_row').css('display','inline');
        });
        $("#declaration_q1_no").on('click', function() {
            $('#if_declaration_q1_yes').css('display','none');
            // $('#declaration_q1_date_row').css('display','none');
        });

        $("#declaration_q2_yes").on('click', function() {
            $('#if_declaration_q2_yes').css('display','inline');
        });
        $("#declaration_q2_no").on('click', function() {
            $('#if_declaration_q2_yes').css('display','none');
        });

        $("#declaration_q3_yes").on('click', function() {
            $('#if_declaration_q3_yes').css('display','inline');
        });
        $("#declaration_q3_no").on('click', function() {
            $('#if_declaration_q3_yes').css('display','none');
        });

        $("#declaration_q4_yes").on('click', function() {
            $('#if_declaration_q4_yes').css('display','inline');
        });
        $("#declaration_q4_no").on('click', function() {
            $('#if_declaration_q4_yes').css('display','none');
        });

//add shareholder row
        let rowId = 0;

        $('#shareholderRow').on('click', '.shareholderRow', function () {
            let prevDataCount = $("#shareholderDataCount").val();

            let child = $(this).closest('tr').nextAll();

            child.each(function () {
                let id = $(this).attr('id');
                let idx = $(this).children('.row-index').children('p');
                let dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#shareholderDataCount").val(prevDataCount - 1);
        });

        // nationality
        $(document).on('change','.shareholder_nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#passportBlock_'+lastRowId).hide();

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).show();
            }
        });


        {{--$(document).on('change','#type_of_iptsp_licensese' ,function (){--}}

        {{--    let type_of_iptsp_licensese = $(this).val();--}}

        {{--    $("#coverage_area").val(type_of_iptsp_licensese);--}}
        {{--    $("#coverage_out_of_area").val(type_of_iptsp_licensese);--}}

        {{--    let oss_fee = 0;--}}
        {{--    let vat = 0;--}}
        {{--    $.ajax({--}}
        {{--        type: "POST",--}}
        {{--        url: "{{ url('iptsp-license-issue/get-payment-data-by-license-type') }}",--}}
        {{--        data: {--}}
        {{--            process_type_id: {{ $process_type_id }},--}}
        {{--            payment_type: 1,--}}
        {{--            license_type: $(this).val()--}}
        {{--        },--}}
        {{--        success: function (response) {--}}
        {{--            if (response.responseCode == -1) {--}}
        {{--                alert(response.msg);--}}
        {{--                return false;--}}
        {{--            }--}}
        {{--            oss_fee = parseFloat(response.data.oss_fee);--}}
        {{--            vat = parseInt(response.data.vat);--}}
        {{--        },--}}
        {{--        complete: function () {--}}
        {{--            var unfixed_amounts = {--}}
        {{--                1: 0,--}}
        {{--                2: oss_fee,--}}
        {{--                3: 0,--}}
        {{--                4: 0,--}}
        {{--                5: vat,--}}
        {{--                6: 0,--}}
        {{--                7: 0,--}}
        {{--                8: 0,--}}
        {{--                9: 0,--}}
        {{--                10: 0--}}
        {{--            };--}}
        {{--            loadPaymentPanelV2('', '{{ $process_type_id }}', '1',--}}
        {{--                'payment_panel',--}}
        {{--                "{{ CommonFunction::getUserFullName() }}",--}}
        {{--                "{{ Auth::user()->user_email }}",--}}
        {{--                "{{ Auth::user()->user_mobile }}",--}}
        {{--                "{{ Auth::user()->contact_address }}",--}}
        {{--                unfixed_amounts);--}}
        {{--        }--}}
        {{--    })--}}
        {{--});--}}

        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }

        $('#type_of_iptsp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#iptsp_licensese_area_division').val("");
                $('#district').css('display','none');
                $('#thana').css('display','none');
                let multiDistrict = @json($multiDistricts);
                let district_list = "";
                for(const key in multiDistrict){
                    district_list +=`<option value="${key}">${multiDistrict[key]}</option>`;
                }
                $("#coverage_district").html("");
                $("#coverage_district").html(district_list);
                $("#coverage_out_of_district").html("");
                $("#coverage_out_of_district").html(district_list);
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }

            if(this.value == 3){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
            }

            if(this.value == 4){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');
            }
        });

        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        let company_type = "{{$companyInfo->org_type}}";
        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        $('.add_row').click(function(){
            let btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                type: "POST",
                url: "{{ url('iptsp-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    getHelpText();
                }
            });
        });

        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        $(".multiple_select2").select2({
            //maximumSelectionLength: 1
            closeOnSelect : false,
            placeholder : "Select",
            allowHtml: true,
            allowClear: true,
            tags: true // создает новые опции на лету
        });

        //Load list of directors
        function LoadListOfDirectors() {
            $.ajax({
                url: "{{ url('client/company-profile/load-listof-directors-session') }}",
                type: "POST",
                data: {
                    {{-- app_id: "{{ Encryption::encodeId($appInfo->id) }}", --}}
                        {{-- process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}", --}}
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    let html = '';
                    if (response.responseCode == 1) {

                        let edit_url = "{{ url('/client/company-profile/edit-director') }}";
                        let delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                        let count = 1;
                        $.each(response.data, function(id, value) {
                            let sl = count++;
                            html += '<tr>';
                            html += '<td>' + sl + '</td>';
                            html += '<td>' + value.l_director_name + '</td>';
                            html += '<td>' + value.l_director_designation + '</td>';
                            html += '<td>' + value.nationality + '</td>';
                            html += '<td>' + value.nid_etin_passport + '</td>';
                            html += '<td>' +
                                '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' +
                                edit_url + '/' + id +
                                '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                            if (sl != 1) {
                                html += '<a data-action="' + delete_url + '/' + id +
                                    '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                            }
                            html += '</td>';
                            html += '</tr>';
                        });

                        if (response.ceoInfo != null) {
                            $("#ceoInfoDIV").removeClass('hidden');

                            $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                            let date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                            $("#company_ceo_dob").val(date_of_birth);
                            $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                            $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                            if (response.ceoInfo.nationality == 'Bangladeshi') {
                                $("#company_ceo_nationality").attr('readonly', true);
                                $("#company_ceo_nationality").val(18);
                            }

                            if (response.ceoInfo.identity_type == 'passport') {
                                $("#company_ceo_passport_section").removeClass("hidden");
                                $("#company_ceo_nid_section").addClass("hidden");
                                $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                                $("#company_ceo_nationality").attr('readonly', false);
                                $("#company_ceo_nationality").val('');
                                $("#company_ceo_nid").val('');
                            } else {
                                $("#company_ceo_passport_section").addClass("hidden");
                                $("#company_ceo_nid_section").removeClass("hidden");
                                $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                                $("#company_ceo_passport").val('');
                            }
                        } else {
                            $(".ceoInfoDirector").removeClass('hidden');
                            // $("#ceoInfoDIV").addClass('hidden');
                        }

                    } else {
                        html += '<tr>';
                        html += '<td colspan="5" class="text-center">' +
                            '<span class="text-danger">No data available!</span>' + '</td>';
                        html += '</tr>';
                    }
                    $('#directorList tbody').html(html);
                }
            });
        }

    });

    function investmentInfo(){
        let foreign_investment = parseFloat($('#foreign_investment').val()) || 0;
        let local_investment = parseFloat($('#local_investment').val()) || 0;
        let total_investment = parseInt(local_investment + foreign_investment);
        if(total_investment > 100){
            alert('Total Investment amount cannot be more than 100');
            // $('#foreign_investment').addClass('has-error');
            $('#local_investment').addClass("error");
            $('#foreign_investment').addClass("error");
            $('#total_investment').addClass("error");
            return;
        }
        $('#total_investment').val(total_investment);
    }

    function openPreviewV2() {
        let preview = 1;
        // If select online payment
        const onlinePayment = "{{config('payment.online_payment')}}";
        if ($("#online_payment").is(':checked') && !onlinePayment) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }

        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }

        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }
</script>

<script type="text/javascript">
    $(function () {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.declaration_q1_datetimepicker').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        $('.declaration_q3_datetimepicker').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
    $(document).ready(function() {
        $("#gross_revenue_eamed_in_last_year_img").change(function() {
            var file = this.files[0];
            var fileType = file.type;
            if (!(fileType == "image/jpeg" || fileType == "image/png" || fileType == "application/pdf")) {
                alert("Please upload only image or PDF files.");
                $(this).val('');
            }
        });
    });
</script>
